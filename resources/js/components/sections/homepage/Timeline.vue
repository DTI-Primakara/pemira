<script lang="ts" setup>
import TimelineItem from './TimelineItem.vue';
const timelineDatas = [
    {
        order: 1,
        title: 'Pendaftaran Kandidat',
        dateStart: '2025-11-01T08:00:00Z',
        dateEnd: '2025-11-15T23:59:59Z',
    },
    {
        order: 2,
        title: 'Kampanye Kandidat',
        dateStart: '2025-12-11T00:00:00Z',
        dateEnd: null,
    },
    {
        order: 3,
        title: 'Debat Presma & Wapresma',
        dateStart: '2025-12-12T08:00:00Z',
        dateEnd: null,
    },
    {
        order: 4,
        title: 'Voting Kandidat',
        dateStart: '2025-12-14T16:00:00Z',
        dateEnd: '2025-12-16T15:59:59Z',
    },
    {
        order: 5,
        title: 'Laporan Pertanggung Jawaban',
        dateStart: '2025-12-22T08:00:00Z',
        dateEnd: null,
    },
    {
        order: 6,
        title: 'Pelantikan Kandidat Terpilih',
        dateStart: '2026-01-10T08:00:00Z',
        dateEnd: null,
    },
    {
        order: 7,
        title: 'Kongres',
        dateStart: '2026-01-17T08:00:00Z',
        dateEnd: null,
    },
];
</script>

<template>
    <section class="relative mb-16 flex w-full items-center justify-center px-6 md:mb-30 md:px-10">
        <div class="flex w-full max-w-6xl flex-col items-center justify-center gap-4 md:gap-10">
            <!-- HEAD CONTENT -->
            <h2
                class="relative w-full max-w-lg text-center font-unbounded text-2xl leading-[1.5em] font-bold text-black sm:text-3xl md:text-4xl lg:text-5xl"
            >
                Our Event’s
                <span class="text-blue">Timeline</span>
                <img src="/images/snow-pink.svg" alt="snow pink illustration" class="rotate-counter-clock absolute -top-10 right-0 -rotate-12" />
            </h2>
            <!-- TIMELINEBOX -->
            <div class="flex w-full max-w-xl flex-col items-start justify-start">
                <!-- ITEM TIMELINE -->
                <TimelineItem
                    v-for="data in timelineDatas"
                    :key="data.order"
                    :order="data.order"
                    :title="data.title"
                    :date-start="new Date(data.dateStart)"
                    :date-end="data.dateEnd ? new Date(data.dateEnd) : null"
                />
            </div>
        </div>
    </section>
</template>
