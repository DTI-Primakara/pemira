<script setup lang="ts">
import type { Candidate } from '@/types';
import CandidateCard from './CandidateCard.vue';

defineProps<{
    candidates: Candidate[];
    stepTitle: string;
    selectedCandidate?: number;
}>();

// define emit
const emit = defineEmits<{
    (e: 'vote', id: number): void;
}>();

const selectCandidate = (candidateId: number) => {
    emit('vote', candidateId);
};
</script>

<template>
    <div class="flex flex-row flex-wrap items-center justify-center gap-5 sm:gap-6 md:gap-8">
        <CandidateCard
            v-for="candidate in candidates"
            :key="candidate.id"
            :number="candidate.number"
            :name="candidate.name_candidates"
            :position="candidate.position"
            :image-url="candidate.image"
            :vision="candidate.vision"
            :mission="candidate.mission"
            :is-selected="selectedCandidate === candidate.id"
            @click="selectCandidate(candidate.id)"
        />
    </div>
</template>
