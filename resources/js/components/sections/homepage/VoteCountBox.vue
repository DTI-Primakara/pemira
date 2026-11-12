<script setup lang="ts">
import type { TallyCandidate } from '@/types';
import { computed } from 'vue';

const props = defineProps<{
    title: string;
    items: TallyCandidate[];
}>();

const totalVote = computed(() => props.items.reduce((sum, item) => sum + (item.votes_count ?? 0), 0));

function getPercentage(count: number) {
    if (totalVote.value === 0) return 0;
    return (count / totalVote.value) * 100;
}
</script>

<template>
    <div class="group flex flex-col items-center justify-center gap-4 hover:bg-white">
        <h2 class="max-w-xs text-center font-unbounded text-lg font-semibold md:text-xl xl:text-2xl">{{ title }}</h2>
        <div class="flex w-full flex-row items-center justify-center gap-4">
            <div v-for="data in items" :key="data.id" class="flex flex-col items-center justify-center gap-4">
                <div class="relative h-52 w-32 overflow-hidden rounded-sm bg-slate-200 sm:h-64 md:h-72 md:w-40">
                    <div
                        class="absolute bottom-0 left-0 w-full bg-[#4342ff] transition-all duration-500"
                        :style="{ height: getPercentage(data.votes_count) + '%' }"
                    ></div>
                    <p
                        class="absolute bottom-4 w-full text-center font-unbounded text-lg font-semibold text-white transition-all duration-300 group-hover:bottom-4 sm:-bottom-20 sm:text-xl md:text-2xl"
                    >
                        {{ getPercentage(data.votes_count).toFixed(1) }}%
                    </p>
                </div>
                <div class="flex flex-col items-center justify-center gap-2">
                    <h3 class="max-w-[160px] text-center font-unbounded text-base font-semibold sm:text-lg md:text-xl">{{ data.name_candidates }}</h3>
                    <p class="font-dmsans text-sm text-black opacity-70 md:text-base">
                        Paslon No Undi {{ String(data.number).padStart(2, '0') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
