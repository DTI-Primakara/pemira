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
 * left.
 *
 * Both are rendered and CSS picks between them, so the correct one is already
 * in place at first paint and server rendering stays truthful. Only the timer
 * for the variant actually on screen runs.
 */
const MOBILE_QUERY = '(max-width: 767px)';

const isMobile = ref(false);
const prefersReducedMotion = ref(false);

/* ---------------------------------------------------------------- desktop */

const placeholders = [
    { id: 1, img: '/candidates/candidate-anonym.png' },
    { id: 2, img: '/candidates/candidate-anonym.png' },
    { id: 3, img: '/candidates/candidate-anonym.png' },
];

const current = ref(0);
const leaving = ref(false); // true = fase 1 (kartu depan keluar ke kiri)

let swapTimeout: ReturnType<typeof setTimeout> | undefined;

const nextStacked = () => {
    if (leaving.value) return;

    // Fase 1: kartu depan geser ke kiri + miring, kartu lain geser ke kanan
    leaving.value = true;

    // Fase 2: ganti urutan (kartu no. 2 jadi depan, kartu lama pindah ke belakang tengah)
    swapTimeout = setTimeout(() => {
        current.value = (current.value + 1) % placeholders.length;
        leaving.value = false;
    }, DURATION);
};

const posOf = (i: number) => {
    const n = placeholders.length;
    return (i - current.value + n) % n;
};

const posClass = (i: number) => {
    const pos = posOf(i);

    if (leaving.value) {
        if (pos === 0) return 'z-40 -translate-x-[70%] -rotate-12'; // keluar ke kiri
        if (pos === 1) return 'z-20 translate-x-8 -translate-y-2 rotate-6'; // geser ke kanan
        return 'z-10 translate-x-4 -translate-y-1 rotate-3 scale-95';
    }

    if (pos === 0) return 'z-30 translate-x-0 translate-y-0 rotate-0 scale-100'; // depan
    if (pos === 1) return 'z-20 translate-x-4 -translate-y-2 rotate-3'; // belakang (kanan)
    return 'z-10 translate-x-0 translate-y-0 rotate-0 scale-95'; // paling belakang (tengah)
};

// Overlay hitam hanya hilang di kartu paling depan
const overlayClass = (i: number) => (posOf(i) === 0 ? 'opacity-0' : 'opacity-60');

/* ----------------------------------------------------------------- mobile */

/** Width of one card as a share of the reel, leaving both neighbours peeking. */
const CARD = 72;
const SIDE = (100 - CARD) / 2;

const withArtwork = computed(() => props.candidates.filter((candidate) => candidate.image_url));

const reel = ref<ShowcaseCandidate[]>([]);
const slide = ref(0);
const sliding = ref(true);

let rewind: ReturnType<typeof setTimeout> | undefined;

/**
 * The reel is the paslon list three times over, and it starts on the middle
 * copy. That is what guarantees a card on both sides from the very first
 * frame — a single copy leaves the opening shot lopsided, with nothing to the
 * left of the centred paslon.
 *
 * Reaching the last copy means the same card is centred again, so the position
 * can be reset to the middle without a transition and the loop never visibly
 * rewinds.
 */
const loop = computed(() => [...reel.value, ...reel.value, ...reel.value]);

const trackStyle = computed(() => ({
    transform: `translateX(calc(${SIDE}% - ${slide.value * CARD}%))`,
}));

const isCentre = (index: number) => reel.value.length > 0 && index % reel.value.length === slide.value % reel.value.length;

const nextSlide = () => {
    if (reel.value.length < 2) return;

    slide.value += 1;

    if (slide.value < reel.value.length * 2) return;

    // Back to the middle copy the moment its first card is centred again.
    rewind = setTimeout(() => {
        sliding.value = false;
        slide.value = reel.value.length;

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
    reel.value = shuffle(withArtwork.value);
    slide.value = reel.value.length;

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
function shuffle(items: ShowcaseCandidate[]): ShowcaseCandidate[] {
    const copy = [...items];

    for (let i = copy.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [copy[i], copy[j]] = [copy[j], copy[i]];
    }

    return copy;
}
</script>

<template>
    <div class="w-full">
        <!-- MOBILE: reel paslon, bergantian dari kanan ke kiri -->
        <div v-if="reel.length > 0" class="relative w-full overflow-hidden md:hidden">
            <div class="flex aspect-[3/4] w-full ease-in-out" :class="sliding ? 'transition-transform duration-[600ms]' : ''" :style="trackStyle">
                <div v-for="(candidate, index) in loop" :key="`${candidate.id}-${index}`" class="h-full shrink-0 px-1" :style="{ width: `${CARD}%` }">
                    <div
                        class="relative h-full w-full overflow-hidden rounded-2xl transition-transform duration-[600ms] ease-in-out"
                        :class="isCentre(index) ? 'scale-100' : 'scale-[0.88]'"
                    >
                        <img
                            :src="candidate.image_url!"
                            :alt="`${candidate.position || 'Calon'} nomor urut ${candidate.number} — ${candidate.name_candidates}`"
                            loading="lazy"
                            class="h-full w-full object-cover"
                        />
                        <div
                            class="pointer-events-none absolute inset-0 bg-[#0F0F0F] transition-opacity duration-[600ms] ease-in-out"
                            :class="isCentre(index) ? 'opacity-0' : 'opacity-60'"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- MOBILE tanpa paslon: jatuh kembali ke tumpukan supaya hero tidak kosong -->
        <div v-else class="relative aspect-[3/4] w-full max-w-sm md:hidden">
            <div
                v-for="(c, i) in placeholders"
                :key="c.id"
                class="absolute inset-0 overflow-hidden rounded-2xl transition-all duration-[600ms] ease-in-out"
                :class="posClass(i)"
            >
                <img :src="c.img" class="h-full w-full object-cover" alt="" />
                <div
                    class="pointer-events-none absolute inset-0 bg-[#0F0F0F] transition-opacity duration-[600ms] ease-in-out"
                    :class="overlayClass(i)"
                />
            </div>
        </div>

        <!-- DESKTOP: tumpukan kartu, tidak diubah -->
        <div class="relative hidden aspect-[3/4] w-full max-w-sm md:block">
            <div
                v-for="(c, i) in placeholders"
                :key="c.id"
                class="absolute inset-0 overflow-hidden rounded-2xl transition-all duration-[600ms] ease-in-out"
                :class="posClass(i)"
            >
                <img :src="c.img" class="h-full w-full object-cover" alt="" />

                <!-- overlay #0F0F0F 60% -->
                <div
                    class="pointer-events-none absolute inset-0 bg-[#0F0F0F] transition-opacity duration-[600ms] ease-in-out"
                    :class="overlayClass(i)"
                />
            </div>
        </div>
    </div>
</template>
