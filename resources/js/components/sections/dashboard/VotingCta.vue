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
        class="relative flex max-h-[22rem] flex-1 cursor-pointer flex-col items-center justify-center rounded-xl p-8 text-center"
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
        <div class="relative mb-6">
            <img :src="icon" :alt="iconAlt" />
        </div>

        <!-- teks -->
        <h3 class="font-unbounded text-2xl font-bold text-slate-800">{{ title }}</h3>
        <p class="mt-2 font-dmsans text-slate-500 dark:text-slate-400">{{ desc }}</p>
    </div>
</template>
