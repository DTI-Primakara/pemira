<script setup lang="ts">
import CandidateCard from './CandidateCard.vue';
import type { PropType } from 'vue';

interface Candidate {
    id: number;
    number: string;
    name: string;
    position: string;
    imageUrl: string;
}

defineProps({
    candidates: {
        type: Array as PropType<Candidate[]>,
        required: true
    },
    stepTitle: {
        type: String,
        required: true
    },
    selectedCandidate: {
        type: Number,
        required: false // optional
    }
});

// define emit
const emit = defineEmits<{
    (e: 'vote', id: number): void
}>();


const selectCandidate = (candidateId: number) => {
    emit('vote', candidateId);
};
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <CandidateCard
            v-for="candidate in candidates"
            :key="candidate.id"
            :number="candidate.number"
            :name="candidate.name"
            :position="candidate.position"
            :image-url="candidate.imageUrl"
            :is-selected="selectedCandidate === candidate.id"
            @click="selectCandidate(candidate.id)"
        />
    </div>
</template>
