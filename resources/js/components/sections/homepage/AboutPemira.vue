<script setup lang="ts">
import type { ElectionShowcase } from '@/types';
import { computed } from 'vue';

const props = withDefaults(defineProps<{ elections?: ElectionShowcase[] }>(), { elections: () => [] });

const electionCount = computed(() => props.elections.length);
const candidateCount = computed(() => props.elections.reduce((total, election) => total + election.candidates.length, 0));

const organizations = [
    {
        title: 'Pemilihan BEM',
        description: 'Badan Eksekutif Mahasiswa, pelaksana program kemahasiswaan di tingkat universitas.',
    },
    {
        title: 'Pemilihan DPM',
        description: 'Dewan Perwakilan Mahasiswa, pengawas jalannya organisasi sekaligus penyelenggara PEMIRA.',
    },
    {
        title: 'Pemilihan HIMA',
        description: 'Himpunan Mahasiswa di setiap program studi, dipilih khusus oleh mahasiswa prodi tersebut.',
    },
];

const steps = [
    {
        title: 'Masuk dengan akun kampus',
        description: 'Cukup login memakai akun Google Primakara. Sistem otomatis mengenali prodi dan hak suaramu.',
    },
    {
        title: 'Pilih di setiap pemilihan',
        description: 'Mulai dari BEM, DPM, sampai HIMA program studimu sendiri — semuanya dalam satu lembar suara.',
    },
    {
        title: 'Suara terkunci dan rahasia',
        description: 'Setelah dikirim, suara tidak bisa diubah dan tidak dapat ditelusuri kembali ke pemilihnya.',
    },
];
</script>

<template>
    <div class="relative grid w-full grid-cols-1 items-stretch gap-12 md:grid-cols-2 md:gap-14 lg:gap-20">
        <!-- LEFT: apa yang dipilih -->
        <div data-anim="left" class="relative flex w-full flex-col">
            <div
                class="relative z-10 flex w-full flex-1 flex-col gap-6 rounded-3xl border border-[#C9C9D4]/50 bg-white p-6 pb-16 shadow-[0_24px_50px_-34px_rgba(1,0,120,0.35)] sm:p-8 sm:pb-20 md:p-9 md:pb-20"
            >
                <div class="flex flex-col gap-1">
                    <h3 class="text-gradient-main-26 font-unbounded text-base font-bold sm:text-xl">
                        Pesta Demokrasi
                        <span class="text-gradient-secondary-26">Mahasiswa Primakara</span>
                    </h3>
                    <p class="font-dmsans text-xs leading-relaxed text-black opacity-60 sm:text-sm">
                        Pemilihan umum tingkat kampus yang digelar DPM Primakara University setiap tahun.
                    </p>
                </div>

                <div class="flex flex-col gap-5">
                    <div v-for="(organization, index) in organizations" :key="organization.title" class="flex items-start gap-4">
                        <span
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#F1F1FF] font-unbounded text-xs font-bold text-[#2A29D6] sm:text-sm"
                        >
                            {{ String(index + 1).padStart(2, '0') }}
                        </span>
                        <div class="flex flex-col gap-1">
                            <p class="font-unbounded text-xs font-bold text-[#11114E] sm:text-sm">{{ organization.title }}</p>
                            <p class="font-dmsans text-xs leading-relaxed text-black opacity-60 sm:text-sm">{{ organization.description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Strip angka biru, menumpuk di bawah kartu putih -->
            <div
                class="relative z-20 mx-4 -mt-8 grid grid-cols-3 gap-2 rounded-2xl bg-gradient-main-26 p-4 shadow-[0_24px_50px_-28px_rgba(1,0,120,0.55)] sm:mx-6 sm:gap-4 sm:p-5"
            >
                <div class="flex flex-col items-center gap-1 text-center">
                    <p data-count="3" class="font-unbounded text-xl font-bold text-white sm:text-3xl">3</p>
                    <p class="font-dmsans text-[10px] leading-tight text-white opacity-70 sm:text-xs">Jenis ORMAWA</p>
                </div>
                <div class="flex flex-col items-center gap-1 border-x border-white/20 text-center">
                    <p data-count="100" data-count-suffix="%" class="font-unbounded text-xl font-bold text-white sm:text-3xl">100%</p>
                    <p class="font-dmsans text-[10px] leading-tight text-white opacity-70 sm:text-xs">Online</p>
                </div>
                <div class="flex flex-col items-center gap-1 text-center">
                    <p data-count="1" data-count-suffix="x" class="font-unbounded text-xl font-bold text-white sm:text-3xl">1x</p>
                    <p class="font-dmsans text-[10px] leading-tight text-white opacity-70 sm:text-xs">Setiap tahun</p>
                </div>
            </div>
        </div>

        <!-- RIGHT: alur dan angka -->
        <div data-anim="right" data-anim-delay="140" class="relative flex w-full flex-col">
            <div
                class="relative z-10 flex w-full flex-1 flex-col gap-6 overflow-hidden rounded-3xl bg-gradient-main-26 p-6 pb-16 sm:p-8 sm:pb-20 md:p-9 md:pb-20"
            >
                <div class="relative flex flex-col gap-1">
                    <p class="font-unbounded text-base font-bold text-white sm:text-xl">Bagaimana cara memilih?</p>
                    <p class="font-dmsans text-xs leading-relaxed text-white opacity-70 sm:text-sm">Tiga langkah, selesai dalam hitungan menit.</p>
                </div>

                <div class="relative flex flex-col gap-5">
                    <div v-for="(step, index) in steps" :key="step.title" class="flex items-start gap-4">
                        <span
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white/15 font-unbounded text-xs font-bold text-white sm:text-sm"
                        >
                            {{ String(index + 1).padStart(2, '0') }}
                        </span>
                        <div class="flex flex-col gap-1">
                            <p class="font-unbounded text-xs font-bold text-white sm:text-sm">{{ step.title }}</p>
                            <p class="font-dmsans text-xs leading-relaxed text-white opacity-70 sm:text-sm">{{ step.description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu angka, menumpuk di bawah kartu alur -->
            <div
                class="relative z-20 mx-4 -mt-8 grid grid-cols-3 gap-2 rounded-2xl border border-[#ECECFF] bg-white p-4 shadow-[0_24px_50px_-28px_rgba(1,0,120,0.45)] sm:mx-6 sm:gap-4 sm:p-5"
            >
                <div class="flex flex-col items-center gap-1 text-center">
                    <p :data-count="electionCount" class="text-gradient-main-26 font-unbounded text-xl font-bold sm:text-3xl">{{ electionCount }}</p>
                    <p class="font-dmsans text-[10px] leading-tight text-black opacity-60 sm:text-xs">Pemilihan</p>
                </div>
                <div class="flex flex-col items-center gap-1 border-x border-[#F0F0F8] text-center">
                    <p :data-count="candidateCount" class="text-gradient-main-26 font-unbounded text-xl font-bold sm:text-3xl">{{ candidateCount }}</p>
                    <p class="font-dmsans text-[10px] leading-tight text-black opacity-60 sm:text-xs">Pasangan calon</p>
                </div>
                <div class="flex flex-col items-center gap-1 text-center">
                    <p data-count="1" class="text-gradient-main-26 font-unbounded text-xl font-bold sm:text-3xl">1</p>
                    <p class="font-dmsans text-[10px] leading-tight text-black opacity-60 sm:text-xs">Suara per mahasiswa</p>
                </div>
            </div>
        </div>
    </div>
</template>
