<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const loading = ref(false)

onMounted(() => {
    router.on('start', () => { loading.value = true })
    router.on('finish', () => { loading.value = false })
})
</script>

<template>
    <div v-if="loading" class="fixed inset-0 flex items-center justify-center bg-white z-50">
        <span class="text-xl font-bold">Loading...</span>
    </div>

    <div class="container mx-auto px-4 py-8">
        <img src="/logo.svg" alt="logo-pemira" loading="lazy" class="h-8 md:h-12">
    </div>

    <div class="container mx-auto px-4">
    <slot/>
    </div>

</template>

<style scoped>

</style>
