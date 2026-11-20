<script setup lang="ts">
withDefaults(
    defineProps<{
        /** How many "PEMIRA 2026 + icon" pairs make up one loop group. */
        repeat?: number;
    }>(),
    { repeat: 8 },
);

/**
 * The track holds exactly two identical groups and slides by -50%,
 * so the loop restarts on a pixel-identical frame (no visible jump)
 * and the ribbon is already full on the first frame.
 */
const groups = 2;
</script>

<template>
    <div class="pointer-events-none relative z-20 w-full overflow-hidden py-12 select-none sm:py-16 md:py-24" aria-hidden="true">
        <!-- BACK RIBBON: tilted the other way and dimmed by an overlay -->
        <div
            class="absolute top-1/2 left-1/2 w-[140%] -translate-x-1/2 -translate-y-1/2 rotate-[4deg] overflow-hidden bg-gradient-main-26 py-2.5 sm:py-3.5 md:py-5"
        >
            <div class="marquee-26 marquee-26-reverse" style="--marquee-26-duration: 46s">
                <div v-for="group in groups" :key="group" class="flex shrink-0 items-center gap-6 pr-6 sm:gap-8 sm:pr-8 md:gap-12 md:pr-12">
                    <template v-for="item in repeat" :key="item">
                        <span class="font-unbounded text-lg font-bold whitespace-nowrap text-white sm:text-2xl md:text-4xl">PEMIRA 2026</span>
                        <img src="/images/asset26-1.svg" alt="" class="h-7 w-7 shrink-0 sm:h-9 sm:w-9 md:h-12 md:w-12" />
                    </template>
                </div>
            </div>
            <div class="absolute inset-0 bg-[#01006b]/55"></div>
        </div>

        <!-- FRONT RIBBON -->
        <div
            class="absolute top-1/2 left-1/2 w-[140%] -translate-x-1/2 -translate-y-1/2 -rotate-[3deg] overflow-hidden bg-gradient-main-26 py-2.5 shadow-[0_18px_40px_-20px_rgba(1,0,120,0.65)] sm:py-3.5 md:py-5"
        >
            <div class="marquee-26" style="--marquee-26-duration: 38s">
                <div v-for="group in groups" :key="group" class="flex shrink-0 items-center gap-6 pr-6 sm:gap-8 sm:pr-8 md:gap-12 md:pr-12">
                    <template v-for="item in repeat" :key="item">
                        <span class="font-unbounded text-lg font-bold whitespace-nowrap text-white sm:text-2xl md:text-4xl">PEMIRA 2026</span>
                        <img src="/images/asset26-1.svg" alt="" class="h-7 w-7 shrink-0 sm:h-9 sm:w-9 md:h-12 md:w-12" />
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
