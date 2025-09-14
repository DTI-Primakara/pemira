<script setup lang="ts">
import VotingStep from '@/components/sections/vote/VotingStep.vue';
import { Button } from '@/components/ui/button';
import VoteLayout from '@/layouts/VoteLayout.vue';
import { dashboard } from '@/routes';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface Candidate {
    id: number;
    number: string;
    name: string;
    position: string;
    imageUrl: string;
}

interface ElectionStep {
    id: number;
    title: string;
    subtitle: string;
    candidates: Candidate[];
}

interface Selections {
    [key: string]: number;
}

const electionSteps = ref<ElectionStep[]>([
    {
        id: 1,
        title: 'DPM',
        subtitle: 'Dewan Perwakilan Mahasiswa',
        candidates: [
            { id: 101, number: '01', name: 'ARYA', position: 'Calon Ketua DPM', imageUrl: '/images/paslon3.png' },
            { id: 102, number: '02', name: 'MEDDY', position: 'Calon Ketua DPM', imageUrl: '/images/paslon2.png' },
        ],
    },
    {
        id: 2,
        title: 'BEM',
        subtitle: 'Badan Eksekutif Mahasiswa',
        candidates: [
            { id: 201, number: '01', name: 'CANDRA', position: 'Calon Ketua BEM', imageUrl: '/images/paslon2.png' },
            { id: 202, number: '02', name: 'DINA', position: 'Calon Ketua BEM', imageUrl: '/images/paslon4.png' },
        ],
    },
    {
        id: 3,
        title: 'HIMA IF',
        subtitle: 'Himpunan Mahasiswa Informatika',
        candidates: [
            { id: 301, number: '01', name: 'ERIK', position: 'Calon Ketua HIMA IF', imageUrl: '/images/paslon5.png' },
            { id: 302, number: '02', name: 'FARA', position: 'Calon Ketua HIMA IF', imageUrl: '/images/paslon5.png' },
        ],
    },
]);

const currentStepIndex = ref<number>(0);
const selections = ref<Selections>({});

const currentStepData = computed<ElectionStep>(() => electionSteps.value[currentStepIndex.value]);
const isLastStep = computed<boolean>(() => currentStepIndex.value === electionSteps.value.length - 1);

const nextStep = (): void => {
    if (!isLastStep.value) {
        currentStepIndex.value++;
    } else {
        finishVote();
    }
};

// nyimpen kandidat
const handleVote = (candidateId: number): void => {
    const stepTitle = currentStepData.value.title;
    selections.value[stepTitle] = candidateId;
    console.log('Current selections:', selections.value);
};

//  menyelesaikan voting
const finishVote = (): void => {
    // nnti send data disini
    router.get(dashboard().url);
    alert('Terima kasih telah memberikan suara! Pilihan Anda: ' + JSON.stringify(selections.value));
};
</script>

<template>
    <Head title="Voting" />
    <VoteLayout>
        <header class="mb-8 flex flex-col items-start justify-between md:flex-row">
            <div>
                <p class="mt-4 text-primary">
                    Tahapan {{ currentStepData.id }} / <span class="text-gray-300">{{ electionSteps.length }} </span>
                </p>
                <h2 class="mt-1 font-unbounded text-4xl font-extrabold tracking-tight text-slate-800 sm:text-5xl">Pilih Kandidatmu <br /></h2>
            </div>
            <div class="mt-12 text-left md:text-right">
                <h3 class="font-unbounded text-xl font-extrabold text-slate-800 sm:text-4xl">{{ currentStepData.title }}</h3>
                <p class="mt-1 text-gray-500">{{ currentStepData.subtitle }}</p>
            </div>
        </header>

        <VotingStep
            :key="currentStepData.id"
            :candidates="currentStepData.candidates"
            :step-title="currentStepData.title"
            :selected-candidate="selections[currentStepData.title]"
            @vote="handleVote"
        />

        <footer class="mt-12 flex justify-end">
            <Button @click="nextStep" size="lg" font="unbounded" class="rounded-none">
                {{ isLastStep ? 'FINISH VOTE' : 'PEMILIHAN SELANJUTNYA' }}
            </Button>
        </footer>
    </VoteLayout>
</template>
