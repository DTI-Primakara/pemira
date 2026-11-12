<script setup lang="ts">
import VotingStep from '@/components/sections/vote/VotingStep.vue';
import { Button } from '@/components/ui/button';
import VoteLayout from '@/layouts/VoteLayout.vue';
import { dashboard } from '@/routes';
import type { BallotEvent, Candidate } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps<{
    events: BallotEvent[];
}>();

const currentStepIndex = ref(0);
const submitting = ref(false);

/** candidate id chosen per event id */
const selections = ref<Record<number, number>>({});

const currentStepData = computed<BallotEvent | undefined>(() => props.events[currentStepIndex.value]);
const isLastStep = computed(() => currentStepIndex.value === props.events.length - 1);

const selectedCandidateId = computed<number | undefined>(() =>
    currentStepData.value ? selections.value[currentStepData.value.id] : undefined,
);

const isCandidateSelected = computed(() => selectedCandidateId.value !== undefined);

const selectedCandidate = computed<Candidate | undefined>(() =>
    currentStepData.value?.candidates.find((candidate) => candidate.id === selectedCandidateId.value),
);

const handleVote = (candidateId: number): void => {
    if (!currentStepData.value) return;
    selections.value[currentStepData.value.id] = candidateId;
};

const nextStep = (): void => {
    if (!isLastStep.value) {
        currentStepIndex.value++;
        return;
    }
    finishVote();
};

const finishVote = (): void => {
    if (submitting.value) return;
    submitting.value = true;

    const votesPayload = props.events.map((event) => ({
        event_id: event.id,
        candidate_id: selections.value[event.id],
    }));

    router.post(
        '/vote/submit',
        { votes: votesPayload },
        {
            onError: (errors) => {
                alert(Object.values(errors)[0] ?? 'Gagal mengirim suara, coba lagi.');
            },
            onFinish: () => {
                submitting.value = false;
            },
        },
    );
};
</script>

<template>
    <Head title="Voting" />
    <VoteLayout>
        <div v-if="!currentStepData" class="py-20 text-center">
            <h2 class="font-unbounded text-2xl font-extrabold text-slate-800">Belum ada pemilihan yang tersedia</h2>
            <p class="mt-2 text-gray-500">Belum ada kandidat yang terdaftar untuk hak suaramu saat ini.</p>
            <Button class="mt-6 rounded-none" font="unbounded" @click="router.get(dashboard().url)">KEMBALI KE DASHBOARD</Button>
        </div>

        <template v-else>
            <header class="mb-8 flex flex-col items-start justify-between md:flex-row">
                <div>
                    <p class="mt-4 text-primary">
                        Tahapan {{ currentStepIndex + 1 }} / <span class="text-gray-300">{{ props.events.length }} </span>
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
                :selected-candidate="selectedCandidateId"
                @vote="handleVote"
            />

            <footer class="mt-12 flex flex-col items-end justify-between gap-5 pb-20 md:flex-row md:items-center">
                <p v-if="selectedCandidate" class="font-unbounded text-lg font-semibold">
                    No Undi Terpilih : {{ String(selectedCandidate.number).padStart(2, '0') }}
                </p>
                <p v-else></p>
                <Button
                    @click="nextStep"
                    :disabled="!isCandidateSelected || submitting"
                    size="lg"
                    font="unbounded"
                    class="rounded-none"
                >
                    {{ isLastStep ? (submitting ? 'MENGIRIM...' : 'FINISH VOTE') : 'PEMILIHAN SELANJUTNYA' }}
                </Button>
            </footer>
        </template>
    </VoteLayout>
</template>
