<script setup lang="ts">
import { ref} from 'vue';
import {InfoIcon, X} from 'lucide-vue-next'

defineProps({
    number: {
        type: String,
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
    position: {
        type: String,
        required: true,
    },
    imageUrl: {
        type: String,
        required: true,
    },
    isSelected: {
        type: Boolean,
        default: false,
    },
    vision: {
        type: String,
        default: 'Terwujudnya dunia di mana setiap perut mengenal kenyang yang paripurna.',
    },
    mission: {
        type: String,
        default: "Melaksanakan ekspedisi rasa tanpa batas dengan semangat 'Makan!', menjelajahi keanekaragaman rasa, mengutamakan porsi berdaulat, dan menciptakan kebersamaan di meja makan.",
    },
});

const isOverlayVisible = ref<boolean>(false);
</script>

<template>
    <div
        class="group relative transform cursor-pointer overflow-hidden rounded-xl bg-blue-600 transition-all duration-300 hover:-translate-y-2"
        :class="isSelected ? 'ring-4 ring-blue-600 ring-offset-2' : 'ring-1 ring-gray-200 dark:ring-gray-700'"
    >
        <div class="absolute inset-y-0 left-0 w-20 opacity-30 md:opacity-90">
            <img src="/images/pattern-card.svg" alt="Pattern" class="h-full w-full object-cover" />
        </div>
        <div class="absolute inset-y-0 -right-5 w-20 opacity-30 md:opacity-90">
            <img src="/images/pattern-card-right.svg" alt="Pattern" class="h-full w-full object-cover" />
        </div>

        <button
            @click.stop="isOverlayVisible = !isOverlayVisible"
            v-show="!isOverlayVisible"
            class="absolute top-4 right-4 z-30 flex items-center justify-center w-8 h-8 bg-black/30 rounded-full text-white md:hidden"
            aria-label="Tampilkan Visi & Misi"
        >
            <InfoIcon/>
        </button>

        <div class="relative z-10 grid h-96 grid-cols-5 gap-4 px-8 pt-8">
            <div class="col-span-2 flex flex-col gap-4">
                <div class="flex h-1/2 items-start justify-start rounded-lg">
                    <span class="font-unbounded text-7xl font-extrabold text-white">{{ number }}</span>
                </div>
                <div class="-ml-4 flex h-1/2 flex-col justify-center rounded-lg p-4">
                    <h3 class="font-unbounded text-3xl font-bold text-white md:text-6xl">{{ name }}</h3>
                    <p class="text-white opacity-80 md:text-lg">{{ position }}</p>
                </div>
            </div>

            <div class="col-span-3 overflow-hidden rounded-lg">
                <img
                    :src="imageUrl"
                    :alt="name"
                    class="h-full w-full object-cover object-top grayscale transition-transform duration-500 group-hover:scale-105"
                />
            </div>
        </div>

        <div
            class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-black/80 p-6 text-white opacity-0 transition-opacity duration-300 group-hover:opacity-100"
            :class="{ '!opacity-100': isOverlayVisible }"
        >
            <h4 class="mb-2 font-unbounded text-xl font-bold">Visi & Misi</h4>
            <div class="space-y-2 text-center text-sm">
                <div>
                    <p class="font-semibold">Visi:</p>
                    <p class="opacity-80">{{ vision }}</p>
                </div>
                <div>
                    <p class="font-semibold">Misi:</p>
                    <p class="opacity-80">{{ mission }}</p>
                </div>
            </div>
            <button
                @click.stop="isOverlayVisible = false"
                class="absolute top-4 right-4 z-30 text-white md:hidden"
                aria-label="Tutup Visi & Misi"
            >
                <X/>
            </button>
        </div>
    </div>
</template>
