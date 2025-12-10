<script lang="ts" setup>
import TimelineItem from './TimelineItem.vue';
const timelineDatas = [
    {
        order: 1,
        title: 'Pendaftaran',
        dateStart: '2025-11-01T08:00:00Z',
        dateEnd: '2025-11-15T23:59:59Z',
    },
    {
        order: 2,
        title: 'Kampanye',
        dateStart: '2025-12-11T08:00:00Z',
        dateEnd: '2025-12-11T23:59:59Z',
    },
    {
        order: 3,
        title: 'Debat',
        dateStart: '2025-12-12T08:00:00Z',
        dateEnd: '2025-12-12T23:59:59Z',
    },
    {
        order: 4,
        title: 'Voting',
        dateStart: '2025-12-15T08:00:00Z',
        dateEnd: '2025-12-16T23:59:59Z',
    },
    {
        order: 5,
        title: 'LPJ',
        dateStart: '2025-12-22T08:00:00Z',
        dateEnd: '2025-12-23T23:59:59Z',
    },
    {
        order: 6,
        title: 'Pelantikan',
        dateStart: '2026-01-10T08:00:00Z',
        dateEnd: '2026-01-10T23:59:59Z',
    },
    {
        order: 7,
        title: 'Kongres',
        dateStart: '2026-01-17T08:00:00Z',
        dateEnd: '2026-01-17T23:59:59Z',
    },
];
</script>

<template>
    <section class="relative mb-16 flex w-full items-center justify-center px-6 md:mb-30 md:px-10">
        <div class="flex w-full max-w-3xl flex-col items-center justify-center gap-4 md:gap-10">
            <!-- HEAD CONTENT -->
            <h2
                class="relative w-full max-w-lg text-center font-unbounded text-2xl leading-[1.5em] font-bold text-black sm:text-3xl md:text-4xl lg:text-5xl"
            >
                Our Event’s
                <span class="text-blue">Timeline</span>
                <img src="/images/snow-pink.svg" alt="snow pink illustration" class="rotate-counter-clock absolute -top-10 right-0 -rotate-12" />
            </h2>
            <!-- TIMELINEBOX -->
            <div class="flex w-full max-w-lg flex-col items-start justify-start">
                <!-- ITEM TIMELINE -->
                <TimelineItem
                    v-for="data in timelineDatas"
                    :key="data.order"
                    :order="data.order"
                    :title="data.title"
                    :date-start="new Date(data.dateStart)"
                    :date-end="new Date(data.dateEnd)"
                />
            </div>
        </div>
    </section>
</template>
