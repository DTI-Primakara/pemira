<script lang="ts" setup>
import { defineProps } from 'vue';

interface Props {
    order: number;
    title: string;
    dateStart: Date;
    dateEnd: Date;
}

const props = defineProps<Props>();
let status = 'upcoming'; // upcoming, ongoing, done

if (props.dateStart <= new Date() && props.dateEnd >= new Date()) {
    status = 'ongoing';
} else if (props.dateEnd < new Date()) {
    status = 'done';
}
</script>

<template>
    <div class="flex w-full flex-row items-center justify-start gap-6">
        <!-- POINT PART -->
        <div class="relative flex items-center justify-center py-6">
            <!-- POINT MAIN -->
            <div :class="['relative z-2 flex h-12 w-12 items-center justify-center rounded-full', status === 'done' ? 'bg-[#CECECE]' : 'bg-blue']">
                <div v-if="status === 'ongoing'" class="h-6 w-6 rounded-full bg-[#0A41D4]"></div>
            </div>
            <!-- POINT STRIP -->
            <div :class="['absolute bottom-0 z-1 w-3 bg-[#F0F0F0]', order == 1 ? 'h-1/2' : 'h-full']"></div>
        </div>

        <!-- DETAIL PART -->
        <div :class="['flex w-full flex-col items-start justify-start gap-1', status === 'done' ? 'opacity-80' : '']">
            <h3 :class="['relative font-unbounded text-2xl leading-[1.5em] font-bold', status == 'done' ? 'text-[#8B8B8B]' : 'text-black']">
                {{ props.title }}
            </h3>
            <h4 :class="['text-md relative font-unbounded leading-[1.5em] font-bold', status == 'done' ? 'text-[#D0D0D0]' : 'text-blue']">
                {{ props.dateStart.toLocaleDateString('id-ID', { dateStyle: 'long' }) }} -
                {{ props.dateEnd.toLocaleDateString('id-ID', { dateStyle: 'long' }) }}
            </h4>
        </div>
    </div>
</template>
