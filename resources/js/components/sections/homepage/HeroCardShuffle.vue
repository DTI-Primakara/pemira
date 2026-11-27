<script setup lang="ts">
import type { ShowcaseCandidate } from '@/types';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const props = withDefaults(defineProps<{ candidates?: ShowcaseCandidate[] }>(), {
    candidates: () => [],
});

const DURATION = 600; // ms, samakan dengan duration-[600ms] di template
const INTERVAL = 3000;

/*
 * Two different animations on purpose. The stacked shuffle reads well in the
 * wide half-column it gets on desktop, but on a phone the cards sit nearly on
 * top of one another and the motion turns into a flicker. Narrow screens get a
 * reel instead: one paslon centred, its neighbours peeking, sliding right to
 * left, edge to edge so the screen itself does the cropping.
 *
 * Both are rendered and CSS picks between them, so the correct one is already
 * in place at first paint and server rendering stays truthful. Only the timer
 * for the variant actually on screen runs.
 */
const MOBILE_QUERY = '(max-width: 767px)';

const isMobile = ref(false);
const prefersReducedMotion = ref(false);

interface DeckCard {
    key: string;
    /** Null when the paslon has no artwork at all; a placeholder stands in. */
    src: string | null;
    alt: string;
}

/**
 * The deck, straight from the ballot.
 *
 * `image_url` is the designed homepage card when one was uploaded and the
 * ballot photo otherwise, so a paslon shows whatever picture exists. One with
 * neither gets a plain placeholder, and an empty database gets three of them,
 * rather than a hero with a hole in it.
 */
const source = computed<DeckCard[]>(() => {
    if (props.candidates.length === 0) {
        return [1, 2, 3].map((n) => ({ key: `kosong-${n}`, src: null, alt: '' }));
    }

    return props.candidates.map((candidate) => ({
        key: `paslon-${candidate.id}`,
        src: candidate.image_url,
        alt: `${candidate.position || 'Calon'} nomor urut ${candidate.number} — ${candidate.name_candidates}`,
    }));
});

/** Shuffled on mount; both layouts draw from this one list. */
const deck = ref<DeckCard[]>([]);

/* ---------------------------------------------------------------- desktop */

const current = ref(0);
const leaving = ref(false); // true = fase 1 (kartu depan keluar ke kiri)

let swapTimeout: ReturnType<typeof setTimeout> | undefined;

const nextStacked = () => {
    if (leaving.value || deck.value.length < 2) return;

    // Fase 1: kartu depan geser ke kiri + miring, kartu lain geser ke kanan
    leaving.value = true;

    // Fase 2: ganti urutan (kartu no. 2 jadi depan, kartu lama pindah ke belakang)
    swapTimeout = setTimeout(() => {
        current.value = (current.value + 1) % deck.value.length;
        leaving.value = false;
    }, DURATION);
};

const posOf = (i: number) => {
    const n = deck.value.length || 1;

    return (i - current.value + n) % n;
};

const posClass = (i: number) => {
    const pos = posOf(i);

    if (leaving.value) {
        if (pos === 0) return 'z-40 -translate-x-[70%] -rotate-12'; // keluar ke kiri
        if (pos === 1) return 'z-20 translate-x-8 -translate-y-2 rotate-6'; // geser ke kanan
        if (pos === 2) return 'z-10 translate-x-4 -translate-y-1 rotate-3 scale-95';
    } else {
        if (pos === 0) return 'z-30 translate-x-0 translate-y-0 rotate-0 scale-100'; // depan
        if (pos === 1) return 'z-20 translate-x-4 -translate-y-2 rotate-3'; // belakang (kanan)
        if (pos === 2) return 'z-10 translate-x-0 translate-y-0 rotate-0 scale-95'; // paling belakang
    }

    // Past the third card: parked behind the deck, out of sight. The stack
    // still shows three at a time, but it now works through every paslon
    // instead of a fixed trio.
    return 'z-0 scale-90 opacity-0';
};

// Overlay hitam hanya hilang di kartu paling depan
const overlayClass = (i: number) => (posOf(i) === 0 ? 'opacity-0' : 'opacity-60');

/* ----------------------------------------------------------------- mobile */

/** Width of one card as a share of the screen, leaving both neighbours peeking. */
const CARD = 70;
const SIDE = (100 - CARD) / 2;

const slide = ref(0);
const sliding = ref(true);

let rewind: ReturnType<typeof setTimeout> | undefined;

/**
 * The reel is the deck three times over, starting on the middle copy. That is
 * what guarantees a card on both sides from the very first frame — a single
 * copy leaves the opening shot lopsided, with nothing to the left of the
 * centred paslon.
 *
 * Reaching the last copy means the same card is centred again, so the position
 * resets to the middle without a transition and the loop never visibly
 * rewinds.
 */
const loop = computed(() => [...deck.value, ...deck.value, ...deck.value]);

const trackStyle = computed(() => ({
    transform: `translateX(calc(${SIDE}% - ${slide.value * CARD}%))`,
}));

const isCentre = (index: number) => index === slide.value;

/**
 * How each card sits. The two neighbours fan out six degrees and are pulled
 * back towards the middle so they tuck under the centred card instead of
 * standing beside it in a row.
 */
const cardClass = (index: number) => {
    if (isCentre(index)) return 'z-20 rotate-0 scale-100';
    if (index === slide.value - 1) return 'z-10 translate-x-[14%] -rotate-6 scale-[0.88]';
    if (index === slide.value + 1) return 'z-10 -translate-x-[14%] rotate-6 scale-[0.88]';

    return 'z-0 scale-[0.88]';
};

const nextSlide = () => {
    if (deck.value.length < 2) return;

    slide.value += 1;

    if (slide.value < deck.value.length * 2) return;

    rewind = setTimeout(() => {
        sliding.value = false;
        slide.value = deck.value.length;

        // Two frames: one for the browser to apply the jump, one before the
        // transition is allowed back, or it animates the jump itself.
        requestAnimationFrame(() => requestAnimationFrame(() => (sliding.value = true)));
    }, DURATION);
};

/* -------------------------------------------------------------- lifecycle */

let timer: ReturnType<typeof setInterval> | undefined;
let media: MediaQueryList | undefined;

const tick = () => (isMobile.value ? nextSlide() : nextStacked());

const applyViewport = (event: MediaQueryList | MediaQueryListEvent) => {
    isMobile.value = event.matches;
};

onMounted(() => {
    prefersReducedMotion.value = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    media = window.matchMedia(MOBILE_QUERY);
    applyViewport(media);
    media.addEventListener('change', applyViewport);

    // Shuffled here rather than in setup: the server has no business picking an
    // order the client would then disagree with mid-hydration.
    //
    // Placed without a transition, because moving to the middle copy is
    // bookkeeping, not motion, and animating it makes the reel visibly scoot
    // across the screen the moment the page loads.
    sliding.value = false;
    deck.value = shuffle(source.value);
    slide.value = deck.value.length;

    requestAnimationFrame(() => requestAnimationFrame(() => (sliding.value = true)));

    // Motion that moves on its own is exactly what a reduced-motion preference
    // asks us not to do; the first card simply stays put.
    if (!prefersReducedMotion.value) {
        timer = setInterval(tick, INTERVAL);
    }
});

onBeforeUnmount(() => {
    clearInterval(timer);
    clearTimeout(swapTimeout);
    clearTimeout(rewind);
    media?.removeEventListener('change', applyViewport);
});

/** Fisher–Yates, on a copy. */
function shuffle(items: DeckCard[]): DeckCard[] {
    const copy = [...items];

    for (let i = copy.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [copy[i], copy[j]] = [copy[j], copy[i]];
    }

    return copy;
}
</script>

<template>
    <!--
        The root keeps the desktop box exactly as it was: aspect ratio and
        max-width apply from md up only, so the hero column is untouched there
        while the phone reel is free to run edge to edge.
    -->
    <div class="relative w-full md:aspect-[3/4] md:max-w-sm">
        <!--
            MOBILE: reel paslon, bergantian dari kanan ke kiri.
            -mx-6 membatalkan padding hero supaya kartu dipotong tepi layar,
            bukan oleh ruang kosong di kiri dan kanan.
        -->
        <div class="relative -mx-6 w-screen overflow-hidden md:hidden">
            <div class="flex w-full items-center ease-in-out" :class="sliding ? 'transition-transform duration-[600ms]' : ''" :style="trackStyle">
                <div v-for="(card, index) in loop" :key="`${card.key}-${index}`" class="shrink-0" :style="{ width: `${CARD}%` }">
                    <!--
                        No fixed ratio and no object-cover: the card box follows
                        the artwork's own proportions. Forcing a ratio here was
                        shaving the number and the name off the sides.
                    -->
                    <div class="relative overflow-hidden rounded-2xl transition-transform duration-[600ms] ease-in-out" :class="cardClass(index)">
                        <img v-if="card.src" :src="card.src" :alt="card.alt" loading="lazy" class="block h-auto w-full" />
                        <div v-else class="aspect-[3/4] w-full bg-gradient-to-br from-[#E4E4EA] to-[#C3C3CE]"></div>

                        <div
                            class="pointer-events-none absolute inset-0 bg-[#0F0F0F] transition-opacity duration-[600ms] ease-in-out"
                            :class="isCentre(index) ? 'opacity-0' : 'opacity-60'"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- DESKTOP: tumpukan kartu -->
        <div
            v-for="(card, i) in deck"
            :key="card.key"
            class="absolute inset-0 hidden overflow-hidden rounded-2xl transition-all duration-[600ms] ease-in-out md:block"
            :class="posClass(i)"
        >
            <img v-if="card.src" :src="card.src" :alt="card.alt" class="h-full w-full object-cover" />
            <div v-else class="h-full w-full bg-gradient-to-br from-[#E4E4EA] to-[#C3C3CE]"></div>

            <!-- overlay #0F0F0F 60% -->
            <div class="pointer-events-none absolute inset-0 bg-[#0F0F0F] transition-opacity duration-[600ms] ease-in-out" :class="overlayClass(i)" />
        </div>
    </div>
</template>
