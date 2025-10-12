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
    name_candidates: string;
    position: string;
    image: string;
    vision: string;
    mission: string;
}

interface ElectionStep {
    id: number;
    title: string;
    type: string;
    subtitle: string;
    candidates: Candidate[];
}

interface Selections {
    [key: string]: number;
}

const props = defineProps({
    events: Array,
});

const electionSteps = props.events as ElectionStep[];

const currentStepIndex = ref<number>(0);
const selections = ref<Selections>({});

const currentStepData = computed<ElectionStep>(() => electionSteps[currentStepIndex.value]);
const isLastStep = computed<boolean>(() => currentStepIndex.value === electionSteps.length - 1);

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
    const votesPayload = electionSteps.map((step) => ({
        event_id: step.id,
        candidate_id: selections.value[step.title],
    }));

    router.post(
        '/vote/submit',
        { votes: votesPayload },
        {
            onSuccess: () => {
                // alert('Terima kasih sudah voting!');
                router.get(dashboard().url);
            },
            onError: (errors) => {
                console.error(errors);
                alert('Gagal mengirim vote, coba lagi.');
            },
        },
    );
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
                <h3 class="font-unbounded text-xl font-extrabold text-slate-800 sm:text-4xl">{{ currentStepData.type }}</h3>
                <p class="mt-1 text-gray-500">{{ currentStepData.title }}</p>
            </div>
        </header>

        <VotingStep
            :key="currentStepData.id"
            :candidates="currentStepData.candidates"
            :step-title="currentStepData.title"
            :selected-candidate="selections[currentStepData.title]"
            @vote="handleVote"
        />

        <footer class="mt-12 flex justify-end pb-20">
            <Button @click="nextStep" size="lg" font="unbounded" class="rounded-none">
                {{ isLastStep ? 'FINISH VOTE' : 'PEMILIHAN SELANJUTNYA' }}
            </Button>
        </footer>
    </VoteLayout>
</template>
