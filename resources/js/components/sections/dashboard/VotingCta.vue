<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    title: string;
    desc: string;
    icon: string;
    iconAlt: string;
    variant: 'default' | 'success' | 'error' | 'disabled';
}>();

const variantColors = computed(() => {
    switch (props.variant) {
        case 'success':
            return {
                border: 'stroke-green-400 dark:stroke-green-400',
                background: 'bg-green-400/5 dark:bg-green/5',
            };
        case 'error':
            return {
                border: 'stroke-red-400 dark:stroke-red-500',
                background: 'bg-red-400/5 dark:bg-red-500/5',
            };
        case 'disabled':
            return {
                border: 'stroke-slate-400 dark:stroke-slate-700',
                background: 'bg-slate-400/5 dark:bg-slate-700/5',
            };
        case 'default':
        default:
            return {
                border: 'stroke-primary dark:stroke-primary',
                background: 'bg-primary/5 dark:bg-primary/5',
            };
    }
});
</script>

<template>
    <div
        :class="variantColors.background"
        class="relative flex max-h-[14rem] w-full flex-1 flex-col items-center justify-center rounded-xl p-8 text-center sm:max-h-[16rem] md:max-h-[22rem]"
    >
        <!-- dashed border -->
        <svg class="pointer-events-none absolute inset-0 h-full w-full" xmlns="http://www.w3.org/2000/svg">
            <rect
                x="1"
                y="1"
                width="calc(100% - 2px)"
                height="calc(100% - 2px)"
                rx="11"
                ry="11"
                fill="none"
                :class="variantColors.border"
                stroke-width="2"
                stroke-dasharray="20 15"
            />
        </svg>

        <!-- icon -->
        <div class="relative mb-3 aspect-square w-18 sm:mb-6 sm:w-20 md:w-24">
            <img :src="icon" :alt="iconAlt" />
        </div>

        <!-- teks -->
        <h3 class="font-unbounded text-base font-bold text-slate-800 sm:text-lg md:text-xl lg:text-2xl">{{ title }}</h3>
        <p class="mt-2 font-dmsans text-xs text-slate-500 sm:text-base dark:text-slate-400">{{ desc }}</p>

        <button
            v-if="title == 'Start Vote Now'"
            class="mt-4 cursor-pointer rounded-md bg-[#0047FF] px-8 py-3 font-unbounded text-base font-semibold text-white"
        >
            Mulai Voting
        </button>
    </div>
</template>
