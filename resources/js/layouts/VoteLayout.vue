<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const loading = ref(false);

onMounted(() => {
    router.on('start', () => {
        loading.value = true;
    });
    router.on('finish', () => {
        loading.value = false;
    });
});
</script>

<template>
    <div v-if="loading" class="fixed inset-0 z-50 flex items-center justify-center bg-white">
        <span class="text-xl font-bold">Loading...</span>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8">
        <img src="/logo.svg" alt="logo-pemira" loading="lazy" class="h-8 md:h-12" />
    </div>

    <div class="mx-auto max-w-7xl px-4">
        <slot />
    </div>
</template>

<style scoped></style>
