<script setup lang="ts">
import { computed } from 'vue';

// Mendefinisikan props yang diterima oleh komponen
const props = withDefaults(defineProps<{
    title: string,
    desc: string,
    iconUrl: string,
    iconAlt: string,
    variant?: 'default' | 'success' | 'error' | 'disabled',
}>(), {
    variant: 'disabled',
});

// Logika untuk mengubah warna berdasarkan prop 'variant'
const variantColors = computed(() => {
    switch (props.variant) {
        case 'success':
            return {
                border: 'stroke-green-400 dark:stroke-green-400',
                background: 'bg-green-400/5 dark:bg-green-400/5'
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
    <div :class="variantColors.background" class="relative w-full rounded-xl flex flex-col sm:flex-row justify-start items-start space-y-4 md:items-center p-6 text-left">
        <!-- border -->
        <svg class="absolute inset-0 w-full h-full pointer-events-none" xmlns="http://www.w3.org/2000/svg">
            <rect x="1" y="1" width="calc(100% - 2px)" height="calc(100% - 2px)" rx="11" ry="11" fill="none" :class="variantColors.border" stroke-width="2" stroke-dasharray="12 8" />
        </svg>

        <!-- icon -->
        <div class="flex-shrink-0  w-10 h-10 rounded-md bg-slate-200 dark:bg-slate-700 mr-5">
            <img :src="iconUrl" :alt="iconAlt">
        </div>

        <!-- teks -->
        <div>
            <h3 class="font-unbounded text-lg font-bold text-slate-800 dark:text-white">{{ title }}</h3>
            <p class="font-dmsans text-slate-500 dark:text-slate-400 mt-1 text-sm leading-relaxed">{{ desc }}</p>
        </div>
    </div>
</template>
