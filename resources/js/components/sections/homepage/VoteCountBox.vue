<script setup lang="ts">
import { Candidate } from '@/types';

// Ubah dari array menjadi object/record
const { title, items } = defineProps<{
    title: string;
    items: Candidate[];
}>();

let totalVote = 0;

Object.values(items).forEach((item) => {
    totalVote += item.votes.length;
});

function getPercentage(count: number) {
    if (totalVote === 0) return 0;
    return (count / totalVote) * 100;
}
</script>
<template>
    <div class="group flex flex-col items-center justify-center gap-4 hover:bg-white">
        <h2 class="max-w-xs text-center font-unbounded text-lg font-semibold md:text-xl xl:text-2xl">{{ title }}</h2>
        <div class="flex w-full flex-row items-center justify-center gap-4">
            <div v-for="(data, key) in items" :key="key" class="flex flex-col items-center justify-center gap-4">
                <div class="relative h-52 w-32 overflow-hidden rounded-sm bg-slate-200 sm:h-64 md:h-72 md:w-40">
                    <div
                        class="absolute bottom-0 left-0 w-full bg-[#4342ff] transition-all duration-500"
                        :style="{ height: getPercentage(data.votes.length) + '%' }"
                    ></div>
                    <p
                        class="absolute bottom-4 w-full text-center font-unbounded text-lg font-semibold text-white transition-all duration-300 group-hover:bottom-4 sm:-bottom-20 sm:text-xl md:text-2xl"
                    >
                        {{ getPercentage(data.votes.length).toFixed(1) }}%
                    </p>
                </div>
                <div class="flex flex-col items-center justify-center gap-2">
                    <h3 class="max-w-[160px] text-center font-unbounded text-base font-semibold sm:text-lg md:text-xl">{{ data.name_candidates }}</h3>
                    <p class="font-dmsans text-sm text-black opacity-70 md:text-base">Paslon No Undi 0{{ data.number }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
