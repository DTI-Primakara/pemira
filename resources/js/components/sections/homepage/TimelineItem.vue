<script lang="ts" setup>
import { computed } from 'vue';

interface Props {
    order: number;
    title: string;
    dateStart: Date;
    dateEnd: Date | null;
}

const props = defineProps<Props>();

const status = computed(() => {
    const now = new Date();

    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    const start = new Date(props.dateStart.getFullYear(), props.dateStart.getMonth(), props.dateStart.getDate());

    if (!props.dateEnd) {
        if (today.getTime() === start.getTime()) {
            return 'ongoing';
        }

        if (today.getTime() > start.getTime()) {
            return 'done';
        }

        return 'upcoming';
    }

    const end = new Date(props.dateEnd.getFullYear(), props.dateEnd.getMonth(), props.dateEnd.getDate());

    if (today >= start && today <= end) {
        return 'ongoing';
    }

    if (today > end) {
        return 'done';
    }

    return 'upcoming';
});
</script>

<template>
    <div class="flex w-full flex-row items-center justify-start gap-6">
        <!-- POINT PART -->
        <div class="relative flex items-center justify-center py-6">
            <!-- POINT MAIN -->
            <div
                :class="[
                    'relative z-2 flex h-8 w-8 items-center justify-center rounded-full sm:h-10 sm:w-10 md:h-12 md:w-12',
                    status === 'done' ? 'bg-[#CECECE]' : 'bg-blue',
                ]"
            >
                <div v-if="status === 'ongoing'" class="h-4 w-4 rounded-full bg-[#0A41D4] sm:h-5 sm:w-5 md:h-6 md:w-6"></div>
            </div>
            <!-- POINT STRIP -->
            <div :class="['absolute bottom-0 z-1 w-3 bg-[#F0F0F0]', order == 1 ? 'h-1/2' : 'h-full']"></div>
        </div>

        <!-- DETAIL PART -->
        <div :class="['flex w-full flex-col items-start justify-start gap-1', status === 'done' ? 'opacity-80' : '']">
            <h3
                :class="[
                    'relative font-unbounded text-lg leading-[1.5em] font-bold sm:text-xl md:text-2xl',
                    status == 'done' ? 'text-[#8B8B8B]' : 'text-black',
                ]"
            >
                {{ props.title }}
            </h3>
            <h4
                :class="[
                    'relative font-unbounded text-xs leading-[1.5em] font-bold sm:text-base md:text-sm',
                    status == 'done' ? 'text-[#D0D0D0]' : 'text-blue',
                ]"
            >
                {{ props.dateStart.toLocaleDateString('id-ID', { dateStyle: 'long' }) }}
                {{ props.dateEnd ? ' - ' + props.dateEnd.toLocaleDateString('id-ID', { dateStyle: 'long' }) : '' }}
            </h4>
        </div>
    </div>
</template>
