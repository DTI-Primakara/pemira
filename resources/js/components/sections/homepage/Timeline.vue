<script lang="ts" setup>
import TimelineItem from './TimelineItem.vue';
const timelineDatas = [
    {
        order: 1,
        title: 'Sosialisasi Pemilihan',
        dateStart: '2025-09-20T08:00:00Z',
        dateEnd: '2025-09-21T17:00:00Z',
    },
    {
        order: 2,
        title: 'Pembukaan Pendaftaran Kandidat',
        dateStart: '2025-09-22T08:00:00Z',
        dateEnd: '2025-09-30T23:59:59Z',
    },
    {
        order: 3,
        title: 'Seleksi Administrasi Kandidat',
        dateStart: '2025-10-01T08:00:00Z',
        dateEnd: '2025-10-02T17:00:00Z',
    },
    {
        order: 4,
        title: 'Pengumuman Kandidat Lolos',
        dateStart: '2025-10-03T08:00:00Z',
        dateEnd: '2025-10-03T17:00:00Z',
    },
    {
        order: 5,
        title: 'Masa Kampanye',
        dateStart: '2025-10-04T08:00:00Z',
        dateEnd: '2025-10-10T17:00:00Z',
    },
    {
        order: 6,
        title: 'Debat Kandidat',
        dateStart: '2025-10-11T13:00:00Z',
        dateEnd: '2025-10-11T17:00:00Z',
    },
    {
        order: 7,
        title: 'Hari Pemilihan',
        dateStart: '2025-10-15T08:00:00Z',
        dateEnd: '2025-10-15T17:00:00Z',
    },
    {
        order: 8,
        title: 'Pengumuman Hasil Pemilihan',
        dateStart: '2025-10-16T10:00:00Z',
        dateEnd: '2025-10-16T12:00:00Z',
    },
    {
        order: 9,
        title: 'Pelantikan Ketua Terpilih',
        dateStart: '2025-10-20T09:00:00Z',
        dateEnd: '2025-10-20T12:00:00Z',
    },
];
</script>

<template>
    <section class="relative mb-30 flex w-full items-center justify-center">
        <div class="flex w-full max-w-3xl flex-col items-center justify-center gap-10">
            <!-- HEAD CONTENT -->
            <h2 class="relative w-full max-w-lg text-center font-unbounded text-5xl leading-[1.5em] font-bold text-black">
                Our Event’s
                <span class="text-blue">Timeline</span>
                <img src="/images/snow-pink.svg" alt="snow pink illustration" class="rotate-counter-clock absolute -top-10 right-0 -rotate-12" />
            </h2>
            <!-- TIMELINEBOX -->
            <div class="flex w-full flex-col items-start justify-start">
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
