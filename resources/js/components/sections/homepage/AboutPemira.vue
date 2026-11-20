<script setup lang="ts">
import LoginModal from '@/components/sections/homepage/LoginModal.vue';
import type { ElectionShowcase } from '@/types';
import { computed } from 'vue';

const props = withDefaults(defineProps<{ elections?: ElectionShowcase[] }>(), { elections: () => [] });

const electionCount = computed(() => props.elections.length);
const candidateCount = computed(() => props.elections.reduce((total, election) => total + election.candidates.length, 0));

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
    <div class="relative grid w-full grid-cols-1 items-start gap-12 md:grid-cols-2 md:gap-14 lg:gap-20">
        <!-- LEFT: apa dan kenapa -->
        <div class="relative flex flex-col items-start gap-5">
            <span class="rounded-full border border-[#E3E3FF] bg-[#F3F3FF] px-4 py-1.5 font-unbounded text-[10px] text-[#2A29D6] sm:text-xs">
                Apa itu PEMIRA?
            </span>

            <h3 class="relative text-gradient-main-26 font-unbounded text-2xl leading-snug font-bold sm:text-3xl md:text-[34px] md:leading-[46px]">
                Pesta Demokrasi
                <span class="text-gradient-secondary-26">Mahasiswa Primakara</span>
            </h3>

            <p class="font-dmsans text-sm leading-relaxed text-black opacity-80 sm:text-base">
                PEMIRA atau Pemilu Raya Mahasiswa adalah pemilihan umum tingkat kampus yang diselenggarakan DPM Primakara University untuk memilih
                pemimpin organisasi mahasiswa pada periode kepengurusan berikutnya.
            </p>
            <p class="font-dmsans text-sm leading-relaxed text-black opacity-80 sm:text-base">
                Lewat PEMIRA, setiap mahasiswa memegang hak yang sama untuk menentukan siapa yang memimpin BEM, DPM, dan HIMA di program studinya
                masing-masing — cukup lewat ponsel, tanpa perlu antre di bilik suara.
            </p>

            <LoginModal>
                <template #trigger>
                    <button
                        class="mt-3 flex cursor-pointer items-center justify-center gap-2 bg-gradient-main-26 px-4 py-2.5 font-unbounded text-xs text-white transition-opacity hover:opacity-90 sm:px-5 sm:py-3 md:text-sm"
                    >
                        Gunakan Hak Suaramu <img src="/images/arrow-white.svg" alt="" class="w-3" />
                    </button>
                </template>
            </LoginModal>

            <img
                src="/images/asset26-3.svg"
                alt=""
                class="rotate-counter-clock pointer-events-none absolute -top-10 -right-4 hidden w-12 md:block xl:w-16"
                aria-hidden="true"
            />
        </div>

        <!-- RIGHT: alur dan angka -->
        <div class="relative w-full">
            <div class="relative z-10 flex w-full flex-col gap-6 overflow-hidden rounded-3xl bg-gradient-main-26 p-6 pb-16 sm:p-8 sm:pb-20 md:p-9 md:pb-20">
                <img
                    src="/images/asset26-2.svg"
                    alt=""
                    class="pointer-events-none absolute -top-6 -right-6 w-24 opacity-25 sm:w-28"
                    aria-hidden="true"
                />

                <div class="relative flex flex-col gap-1">
                    <p class="font-unbounded text-base font-bold text-white sm:text-xl">Bagaimana cara memilih?</p>
                    <p class="font-dmsans text-xs leading-relaxed text-white opacity-70 sm:text-sm">
                        Tiga langkah, selesai dalam hitungan menit.
                    </p>
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
                    <p class="text-gradient-main-26 font-unbounded text-xl font-bold sm:text-3xl">{{ electionCount }}</p>
                    <p class="font-dmsans text-[10px] leading-tight text-black opacity-60 sm:text-xs">Pemilihan</p>
                </div>
                <div class="flex flex-col items-center gap-1 border-x border-[#F0F0F8] text-center">
                    <p class="text-gradient-main-26 font-unbounded text-xl font-bold sm:text-3xl">{{ candidateCount }}</p>
                    <p class="font-dmsans text-[10px] leading-tight text-black opacity-60 sm:text-xs">Pasangan calon</p>
                </div>
                <div class="flex flex-col items-center gap-1 text-center">
                    <p class="text-gradient-main-26 font-unbounded text-xl font-bold sm:text-3xl">1</p>
                    <p class="font-dmsans text-[10px] leading-tight text-black opacity-60 sm:text-xs">Suara per mahasiswa</p>
                </div>
            </div>
        </div>
    </div>
</template>
