<script setup lang="ts">
import { computed } from 'vue';

// Mendefinisikan props yang diterima oleh komponen
const props = withDefaults(
    defineProps<{
        title: string;
        desc: string;
        iconUrl: string;
        iconAlt: string;
        variant?: 'default' | 'success' | 'error' | 'disabled';
    }>(),
    {
        variant: 'disabled',
    },
);

// Logika untuk mengubah warna berdasarkan prop 'variant'
const variantColors = computed(() => {
    switch (props.variant) {
        case 'success':
            return {
                border: 'stroke-green-400 dark:stroke-green-400',
                background: 'bg-green-400/5 dark:bg-green-400/5',
            };
        case 'error':
            return {
                border: 'stroke-red-400 dark:stroke-red-500',
                background: 'bg-red-400/5 dark:bg-red-500/5',
            };
        case 'default':
            return {
                border: 'stroke-blue-500 dark:stroke-blue-500',
                background: 'bg-blue-500/5 dark:bg-blue-500/5',
            };
        case 'disabled':
        default:
            return {
                border: 'stroke-slate-300 dark:stroke-slate-700',
                background: 'bg-slate-300/10 dark:bg-slate-700/10',
            };
    }
});
</script>

<template>
    <div
        :class="variantColors.background"
        class="relative flex w-full flex-col items-start justify-start space-y-4 rounded-xl p-6 text-left sm:flex-row md:items-center"
    >
        <!-- border -->
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
                stroke-dasharray="12 8"
            />
        </svg>

        <!-- icon -->
        <div class="mr-5 h-10 w-10 flex-shrink-0 rounded-md bg-slate-200 dark:bg-slate-700">
            <img :src="iconUrl" :alt="iconAlt" />
        </div>

        <!-- teks -->
        <div>
            <h3 class="font-unbounded text-lg font-bold text-slate-800">{{ title }}</h3>
            <p class="mt-1 font-dmsans text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ desc }}</p>
        </div>
    </div>
</template>
