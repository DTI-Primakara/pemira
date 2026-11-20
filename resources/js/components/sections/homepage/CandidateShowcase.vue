<script setup lang="ts">
import type { ElectionShowcase } from '@/types';

defineProps<{
    elections: ElectionShowcase[];
}>();
</script>

<template>
    <div class="relative flex w-full flex-col items-center justify-center gap-12">
        <p v-if="elections.length === 0" class="px-6 py-10 text-center font-dmsans text-sm text-black opacity-70 sm:text-base">
            Daftar kandidat belum tersedia. Nantikan pengumuman dari panitia PEMIRA.
        </p>

        <div v-for="election in elections" :key="election.id" class="flex w-full flex-col items-center justify-center gap-5 md:gap-8">
            <div class="flex flex-col items-center justify-center">
                <h3 class="relative font-unbounded text-2xl leading-[1.5em] font-bold text-black sm:text-3xl md:text-4xl lg:text-5xl">
                    {{ election.shortLabel }}
                </h3>
                <h4 class="text-blue relative px-16 text-center font-unbounded text-lg leading-[1.5em] font-bold sm:px-12 sm:text-xl md:text-2xl">
                    {{ election.title }}
                </h4>
            </div>

            <div class="md:flex-unwrap flex flex-row flex-wrap items-center justify-center gap-2 px-12 sm:gap-3 md:gap-6">
                <template v-for="candidate in election.candidates" :key="candidate.id">
                    <img
                        v-if="candidate.image_url"
                        :src="candidate.image_url"
                        :alt="`${candidate.position || election.title} nomor urut ${candidate.number} — ${candidate.name_candidates}`"
                        loading="lazy"
                        class="w-[20vw] max-w-72 min-w-48 sm:min-w-36"
                    />
                    <!-- Keeps the row readable when a photo has not been uploaded yet. -->
                    <div
                        v-else
                        class="flex aspect-[3/4] w-[20vw] max-w-72 min-w-48 flex-col items-center justify-center gap-1 bg-slate-100 sm:min-w-36"
                    >
                        <span class="font-unbounded text-3xl font-bold text-slate-400">
                            {{ String(candidate.number).padStart(2, '0') }}
                        </span>
                        <span class="px-3 text-center font-dmsans text-xs text-slate-500">{{ candidate.name_candidates }}</span>
                    </div>
                </template>
            </div>
        </div>

        <!-- DECORATION -->
        <div class="align-items-start absolute top-0 left-0 flex h-full w-full flex-row justify-between">
            <!-- LEFT -->
            <div class="absolute top-0 left-0 h-12 w-32 bg-gradient-white-26 md:w-64"></div>
            <div class="bg-setup top-0 left-0 h-full w-20 bg-[url(/images/candidate_side_2026.png)] sm:w-[15vw]"></div>
            <div class="absolute bottom-0 left-0 h-12 w-32 rotate-180 bg-gradient-white-26 md:w-64"></div>
            <!-- RIGHT -->
            <div class="absolute top-0 right-0 h-12 w-32 bg-gradient-white-26 md:w-64"></div>
            <div class="bg-setup top-0 right-0 h-full w-20 bg-[url(/images/candidate_side_2_2026.png)] sm:w-[15vw]"></div>
            <div class="absolute right-0 bottom-0 h-12 w-32 rotate-180 bg-gradient-white-26 md:w-64"></div>
        </div>
    </div>
</template>
