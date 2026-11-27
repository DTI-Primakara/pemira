<script setup lang="ts">
/**
 * The opening panel: a full blue screen that spells PEMIRA 2026 a letter at a
 * time, then wipes upward to hand over the page.
 *
 * Visibility is decided in CSS (`.homepage-intro`), not here, so a browser
 * without JavaScript — or a reader who asked for reduced motion — never sees a
 * blue panel it has no way to dismiss.
 */
const LINES = ['PEMIRA', '2026'];
</script>

<template>
    <div class="homepage-intro fixed inset-0 z-[100] flex-col items-center justify-center bg-gradient-main-26" aria-hidden="true">
        <!-- Each line is its own mask, so the letters rise out of nothing. -->
        <div
            v-for="(line, lineIndex) in LINES"
            :key="line"
            class="overflow-hidden px-6 leading-[0.95]"
            :class="lineIndex === 0 ? 'text-white' : 'text-[#DFFF00]'"
        >
            <span
                v-for="(character, characterIndex) in line.split('')"
                :key="`${line}-${characterIndex}`"
                data-intro-char
                class="inline-block font-unbounded text-[15vw] font-bold tracking-tight sm:text-[12vw] lg:text-[9rem]"
            >
                {{ character }}
            </span>
        </div>
    </div>
</template>
