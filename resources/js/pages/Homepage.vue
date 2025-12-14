<script setup lang="ts">
import Navbar from '@/components/core/Navbar.vue';
import ErrorModal from '@/components/sections/homepage/ErrorModal.vue';
import LoginModal from '@/components/sections/homepage/LoginModal.vue';
import Timeline from '@/components/sections/homepage/Timeline.vue';
import VoteCountContainer from '@/components/VoteCountContainer.vue';
import { initLenis } from '@/lib/lenis';
import { usePage } from '@inertiajs/vue3';
import { Instagram } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

const expanded = ref(false);

const { props } = usePage();
console.log(props);

const showModalError = ref(false);

onMounted(() => {
    initLenis();

    if (props.flash?.error) {
        showModalError.value = true;
    }
});
</script>

<template>
    <div class="flex min-h-screen w-full flex-col items-center justify-start bg-white">
        <Navbar />

        <!-- START HERO -->
        <section class="relative mb-16 flex w-full items-center justify-start overflow-hidden bg-white pt-36 md:mb-30" id="hero">
            <div class="flex w-full flex-col items-center justify-start">
                <div class="z-10 flex flex-col items-center justify-center">
                    <h1 class="text-blue relative font-unbounded text-[16vw] leading-tight font-bold md:text-[12vw] xl:text-[160px]">
                        <img
                            src="/images/star-pink.svg"
                            alt="star pink svg"
                            class="rotate-clock absolute top-[7vw] -left-[8vw] w-[12vw] rotate-24 md:-left-[5vw] md:w-[8vw] xl:w-[120px]"
                        />
                        PEMIRA
                        <img
                            src="/images/snow-orange.svg"
                            alt="snow orange svg"
                            class="rotate-counter-clock absolute top-[0vw] -right-[5vw] w-[12vw] rotate-24 md:-right-[3vw] md:w-[8vw] xl:w-[120px]"
                        />
                    </h1>
                    <h2 class="relative font-unbounded text-[9vw] leading-tight font-bold text-black md:text-[5vw] xl:text-[64px]">2025</h2>
                    <div class="w-full max-w-[764px]">
                        <p
                            ref="textRef"
                            :class="[
                                'mt-4 px-6 text-center font-dmsans text-xs leading-relaxed text-black opacity-80 sm:text-base md:px-0',
                                !expanded ? 'line-clamp-3' : '',
                            ]"
                        >
                            PEMIRA 2025 adalah saatnya mahasiswa Primakara menentukan pemimpin baru. Mari gunakan suara kita untuk membawa kampus
                            menuju masa depan yang lebih baik. Pemilu Raya Mahasiswa atau PEMIRA merupakan salah satu kegiatan yang diselenggarakan
                            oleh DPM Primakara University. Pada kegiatan PEMIRA ini mahasiswa diberikan kesempatan untuk menentukan pemimpin ORMAWA
                            baru untuk kepengurusan berikutnya. Gunakan hak suara kalian untuk menentukan pemimpin selanjutnya.
                        </p>

                        <span
                            @click="expanded = !expanded"
                            class="mt-2 block cursor-pointer text-center text-xs font-semibold text-blue-600 hover:underline sm:text-sm"
                        >
                            {{ expanded ? 'Tutup' : 'Baca selengkapnya' }}
                        </span>
                    </div>
                    <LoginModal>
                        <template #trigger>
                            <button
                                class="mt-6 flex cursor-pointer items-center justify-center gap-2 bg-[#4342FF] px-3 py-2 font-unbounded text-xs text-white hover:bg-[#1c1cad] sm:px-5 sm:py-3 md:text-sm"
                            >
                                VOTE NOW <img src="/images/arrow-white.svg" alt="arrow white icon" class="w-3" />
                            </button>
                        </template>
                    </LoginModal>

                    <ErrorModal v-model:open="showModalError" />
                </div>
                <div class="relative mt-32 flex w-full flex-col items-center justify-center">
                    <div
                        class="absolute -left-40 flex w-full rotate-32 flex-row items-center justify-center gap-8 overflow-hidden bg-[#4342FF] py-4 md:gap-12"
                    >
                        <p class="linear-move-left font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-10 md:h-14" />
                        <p class="linear-move-left font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-10 md:h-14" />
                        <p class="linear-move-left font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-10 md:h-14" />
                        <p class="linear-move-left font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-10 md:h-14" />
                        <p class="linear-move-left font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-10 md:h-14" />
                        <p class="linear-move-left font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-10 md:h-14" />
                        <p class="linear-move-left font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-10 md:h-14" />
                        <p class="linear-move-left font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-10 md:h-14" />
                        <p class="linear-move-left font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-10 md:h-14" />
                        <p class="linear-move-left font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-10 md:h-14" />
                        <p class="linear-move-left font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-10 md:h-14" />
                        <p class="linear-move-left font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-10 md:h-14" />
                    </div>
                    <div
                        class="absolute -right-40 flex w-full -rotate-32 flex-row items-center justify-center gap-8 overflow-hidden bg-[#4342FF] py-4 md:gap-12"
                    >
                        <p class="linear-move-right font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-10 md:h-14" />
                        <p class="linear-move-right font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-10 md:h-14" />
                        <p class="linear-move-right font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-10 md:h-14" />
                        <p class="linear-move-right font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-10 md:h-14" />
                        <p class="linear-move-right font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-10 md:h-14" />
                        <p class="linear-move-right font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-10 md:h-14" />
                        <p class="linear-move-right font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-10 md:h-14" />
                        <p class="linear-move-right font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-10 md:h-14" />
                        <p class="linear-move-right font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-10 md:h-14" />
                        <p class="linear-move-right font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-10 md:h-14" />
                        <p class="linear-move-right font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-10 md:h-14" />
                        <p class="linear-move-right font-unbounded text-3xl font-bold text-white md:text-5xl">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-10 md:h-14" />
                    </div>
                </div>
                <div class="relative flex w-full flex-row items-start justify-start gap-4 overflow-hidden">
                    <div class="linear-move flex w-full flex-row items-start justify-start gap-2 sm:gap-4">
                        <img
                            src="/paslon-images/bem_1_1x.webp"
                            alt=""
                            class="w-full max-w-48 min-w-48 sm:max-w-[37vw] sm:min-w-[37vw] md:max-w-[24vw] md:min-w-[24vw] xl:max-w-96 xl:min-w-96"
                        />

                        <img
                            src="/paslon-images/dpm_1_1x.webp"
                            alt=""
                            class="w-full max-w-48 min-w-48 sm:max-w-[37vw] sm:min-w-[37vw] md:max-w-[24vw] md:min-w-[24vw] xl:max-w-96 xl:min-w-96"
                        />
                        <img
                            src="/paslon-images/dpm_2_1x.webp"
                            alt=""
                            class="w-full max-w-48 min-w-48 sm:max-w-[37vw] sm:min-w-[37vw] md:max-w-[24vw] md:min-w-[24vw] xl:max-w-96 xl:min-w-96"
                        />

                        <img
                            src="/paslon-images/hima_akun_1_1x.webp"
                            alt=""
                            class="w-full max-w-48 min-w-48 sm:max-w-[37vw] sm:min-w-[37vw] md:max-w-[24vw] md:min-w-[24vw] xl:max-w-96 xl:min-w-96"
                        />
                        <img
                            src="/paslon-images/hima_akun_2_1x.webp"
                            alt=""
                            class="w-full max-w-48 min-w-48 sm:max-w-[37vw] sm:min-w-[37vw] md:max-w-[24vw] md:min-w-[24vw] xl:max-w-96 xl:min-w-96"
                        />

                        <img
                            src="/paslon-images/hima_bd_1_1x.webp"
                            alt=""
                            class="w-full max-w-48 min-w-48 sm:max-w-[37vw] sm:min-w-[37vw] md:max-w-[24vw] md:min-w-[24vw] xl:max-w-96 xl:min-w-96"
                        />
                        <img
                            src="/paslon-images/hima_bd_2_1x.webp"
                            alt=""
                            class="w-full max-w-48 min-w-48 sm:max-w-[37vw] sm:min-w-[37vw] md:max-w-[24vw] md:min-w-[24vw] xl:max-w-96 xl:min-w-96"
                        />

                        <img
                            src="/paslon-images/hima_dkv_1_1x.webp"
                            alt=""
                            class="w-full max-w-48 min-w-48 sm:max-w-[37vw] sm:min-w-[37vw] md:max-w-[24vw] md:min-w-[24vw] xl:max-w-96 xl:min-w-96"
                        />
                        <img
                            src="/paslon-images/hima_dkv_2_1x.webp"
                            alt=""
                            class="w-full max-w-48 min-w-48 sm:max-w-[37vw] sm:min-w-[37vw] md:max-w-[24vw] md:min-w-[24vw] xl:max-w-96 xl:min-w-96"
                        />

                        <img
                            src="/paslon-images/hima_if_1_1x.webp"
                            alt=""
                            class="w-full max-w-48 min-w-48 sm:max-w-[37vw] sm:min-w-[37vw] md:max-w-[24vw] md:min-w-[24vw] xl:max-w-96 xl:min-w-96"
                        />
                        <img
                            src="/paslon-images/hima_if_2_1x.webp"
                            alt=""
                            class="w-full max-w-48 min-w-48 sm:max-w-[37vw] sm:min-w-[37vw] md:max-w-[24vw] md:min-w-[24vw] xl:max-w-96 xl:min-w-96"
                        />

                        <img
                            src="/paslon-images/hima_mana_1_1x.webp"
                            alt=""
                            class="w-full max-w-48 min-w-48 sm:max-w-[37vw] sm:min-w-[37vw] md:max-w-[24vw] md:min-w-[24vw] xl:max-w-96 xl:min-w-96"
                        />
                        <img
                            src="/paslon-images/hima_mana_2_1x.webp"
                            alt=""
                            class="w-full max-w-48 min-w-48 sm:max-w-[37vw] sm:min-w-[37vw] md:max-w-[24vw] md:min-w-[24vw] xl:max-w-96 xl:min-w-96"
                        />

                        <img
                            src="/paslon-images/hima_si_1_1x.webp"
                            alt=""
                            class="w-full max-w-48 min-w-48 sm:max-w-[37vw] sm:min-w-[37vw] md:max-w-[24vw] md:min-w-[24vw] xl:max-w-96 xl:min-w-96"
                        />
                        <img
                            src="/paslon-images/hima_si_2_1x.webp"
                            alt=""
                            class="w-full max-w-48 min-w-48 sm:max-w-[37vw] sm:min-w-[37vw] md:max-w-[24vw] md:min-w-[24vw] xl:max-w-96 xl:min-w-96"
                        />

                        <img
                            src="/paslon-images/hima_sia_1_1x.webp"
                            alt=""
                            class="w-full max-w-48 min-w-48 sm:max-w-[37vw] sm:min-w-[37vw] md:max-w-[24vw] md:min-w-[24vw] xl:max-w-96 xl:min-w-96"
                        />
                        <img
                            src="/paslon-images/hima_sia_2_1x.webp"
                            alt=""
                            class="w-full max-w-48 min-w-48 sm:max-w-[37vw] sm:min-w-[37vw] md:max-w-[24vw] md:min-w-[24vw] xl:max-w-96 xl:min-w-96"
                        />
                    </div>
                </div>
            </div>
        </section>
        <!-- END HERO -->

        <!-- START ABOUT -->
        <section class="relative mb-16 flex w-full items-center justify-center px-3 md:mb-30 md:px-10" id="about">
            <div class="flex w-full max-w-7xl flex-col items-stretch justify-between gap-4 md:gap-16 lg:flex-row">
                <!-- CONTENT -->
                <div class="flex flex-1 flex-col items-start justify-start px-3 py-8 md:px-0">
                    <h2 class="relative font-unbounded text-2xl leading-[1.5em] font-bold text-black sm:text-3xl md:text-4xl lg:text-5xl">
                        Where Voices Become
                        <span class="text-blue">Leadership</span>
                        <img
                            src="/images/snow-pink.svg"
                            alt="snow pink illustration"
                            class="rotate-counter-clock absolute -top-10 right-0 h-12 -rotate-12 sm:h-14 lg:h-16 xl:h-20"
                        />
                    </h2>
                    <p class="mt-4 w-full max-w-[764px] text-left font-dmsans text-xs leading-relaxed text-black opacity-80 sm:text-base">
                        PEMIRA adalah wujud nyata demokrasi mahasiswa, di mana setiap suara memiliki makna dan kekuatan untuk menentukan arah
                        kepemimpinan. Melalui proses ini, kita bersama-sama membangun budaya partisipasi yang sehat, jujur, dan penuh tanggung jawab.
                    </p>
                    <LoginModal>
                        <template #trigger>
                            <button
                                class="mt-6 flex cursor-pointer items-center justify-center gap-2 bg-[#4342FF] px-3 py-2 font-unbounded text-xs text-white hover:bg-[#1c1cad] sm:px-5 sm:py-3 md:text-sm"
                            >
                                VOTE NOW <img src="/images/arrow-white.svg" alt="arrow white icon" class="w-3" />
                            </button>
                        </template>
                    </LoginModal>
                </div>
                <!-- ILLUSTRATION -->
                <div class="flex flex-1 items-center justify-center overflow-hidden rounded-md">
                    <img src="/images/about-image.webp" alt="" />
                </div>
            </div>
        </section>
        <!-- END ABOUT -->

        <!-- START LIVE COUNT -->
        {{}}
        <VoteCountContainer />
        <!-- START LIVE COUNT -->

        <!-- START CANDIDATES -->
        <section class="relative mb-16 flex w-full flex-col items-center justify-center px-0 sm:px-10 md:mb-30" id="candidate">
            <div class="relative flex w-full max-w-7xl flex-col gap-10">
                <!-- HEAD CONTENT -->
                <div class="flex w-full flex-col items-start justify-between gap-3 px-6 sm:px-0 md:flex-row md:items-center md:gap-12">
                    <div class="flex max-w-xl flex-1 flex-col items-start justify-start">
                        <h2 class="relative font-unbounded text-2xl leading-[1.5em] font-bold text-black sm:text-3xl md:text-4xl lg:text-5xl">
                            Let's Meet The
                            <span class="text-blue">Candidates</span>
                            <img
                                src="/images/star-pink.svg"
                                alt="snow pink illustration"
                                class="rotate-counter-clock absolute -top-10 right-0 h-12 w-16 -rotate-12 sm:h-14 lg:h-16 xl:h-20"
                            />
                        </h2>
                    </div>
                    <div class="flex max-w-xl flex-1 flex-col items-end justify-start">
                        <p class="w-full max-w-md text-left font-dmsans text-xs leading-relaxed text-black opacity-80 sm:text-base">
                            Penasaran dengan para kandidat yang akan maju pada PEMIRA 2025? Yuk simak dan dukung mereka pada ajang kali ini!
                        </p>
                    </div>
                </div>
                <!-- BODY CONTENT -->
                <div class="relative flex w-full flex-col items-center justify-center gap-12">
                    <!-- DPM BOX CANDIDATES -->
                    <div class="flex w-full flex-col items-center justify-center gap-5 md:gap-8">
                        <div class="flex flex-col items-center justify-center">
                            <h3 class="relative font-unbounded text-2xl leading-[1.5em] font-bold text-black sm:text-3xl md:text-4xl lg:text-5xl">
                                DPM
                            </h3>
                            <h4
                                class="text-blue relative px-16 text-center font-unbounded text-lg leading-[1.5em] font-bold sm:px-12 sm:text-xl md:text-2xl"
                            >
                                Dewan Perwakilan Mahasiswa
                            </h4>
                        </div>
                        <div class="md:flex-unwrap flex flex-row flex-wrap items-center justify-center gap-2 px-12 sm:gap-3 md:gap-6">
                            <img src="/paslon-images/dpm_1_1x.webp" alt="" class="w-[20vw] max-w-72 min-w-48 sm:min-w-36" />
                            <img src="/paslon-images/dpm_2_1x.webp" alt="" class="w-[20vw] max-w-72 min-w-48 sm:min-w-36" />
                        </div>
                    </div>

                    <!-- BEM BOX CANDIDATES -->
                    <div class="flex w-full flex-col items-center justify-center gap-5 md:gap-8">
                        <div class="flex flex-col items-center justify-center">
                            <h3 class="relative font-unbounded text-2xl leading-[1.5em] font-bold text-black sm:text-3xl md:text-4xl lg:text-5xl">
                                BEM
                            </h3>
                            <h4
                                class="text-blue relative px-16 text-center font-unbounded text-lg leading-[1.5em] font-bold sm:px-12 sm:text-xl md:text-2xl"
                            >
                                Badan Eksekutif Mahasiswa
                            </h4>
                        </div>
                        <div class="md:flex-unwrap flex flex-row flex-wrap items-center justify-center gap-2 px-12 sm:gap-3 md:gap-6">
                            <img src="/paslon-images/bem_1_1x.webp" alt="" class="w-[20vw] max-w-72 min-w-48 sm:min-w-36" />
                        </div>
                    </div>

                    <!-- SI BOX CANDIDATES -->
                    <div class="flex w-full flex-col items-center justify-center gap-5 md:gap-8">
                        <div class="flex flex-col items-center justify-center">
                            <h3 class="relative font-unbounded text-2xl leading-[1.5em] font-bold text-black sm:text-3xl md:text-4xl lg:text-5xl">
                                HIMA SI
                            </h3>
                            <h4
                                class="text-blue relative px-16 text-center font-unbounded text-lg leading-[1.5em] font-bold sm:px-12 sm:text-xl md:text-2xl"
                            >
                                Himpunan Mahasiswa Sistem Informasi
                            </h4>
                        </div>
                        <div class="md:flex-unwrap flex flex-row flex-wrap items-center justify-center gap-2 px-12 sm:gap-3 md:gap-6">
                            <img src="/paslon-images/hima_si_1_1x.webp" alt="" class="w-[20vw] max-w-72 min-w-48 sm:min-w-36" />
                            <img src="/paslon-images/hima_si_2_1x.webp" alt="" class="w-[20vw] max-w-72 min-w-48 sm:min-w-36" />
                        </div>
                    </div>

                    <!-- IF BOX CANDIDATES -->
                    <div class="flex w-full flex-col items-center justify-center gap-5 md:gap-8">
                        <div class="flex flex-col items-center justify-center">
                            <h3 class="relative font-unbounded text-2xl leading-[1.5em] font-bold text-black sm:text-3xl md:text-4xl lg:text-5xl">
                                HIMA IF
                            </h3>
                            <h4
                                class="text-blue relative px-16 text-center font-unbounded text-lg leading-[1.5em] font-bold sm:px-12 sm:text-xl md:text-2xl"
                            >
                                Himpunan Mahasiswa Informatika
                            </h4>
                        </div>
                        <div class="md:flex-unwrap flex flex-row flex-wrap items-center justify-center gap-2 px-12 sm:gap-3 md:gap-6">
                            <img src="/paslon-images/hima_if_1_1x.webp" alt="" class="w-[20vw] max-w-72 min-w-48 sm:min-w-36" />
                            <img src="/paslon-images/hima_if_2_1x.webp" alt="" class="w-[20vw] max-w-72 min-w-48 sm:min-w-36" />
                        </div>
                    </div>

                    <!-- SIA BOX CANDIDATES -->
                    <div class="flex w-full flex-col items-center justify-center gap-5 md:gap-8">
                        <div class="flex flex-col items-center justify-center">
                            <h3 class="relative font-unbounded text-2xl leading-[1.5em] font-bold text-black sm:text-3xl md:text-4xl lg:text-5xl">
                                HIMA SIA
                            </h3>
                            <h4
                                class="text-blue relative px-16 text-center font-unbounded text-lg leading-[1.5em] font-bold sm:px-12 sm:text-xl md:text-2xl"
                            >
                                Himpunan Mahasiswa Sistem Informasi Akuntansi
                            </h4>
                        </div>
                        <div class="md:flex-unwrap flex flex-row flex-wrap items-center justify-center gap-2 px-12 sm:gap-3 md:gap-6">
                            <img src="/paslon-images/hima_sia_1_1x.webp" alt="" class="w-[20vw] max-w-72 min-w-48 sm:min-w-36" />
                            <img src="/paslon-images/hima_sia_2_1x.webp" alt="" class="w-[20vw] max-w-72 min-w-48 sm:min-w-36" />
                        </div>
                    </div>

                    <!-- DKV BOX CANDIDATES -->
                    <div class="flex w-full flex-col items-center justify-center gap-5 md:gap-8">
                        <div class="flex flex-col items-center justify-center">
                            <h3 class="relative font-unbounded text-2xl leading-[1.5em] font-bold text-black sm:text-3xl md:text-4xl lg:text-5xl">
                                HIMA DKV
                            </h3>
                            <h4
                                class="text-blue relative px-16 text-center font-unbounded text-lg leading-[1.5em] font-bold sm:px-12 sm:text-xl md:text-2xl"
                            >
                                Himpunan Mahasiswa Desain Komunikasi Visual
                            </h4>
                        </div>
                        <div class="md:flex-unwrap flex flex-row flex-wrap items-center justify-center gap-2 px-12 sm:gap-3 md:gap-6">
                            <img src="/paslon-images/hima_dkv_1_1x.webp" alt="" class="w-[20vw] max-w-72 min-w-48 sm:min-w-36" />
                            <img src="/paslon-images/hima_dkv_2_1x.webp" alt="" class="w-[20vw] max-w-72 min-w-48 sm:min-w-36" />
                        </div>
                    </div>

                    <!-- MANAJEMEN BOX CANDIDATES -->
                    <div class="flex w-full flex-col items-center justify-center gap-5 md:gap-8">
                        <div class="flex flex-col items-center justify-center">
                            <h3 class="relative font-unbounded text-2xl leading-[1.5em] font-bold text-black sm:text-3xl md:text-4xl lg:text-5xl">
                                HIMA MANAJEMEN
                            </h3>
                            <h4
                                class="text-blue relative px-16 text-center font-unbounded text-lg leading-[1.5em] font-bold sm:px-12 sm:text-xl md:text-2xl"
                            >
                                Himpunan Mahasiswa Manajemen
                            </h4>
                        </div>
                        <div class="md:flex-unwrap flex flex-row flex-wrap items-center justify-center gap-2 px-12 sm:gap-3 md:gap-6">
                            <img src="/paslon-images/hima_mana_1_1x.webp" alt="" class="w-[20vw] max-w-72 min-w-48 sm:min-w-36" />
                            <img src="/paslon-images/hima_mana_2_1x.webp" alt="" class="w-[20vw] max-w-72 min-w-48 sm:min-w-36" />
                        </div>
                    </div>

                    <!-- AKUNTANSI BOX CANDIDATES -->
                    <div class="flex w-full flex-col items-center justify-center gap-5 md:gap-8">
                        <div class="flex flex-col items-center justify-center">
                            <h3 class="relative font-unbounded text-2xl leading-[1.5em] font-bold text-black sm:text-3xl md:text-4xl lg:text-5xl">
                                HIMA AKUNTANSI
                            </h3>
                            <h4
                                class="text-blue relative px-16 text-center font-unbounded text-lg leading-[1.5em] font-bold sm:px-12 sm:text-xl md:text-2xl"
                            >
                                Himpunan Mahasiswa Akuntansi
                            </h4>
                        </div>
                        <div class="md:flex-unwrap flex flex-row flex-wrap items-center justify-center gap-2 px-12 sm:gap-3 md:gap-6">
                            <img src="/paslon-images/hima_akun_1_1x.webp" alt="" class="w-[20vw] max-w-72 min-w-48 sm:min-w-36" />
                            <img src="/paslon-images/hima_akun_2_1x.webp" alt="" class="w-[20vw] max-w-72 min-w-48 sm:min-w-36" />
                        </div>
                    </div>

                    <!-- BD BOX CANDIDATES -->
                    <div class="flex w-full flex-col items-center justify-center gap-5 md:gap-8">
                        <div class="flex flex-col items-center justify-center">
                            <h3 class="relative font-unbounded text-2xl leading-[1.5em] font-bold text-black sm:text-3xl md:text-4xl lg:text-5xl">
                                HIMA BD
                            </h3>
                            <h4
                                class="text-blue relative px-16 text-center font-unbounded text-lg leading-[1.5em] font-bold sm:px-12 sm:text-xl md:text-2xl"
                            >
                                Himpunan Mahasiswa Bisnis Digital
                            </h4>
                        </div>
                        <div class="md:flex-unwrap flex flex-row flex-wrap items-center justify-center gap-2 px-12 sm:gap-3 md:gap-6">
                            <img src="/paslon-images/hima_bd_1_1x.webp" alt="" class="w-[20vw] max-w-72 min-w-48 sm:min-w-36" />
                            <img src="/paslon-images/hima_bd_2_1x.webp" alt="" class="w-[20vw] max-w-72 min-w-48 sm:min-w-36" />
                        </div>
                    </div>

                    <!-- DECORATION -->
                    <div
                        class="bg-setup absolute top-0 left-0 h-full w-9 bg-[url(/images/left-candidate-decoration.svg)] sm:w-12 md:w-16 lg:w-20"
                    ></div>
                    <div
                        class="bg-setup absolute top-0 right-0 h-full w-9 bg-[url(/images/right-candidate-decoration.svg)] sm:w-12 md:w-16 lg:w-20"
                    ></div>
                </div>
            </div>
        </section>
        <!-- END CANDIDATES -->

        <!-- START TIMELINES -->
        <Timeline id="timeline" />
        <!-- END TIMELINES -->
        <!-- START FOOTER -->
        <section class="relative flex w-full flex-col items-center justify-center bg-[#030520]" id="contact">
            <div class="relative flex w-full flex-col items-center justify-center gap-10 overflow-hidden px-8 pt-12 pb-16">
                <!-- CONTENT -->
                <div class="relative z-10 flex max-w-7xl flex-col items-center justify-center gap-2 pt-10 sm:gap-4">
                    <h2
                        class="relative max-w-xl text-center font-unbounded text-2xl leading-[1.5em] font-bold text-white md:text-3xl lg:text-4xl xl:text-5xl"
                    >
                        Let’s Move to The New
                        <span class="text-blue">World</span>
                        <img
                            src="/images/star-pink.svg"
                            alt="snow pink illustration"
                            class="rotate-counter-clock absolute -top-10 right-0 h-12 w-16 -rotate-12 sm:h-14 lg:h-16 xl:h-20"
                        />
                    </h2>
                    <p class="w-full max-w-lg text-center font-dmsans text-sm leading-relaxed text-white opacity-60 sm:text-base">
                        Penasaran dengan para kandidat yang akan maju pada PEMIRA 2025? Yuk simak dan dukung mereka pada ajang kali ini!
                    </p>
                    <LoginModal>
                        <template #trigger>
                            <button
                                class="mt-6 flex cursor-pointer items-center justify-center gap-2 bg-[#4342FF] px-3 py-2 font-unbounded text-xs text-white hover:bg-[#1c1cad] sm:px-5 sm:py-3 md:text-sm"
                            >
                                VOTE NOW <img src="/images/arrow-white.svg" alt="arrow white icon" class="w-3" />
                            </button>
                        </template>
                    </LoginModal>
                </div>
                <!-- MOVEMENT -->
                <div
                    class="absolute top-0 -left-1/4 flex w-full -rotate-45 flex-row items-center justify-center gap-8 overflow-hidden bg-[#4342FF] py-3 sm:gap-10 sm:py-4 md:gap-12"
                >
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-8 sm:h-10 md:h-14" />
                </div>
                <div
                    class="absolute -right-1/4 bottom-0 flex w-full -rotate-45 flex-row items-center justify-center gap-8 overflow-hidden bg-[#4342FF] py-3 sm:py-4 md:gap-12"
                >
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-8 sm:h-10 md:h-14" />
                </div>
            </div>

            <div
                class="relative flex w-full max-w-7xl flex-col items-center justify-between gap-2 px-6 py-8 sm:gap-6 md:flex-row md:gap-4 md:px-10"
                style="border-top: 1px solid #d9d9d920"
            >
                <a href="/" class="font-unbounded text-xl font-bold text-white sm:text-2xl md:text-3xl">
                    PEMIRA <span class="text-blue">2025</span>
                </a>
                <div class="flex flex-col items-center justify-end gap-4 md:items-end">
                    <div class="hidden w-full flex-row items-center justify-end gap-5 md:flex">
                        <!-- ITEM SOSMED -->
                        <a href="/" class="flex items-center justify-center gap-1">
                            <Instagram class="h-5 text-white opacity-70" />
                            <span class="cursor-pointer font-dmsans text-sm text-white opacity-70">@dpmprimakara</span>
                        </a>
                    </div>
                    <span class="font-dmsans text-sm text-white opacity-70">© 2024 PEMIRA Primakara. All rights reserved.</span>
                </div>
            </div>
        </section>
        <!-- END FOOTER -->
    </div>
</template>
