<script setup lang="ts">
import Navbar from '@/components/core/Navbar.vue';
import Timeline from '@/components/sections/homepage/Timeline.vue';
import { initLenis } from '@/lib/lenis';
import { defineProps, onMounted } from 'vue';

interface Props {
    ssoUri: string;
    clientId: string;
    redirectUri: string;
}

const props = defineProps<Props>();

console.log(props.clientId);

onMounted(() => {
    initLenis();
});
</script>

<template>
    <div class="flex min-h-screen w-full flex-col items-center justify-start bg-white">
        <Navbar :sso-uri="props.ssoUri" :client-id="props.clientId" :redirect-uri="props.redirectUri" />

        <!-- START HERO -->
        <section class="relative mb-30 flex w-full items-center justify-start overflow-hidden bg-white pt-36">
            <div class="flex w-full flex-col items-center justify-start">
                <div class="z-10 flex flex-col items-center justify-center">
                    <h1 class="text-blue relative font-unbounded text-[12vw] leading-tight font-bold xl:text-[160px]">
                        <img
                            src="/images/star-pink.svg"
                            alt="star pink svg"
                            class="rotate-clock absolute top-[7vw] -left-[5vw] w-[8vw] rotate-24 xl:w-[120px]"
                        />
                        PEMIRA
                        <img
                            src="/images/snow-orange.svg"
                            alt="snow orange svg"
                            class="rotate-counter-clock absolute top-[0vw] -right-[3vw] w-[8vw] rotate-24 xl:w-[120px]"
                        />
                    </h1>
                    <h2 class="relative font-unbounded text-[5vw] leading-tight font-bold text-black xl:text-[64px]">2025</h2>
                    <p class="mt-4 w-full max-w-[764px] text-center font-dmsans text-base leading-relaxed text-black opacity-80">
                        Bersama bangun Primakara University menjadi lebih baik. Bersama bangun Primakara University menjadi lebih baik. Bersama bangun
                        Primakara University menjadi lebih baik
                    </p>
                    <button
                        class="mt-6 flex cursor-pointer items-center justify-center gap-2 bg-[#4342FF] px-5 py-3 font-unbounded text-sm text-white hover:bg-[#1c1cad]"
                    >
                        VOTE NOW <img src="/images/arrow-white.svg" alt="arrow white icon" class="w-3" />
                    </button>
                </div>
                <div class="relative mt-32 flex w-full flex-col items-center justify-center">
                    <div
                        class="absolute -left-40 flex w-full rotate-32 flex-row items-center justify-center gap-12 overflow-hidden bg-[#4342FF] py-4"
                    >
                        <p class="linear-move-left font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-14" />
                        <p class="linear-move-left font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-14" />
                        <p class="linear-move-left font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-14" />
                        <p class="linear-move-left font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-14" />
                        <p class="linear-move-left font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-14" />
                        <p class="linear-move-left font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-14" />
                        <p class="linear-move-left font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-14" />
                        <p class="linear-move-left font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-14" />
                        <p class="linear-move-left font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-14" />
                        <p class="linear-move-left font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-14" />
                        <p class="linear-move-left font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-14" />
                        <p class="linear-move-left font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-left h-14" />
                    </div>
                    <div
                        class="absolute -right-40 flex w-full -rotate-32 flex-row items-center justify-center gap-12 overflow-hidden bg-[#4342FF] py-4"
                    >
                        <p class="linear-move-right font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-14" />
                        <p class="linear-move-right font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-14" />
                        <p class="linear-move-right font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-14" />
                        <p class="linear-move-right font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-14" />
                        <p class="linear-move-right font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-14" />
                        <p class="linear-move-right font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-14" />
                        <p class="linear-move-right font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-14" />
                        <p class="linear-move-right font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-14" />
                        <p class="linear-move-right font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-14" />
                        <p class="linear-move-right font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-14" />
                        <p class="linear-move-right font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-14" />
                        <p class="linear-move-right font-unbounded text-5xl font-bold text-white">VOTE</p>
                        <img src="/images/star-pink.svg" alt="star pink" class="linear-move-right h-14" />
                    </div>
                </div>
                <div class="relative flex w-full flex-row items-start justify-start gap-4 overflow-hidden">
                    <img src="/images/example-card-1.png" alt="" class="w-[20vw]" />
                    <img src="/images/example-card-2.png" alt="" class="w-[20vw]" />
                    <img src="/images/example-card-1.png" alt="" class="w-[20vw]" />
                    <img src="/images/example-card-2.png" alt="" class="w-[20vw]" />
                    <img src="/images/example-card-1.png" alt="" class="w-[20vw]" />
                    <img src="/images/example-card-2.png" alt="" class="w-[20vw]" />
                </div>
            </div>
        </section>
        <!-- END HERO -->

        <!-- START ABOUT -->
        <section class="relative mb-30 flex w-full items-center justify-center">
            <div class="flex w-full max-w-7xl flex-row items-stretch justify-between gap-16">
                <!-- CONTENT -->
                <div class="flex flex-1 flex-col items-start justify-start py-8">
                    <h2 class="relative font-unbounded text-5xl leading-[1.5em] font-bold text-black">
                        Where Voices Become
                        <span class="text-blue">Leadership</span>
                        <img
                            src="/images/snow-pink.svg"
                            alt="snow pink illustration"
                            class="rotate-counter-clock absolute -top-10 right-0 -rotate-12"
                        />
                    </h2>
                    <p class="mt-4 w-full max-w-[764px] text-left font-dmsans text-base leading-relaxed text-black opacity-80">
                        Pemira adalah wujud nyata demokrasi mahasiswa, di mana setiap suara memiliki makna dan kekuatan untuk menentukan arah
                        kepemimpinan. Melalui proses ini, kita bersama-sama membangun budaya partisipasi yang sehat, jujur, dan penuh tanggung jawab.
                    </p>
                    <button
                        class="mt-6 flex cursor-pointer items-center justify-center gap-2 bg-[#4342FF] px-5 py-3 font-unbounded text-sm text-white hover:bg-[#1c1cad]"
                    >
                        VOTE NOW <img src="/images/arrow-white.svg" alt="arrow white icon" class="w-3" />
                    </button>
                </div>
                <!-- ILLUSTRATION -->
                <div class="bg-blue flex flex-1 items-center justify-center overflow-hidden rounded-md"></div>
            </div>
        </section>
        <!-- END ABOUT -->

        <!-- START CANDIDATES -->
        <section class="relative mb-30 flex w-full flex-col items-center justify-center">
            <div class="relative flex w-full max-w-7xl flex-col gap-10">
                <!-- HEAD CONTENT -->
                <div class="flex w-full flex-row items-center justify-between gap-12">
                    <div class="flex max-w-xl flex-1 flex-col items-start justify-start">
                        <h2 class="relative font-unbounded text-5xl leading-[1.5em] font-bold text-black">
                            Let's Meet The
                            <span class="text-blue">Candidates</span>
                            <img
                                src="/images/star-pink.svg"
                                alt="snow pink illustration"
                                class="rotate-counter-clock absolute -top-10 right-0 w-16 -rotate-12"
                            />
                        </h2>
                    </div>
                    <div class="flex max-w-xl flex-1 flex-col items-end justify-start">
                        <p class="w-full max-w-md text-left font-dmsans text-base leading-relaxed text-black opacity-80">
                            Penasaran dengan para kandidat yang akan maju pada PEMIRA 2025? Yuk simak dan dukung mereka pada ajang kali ini!
                        </p>
                    </div>
                </div>
                <!-- BODY CONTENT -->
                <div class="relative flex w-full flex-col items-center justify-center">
                    <!-- DPM BOX CANDIDATES -->
                    <div class="flex w-full flex-col items-center justify-center gap-8">
                        <div class="flex flex-col items-center justify-center">
                            <h3 class="relative font-unbounded text-5xl leading-[1.5em] font-bold text-black">DPM</h3>
                            <h4 class="text-blue relative font-unbounded text-2xl leading-[1.5em] font-bold">Dewan Perwakilan Mahasiswa</h4>
                        </div>
                        <div class="flex flex-row items-center justify-center gap-6">
                            <img src="/images/example-card-1.png" alt="" class="w-[20vw]" />
                            <img src="/images/example-card-2.png" alt="" class="w-[20vw]" />
                        </div>
                    </div>
                    <!-- DPM BOX CANDIDATES -->
                    <div class="flex w-full flex-col items-center justify-center gap-8">
                        <div class="flex flex-col items-center justify-center">
                            <h3 class="relative font-unbounded text-5xl leading-[1.5em] font-bold text-black">DPM</h3>
                            <h4 class="text-blue relative font-unbounded text-2xl leading-[1.5em] font-bold">Dewan Perwakilan Mahasiswa</h4>
                        </div>
                        <div class="flex flex-row items-center justify-center gap-6">
                            <img src="/images/example-card-1.png" alt="" class="w-[20vw]" />
                            <img src="/images/example-card-2.png" alt="" class="w-[20vw]" />
                        </div>
                    </div>
                    <!-- DPM BOX CANDIDATES -->
                    <div class="flex w-full flex-col items-center justify-center gap-8">
                        <div class="flex flex-col items-center justify-center">
                            <h3 class="relative font-unbounded text-5xl leading-[1.5em] font-bold text-black">DPM</h3>
                            <h4 class="text-blue relative font-unbounded text-2xl leading-[1.5em] font-bold">Dewan Perwakilan Mahasiswa</h4>
                        </div>
                        <div class="flex flex-row items-center justify-center gap-6">
                            <img src="/images/example-card-1.png" alt="" class="w-[20vw]" />
                            <img src="/images/example-card-2.png" alt="" class="w-[20vw]" />
                        </div>
                    </div>
                    <!-- DPM BOX CANDIDATES -->
                    <div class="flex w-full flex-col items-center justify-center gap-8">
                        <div class="flex flex-col items-center justify-center">
                            <h3 class="relative font-unbounded text-5xl leading-[1.5em] font-bold text-black">DPM</h3>
                            <h4 class="text-blue relative font-unbounded text-2xl leading-[1.5em] font-bold">Dewan Perwakilan Mahasiswa</h4>
                        </div>
                        <div class="flex flex-row items-center justify-center gap-6">
                            <img src="/images/example-card-1.png" alt="" class="w-[20vw]" />
                            <img src="/images/example-card-2.png" alt="" class="w-[20vw]" />
                        </div>
                    </div>
                    <!-- DPM BOX CANDIDATES -->
                    <div class="flex w-full flex-col items-center justify-center gap-8">
                        <div class="flex flex-col items-center justify-center">
                            <h3 class="relative font-unbounded text-5xl leading-[1.5em] font-bold text-black">DPM</h3>
                            <h4 class="text-blue relative font-unbounded text-2xl leading-[1.5em] font-bold">Dewan Perwakilan Mahasiswa</h4>
                        </div>
                        <div class="flex flex-row items-center justify-center gap-6">
                            <img src="/images/example-card-1.png" alt="" class="w-[20vw]" />
                            <img src="/images/example-card-2.png" alt="" class="w-[20vw]" />
                        </div>
                    </div>

                    <!-- DECORATION -->
                    <div class="bg-setup absolute top-0 left-0 h-full w-20 bg-[url(/images/left-candidate-decoration.svg)]"></div>
                    <div class="bg-setup absolute top-0 right-0 h-full w-20 bg-[url(/images/right-candidate-decoration.svg)]"></div>
                </div>
            </div>
        </section>
        <!-- END CANDIDATES -->

        <!-- START TIMELINES -->
        <Timeline />
        <!-- END TIMELINES -->
    </div>
</template>
