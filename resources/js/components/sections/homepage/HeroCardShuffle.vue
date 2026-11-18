<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';

const candidates = [
    { id: 1, img: '/candidates/candidate-anonym.png' },
    { id: 2, img: '/candidates/candidate-anonym.png' },
    { id: 3, img: '/candidates/candidate-anonym.png' },
];

const DURATION = 600; // ms, samakan dengan duration-[600ms] di template
const INTERVAL = 3000;

const current = ref(0);
const leaving = ref(false); // true = fase 1 (kartu depan keluar ke kiri)

let timer: ReturnType<typeof setInterval>;
let swapTimeout: ReturnType<typeof setTimeout>;

const next = () => {
    if (leaving.value) return;

    // Fase 1: kartu depan geser ke kiri + miring, kartu lain geser ke kanan
    leaving.value = true;

    // Fase 2: ganti urutan (kartu no. 2 jadi depan, kartu lama pindah ke belakang tengah)
    swapTimeout = setTimeout(() => {
        current.value = (current.value + 1) % candidates.length;
        leaving.value = false;
    }, DURATION);
};

onMounted(() => (timer = setInterval(next, INTERVAL)));
onBeforeUnmount(() => {
    clearInterval(timer);
    clearTimeout(swapTimeout);
});

const posOf = (i: number) => {
    const n = candidates.length;
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
</script>

<template>
    <div class="relative aspect-[3/4] w-full max-w-sm">
        <div
            v-for="(c, i) in candidates"
            :key="c.id"
            class="absolute inset-0 overflow-hidden rounded-2xl transition-all duration-[600ms] ease-in-out"
            :class="posClass(i)"
        >
            <img :src="c.img" class="h-full w-full object-cover" alt="" />

            <!-- overlay #0F0F0F 60% -->
            <div class="pointer-events-none absolute inset-0 bg-[#0F0F0F] transition-opacity duration-[600ms] ease-in-out" :class="overlayClass(i)" />
        </div>
    </div>
</template>
