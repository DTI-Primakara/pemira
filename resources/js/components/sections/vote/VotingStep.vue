<script setup lang="ts">
import type { PropType } from 'vue';
import CandidateCard from './CandidateCard.vue';

interface Candidate {
    id: number;
    number: string;
    name_candidates: string;
    position: string;
    image: string;
    vision: string;
    mission: string;
}

defineProps({
    candidates: {
        type: Array as PropType<Candidate[]>,
        required: true,
    },
    stepTitle: {
        type: String,
        required: true,
    },
    selectedCandidate: {
        type: Number,
        required: false, // optional
    },
});

// define emit
const emit = defineEmits<{
    (e: 'vote', id: number): void;
}>();

const selectCandidate = (candidateId: number) => {
    emit('vote', candidateId);
};
</script>

<template>
    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
        <CandidateCard
            v-for="candidate in candidates"
            :key="candidate.id"
            :number="candidate.number"
            :name="candidate.name_candidates"
            :position="candidate.position"
            :image-url="candidate.image"
            :is-selected="selectedCandidate === candidate.id"
            @click="selectCandidate(candidate.id)"
        />
    </div>
</template>
