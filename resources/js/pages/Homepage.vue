<script setup lang="ts">
import Navbar from '@/components/core/Navbar.vue';
import { initLenis } from '@/lib/lenis';
import { onMounted } from 'vue';

onMounted(() => {
    initLenis();
});
</script>

<template>
    <div class="flex min-h-screen w-full flex-col items-center justify-start bg-white">
        <Navbar />

        <!-- START HERO -->
        <section class="relative flex w-full items-center justify-start overflow-hidden bg-white pt-36">
            <div class="flex w-full flex-col items-center justify-start">
                <div class="z-10 flex flex-col items-center justify-center">
                    <h1 class="relative font-unbounded text-[12vw] leading-tight font-bold text-[#4342FF] xl:text-[160px]">
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
                    <h2 class="relative font-unbounded text-[5vw] leading-tight font-bold text-[#1F1E4B] xl:text-[64px]">2025</h2>
                    <p class="mt-4 w-full max-w-[764px] text-center font-dmsans text-base leading-relaxed text-[#1F1E4B] opacity-80">
                        Bersama bangun Primakara University menjadi lebih baik. Bersama bangun Primakara University menjadi lebih baik. Bersama bangun
                        Primakara University menjadi lebih baik
                    </p>
                    <button class="mt-8 cursor-pointer bg-[#4342FF] px-5 py-3 font-unbounded text-sm text-white hover:bg-[#1c1cad]">VOTE NOW</button>
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
    </div>
</template>
