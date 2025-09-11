<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import GuideCard from './GuideCard.vue';
import { voting } from '@/routes';

const guideItems = ref([
    {
        title: 'Pastikan Data Diri Valid',
        desc: 'Sebelum memulai proses e-voting, pastikan data diri Anda sesuai dengan informasi yang tercatat di sistem. Data yang valid menjamin hak suara Anda dapat digunakan dengan aman.',
        iconUrl: '/icon/guide-1.svg',
        iconAlt: 'guide-1',
    },
    {
        title: '1 Mahasiswa 1 Suara',
        desc: 'Setiap mahasiswa hanya memiliki satu hak pilih. Gunakan suara Anda dengan bijak karena pilihan yang diberikan tidak dapat diubah setelah dikirim.',
        iconUrl: '/icon/guide-2.svg',
        iconAlt: 'guide-2',
    },
    {
        title: 'Memilih Ketua DPM, Presma, dan Ketua HIMA Sesuai Prodi',
        desc: 'Anda berhak memilih Ketua DPM, Presma, dan Ketua HIMA sesuai dengan program studi masing-masing. Pastikan membaca visi dan misi kandidat sebelum menentukan pilihan.',
        iconUrl: '/icon/guide-3.svg',
        iconAlt: 'guide-3',
    },
    {
        title: 'Pemilihan Rahasia dan Aman',
        desc: 'Seluruh proses voting dilakukan secara terenkripsi dan kerahasiaan suara dijamin. Tidak ada pihak lain yang dapat mengetahui pilihan Anda.',
        iconUrl: '/icon/guide-4.svg',
        iconAlt: 'guide-4',
    },
    {
        title: 'Suaramu, Masa Depanmu',
        desc: 'Setiap suara mahasiswa menentukan arah kepemimpinan kampus ke depan. Gunakan kesempatan ini untuk berkontribusi dalam membangun masa depan yang lebih baik.',
        iconUrl: '/icon/guide-5.svg',
        iconAlt: 'guide-5',
    },
]);
</script>

<template>
    <div class="mx-auto max-w-3xl">
        <div class="text-center">
            <h1 class="font-unbounded text-3xl font-bold text-slate-900 md:text-5xl dark:text-white">
                MINI TOUR <span class="text-primary">GUIDE</span>
            </h1>
            <p class="mt-3 text-sm text-slate-600 md:text-base dark:text-slate-400">
                Berikut merupakan panduan tata cara melakukan e-voting pada PEMIRA 2025
            </p>
        </div>

        <div class="mt-10 space-y-4">
            <GuideCard
                v-for="(item, index) in guideItems"
                :key="index"
                :title="item.title"
                :desc="item.desc"
                variant="default"
                :icon-url="item.iconUrl"
                :icon-alt="item.iconAlt"
            />
        </div>

        <div class="mt-12 mb-12 flex justify-center text-center md:justify-end">
            <Link :href="voting().url">
                <Button font="unbounded" class="uppercase">Mulai sekarang</Button>
            </Link>
        </div>
    </div>
</template>
