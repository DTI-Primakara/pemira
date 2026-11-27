<script lang="ts" setup>
import type { TallyGroup } from '@/types';
import VoteCountBox from './VoteCountBox.vue';

defineProps<{
    groups: TallyGroup[];
}>();
</script>

<template>
    <div class="flex w-full flex-col items-center justify-center gap-10 sm:gap-14 md:gap-16">
        <template v-for="group in groups" :key="group.key">
            <div v-if="group.title" class="mt-8 flex flex-col items-center justify-center gap-0">
                <h2 class="max-w-xl text-center font-unbounded text-2xl font-semibold text-black md:text-3xl lg:text-4xl">
                    {{ group.title }}
                </h2>
                <p v-if="group.subtitle" class="mt-4 w-full max-w-[764px] text-center font-dmsans text-xs leading-relaxed text-black opacity-80 sm:text-base">
                    {{ group.subtitle }}
                </p>
            </div>

            <div class="flex w-full flex-row flex-wrap items-end justify-center gap-8">
                <VoteCountBox v-for="event in group.events" :key="event.id" :title="event.title" :items="event.candidates" />
            </div>
        </template>
    </div>
</template>
