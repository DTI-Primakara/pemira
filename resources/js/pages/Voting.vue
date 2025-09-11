<script setup lang="ts">
import { ref, computed } from 'vue';
import VotingStep from '@/components/sections/vote/VotingStep.vue';
import VoteLayout from '@/layouts/VoteLayout.vue';
import { Button } from '@/components/ui/button';
import { Head, router } from '@inertiajs/vue3';
import {dashboard} from '@/routes';

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
            { id: 102, number: '02', name: 'MEDDY', position: 'Calon Ketua DPM', imageUrl: '/images/paslon2.png' }
        ]
    },
    {
        id: 2,
        title: 'BEM',
        subtitle: 'Badan Eksekutif Mahasiswa',
        candidates: [
            { id: 201, number: '01', name: 'CANDRA', position: 'Calon Ketua BEM', imageUrl: '/images/paslon2.png' },
            { id: 202, number: '02', name: 'DINA', position: 'Calon Ketua BEM', imageUrl: '/images/paslon4.png' }
        ]
    },
    {
        id: 3,
        title: 'HIMA IF',
        subtitle: 'Himpunan Mahasiswa Informatika',
        candidates: [
            { id: 301, number: '01', name: 'ERIK', position: 'Calon Ketua HIMA IF', imageUrl: '/images/paslon5.png' },
            { id: 302, number: '02', name: 'FARA', position: 'Calon Ketua HIMA IF', imageUrl: '/images/paslon5.png' },
        ]
    }
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
    <Head title="Voting"/>
    <VoteLayout>


        <header class="flex justify-between flex-col md:flex-row items-start mb-8">
            <div>
                <p class="text-primary mt-4 ">Tahapan {{ currentStepData.id }} / <span class="text-gray-300">{{ electionSteps.length }} </span></p>
                <h2 class="text-4xl sm:text-5xl font-extrabold text-slate-800 dark:text-white mt-1 font-unbounded tracking-tight">
                    Pilih Kandidatmu <br />
                </h2>
            </div>
            <div class="text-left md:text-right mt-12">
                <h3 class="text-xl sm:text-4xl font-extrabold text-slate-800 dark:text-white font-unbounded">{{ currentStepData.title }}</h3>
                <p class="text-gray-500 mt-1">{{ currentStepData.subtitle }}</p>
            </div>
        </header>

        <VotingStep
            :key="currentStepData.id"
            :candidates="currentStepData.candidates"
            :step-title="currentStepData.title"
            :selected-candidate="selections[currentStepData.title]"
            @vote="handleVote"
        />

        <footer class="flex justify-end mt-12">
            <Button @click="nextStep" size="lg" font="unbounded" class="rounded-none">
                {{ isLastStep ? 'FINISH VOTE' : 'PEMILIHAN SELANJUTNYA' }}
            </Button>
        </footer>
    </VoteLayout>
</template>

