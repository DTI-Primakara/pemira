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
        <h2 class="max-w-xs text-center font-unbounded text-2xl font-semibold">{{ title }}</h2>
        <div class="flex w-full flex-row items-center justify-center gap-4">
            <div v-for="(data, key) in items" :key="key" class="flex flex-col items-center justify-center gap-4">
                <div class="relative h-72 w-40 overflow-hidden rounded-sm bg-slate-200">
                    <div
                        class="absolute bottom-0 left-0 w-full bg-[#4342ff] transition-all duration-500"
                        :style="{ height: getPercentage(data.votes.length) + '%' }"
                    ></div>
                    <p
                        class="absolute -bottom-20 w-full text-center font-unbounded text-2xl font-semibold text-white transition-all duration-300 group-hover:bottom-4"
                    >
                        {{ getPercentage(data.votes.length).toFixed(1) }}%
                    </p>
                </div>
                <div class="flex flex-col items-center justify-center gap-2">
                    <h3 class="max-w-[160px] text-center font-unbounded text-xl font-semibold">{{ data.name_candidates }}</h3>
                    <p class="font-dmsans text-base text-black opacity-70">Paslon No Undi 0{{ data.number }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
