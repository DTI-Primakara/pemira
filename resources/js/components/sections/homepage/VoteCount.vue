<script lang="ts" setup>
import { AppPageProps, CandidateProps } from '@/types';
import { usePage } from '@inertiajs/vue3';
import VoteCountBox from './VoteCountBox.vue';

const { props } = usePage<CandidateProps & AppPageProps>();
console.log(props);
</script>

<template>
    <div class="flex w-full flex-col items-center justify-center gap-10 sm:gap-14 md:gap-16">
        <!-- EVENT DPM BEM CONTAINER -->
        <div class="flex w-full flex-row flex-wrap items-end justify-center gap-8">
            <VoteCountBox title="Dewan Perwakilan Mahasiswa" :items="props.datas.dpm" />
            <VoteCountBox title="Badan Eksekutif Mahasiswa" :items="props.datas.bem" />
        </div>

        <div class="itemc mt-8 flex flex-col justify-center gap-0">
            <h2 class="max-w-xl text-center font-unbounded text-2xl font-semibold text-black md:text-3xl lg:text-4xl">
                Fakultas <span class="text-blue">Teknologi Informasi</span> dan <span class="text-blue">Desain</span>
            </h2>
            <p class="mt-4 w-full max-w-[764px] text-center font-dmsans text-xs leading-relaxed text-black opacity-80 sm:text-base">
                Tentukan pilihannmu untuk FTID lebih baik!
            </p>
        </div>

        <!-- EVENT FTID CONTAINER -->
        <div class="flex w-full flex-row flex-wrap items-end justify-center gap-8">
            <VoteCountBox title="Hima Sistem Informasi" :items="props.datas.hima_si" />
            <VoteCountBox title="HIMA Informatika" :items="props.datas.hima_if" />
            <VoteCountBox title="HIMA Sistem Informasi Akuntansi" :items="props.datas.hima_sia" />
            <VoteCountBox title="HIMA Desain Komunikasi Visual" :items="props.datas.hima_dkv" />
        </div>

        <div class="itemc mt-8 flex flex-col justify-center gap-0">
            <h2 class="max-w-xl text-center font-unbounded text-2xl font-semibold text-black md:text-3xl lg:text-4xl">
                Fakultas <span class="text-blue">Ekonomi</span> dan <span class="text-blue">Bisnis</span>
            </h2>
            <p class="mt-4 w-full max-w-[764px] text-center font-dmsans text-xs leading-relaxed text-black opacity-80 sm:text-base">
                Tentukan pilihannmu untuk FEB lebih baik!
            </p>
        </div>

        <!-- EVENT FEB -->
        <div class="flex w-full flex-row flex-wrap items-end justify-center gap-8">
            <VoteCountBox title="Hima Manajemen" :items="props.datas.hima_manajemen" />
            <VoteCountBox title="HIMA Akuntansi" :items="props.datas.hima_akuntansi" />
            <VoteCountBox title="HIMA Bisnis Digital" :items="props.datas.hima_bd" />
        </div>
    </div>
</template>
