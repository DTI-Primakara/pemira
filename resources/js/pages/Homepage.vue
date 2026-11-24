<script setup lang="ts">
import Navbar from '@/components/core/Navbar.vue';
import AboutPemira from '@/components/sections/homepage/AboutPemira.vue';
import CandidateShowcase from '@/components/sections/homepage/CandidateShowcase.vue';
import ErrorModal from '@/components/sections/homepage/ErrorModal.vue';
import HeroCardShuffle from '@/components/sections/homepage/HeroCardShuffle.vue';
import LoginModal from '@/components/sections/homepage/LoginModal.vue';
import RibbonMarquee from '@/components/sections/homepage/RibbonMarquee.vue';
import Timeline from '@/components/sections/homepage/Timeline.vue';
import VoteCountContainer from '@/components/VoteCountContainer.vue';
import { initLenis } from '@/lib/lenis';
import type { HomepageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { Instagram } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';

const { props } = usePage<HomepageProps>();

const elections = computed(() => props.elections ?? []);

/** Every paslon with homepage artwork, flattened for the hero reel. */
const heroCandidates = computed(() => elections.value.flatMap((election) => election.candidates).filter((candidate) => candidate.image_url));
const timelines = computed(() => props.timelines ?? []);

const expanded = ref(false);
const showModalError = ref(false);

onMounted(() => {
    initLenis();

    if (props.flash?.error) {
        showModalError.value = true;
    }
});

function scrollTo(id: string) {
    const el = document.getElementById(id);
    if (el) {
        const y = el.getBoundingClientRect().top + window.scrollY - 120;
        window.scrollTo({ top: y, behavior: 'smooth' });
    }
}
</script>

<template>
    <div class="flex min-h-screen w-full flex-col items-center justify-start overflow-x-clip bg-white">
        <Navbar />

        <!-- START HERO -->
        <section
            class="relative mb-16 flex w-full items-center justify-center overflow-x-hidden overflow-y-visible bg-white pt-40 md:mb-30 md:pt-48"
            id="hero"
        >
            <div class="relative flex w-full max-w-8xl flex-col items-center justify-start overflow-y-visible px-6 md:px-10">
                <div class="relative z-10 flex w-full max-w-7xl flex-col items-center justify-center gap-12 lg:flex-row">
                    <div class="mb-8 flex w-full flex-col items-start justify-start gap-2">
                        <h1
                            v-reveal
                            class="relative text-gradient-main-26 font-unbounded text-[17.5vw] leading-[15.5vw] font-bold sm:text-[16.5vw] sm:leading-[14vw] lg:text-[144px] lg:leading-32"
                        >
                            PEMIRA
                            <br />
                            <img
                                src="/images/asset26-1.svg"
                                alt="snow orange svg"
                                class="rotate-counter-clock absolute top-[-6vw] -right-[12vw] w-[12vw] rotate-24 md:-right-[8vw] md:w-[8vw] xl:w-[104px]"
                            />
                            <span class="text-gradient-secondary-26">2026</span>
                        </h1>
                        <div v-reveal="140" class="w-full max-w-[764px]">
                            <p
                                ref="textRef"
                                :class="[
                                    'mt-4 text-left font-dmsans text-sm leading-relaxed text-black opacity-80 sm:text-lg md:px-0',
                                    !expanded ? 'line-clamp-3' : '',
                                ]"
                            >
                                Pemilu Raya Mahasiswa atau PEMIRA merupakan salah satu kegiatan yang diselenggarakan oleh DPM Primakara University.
                                Pada kegiatan PEMIRA ini mahasiswa diberikan kesempatan untuk menentukan pemimpin ORMAWA baru untuk kepengurusan
                                berikutnya. Gunakan hak suara kalian untuk menentukan pemimpin selanjutnya.
                            </p>

                            <span
                                @click="expanded = !expanded"
                                class="mt-2 block cursor-pointer text-left text-xs font-semibold text-blue-600 hover:underline sm:text-sm"
                            >
                                {{ expanded ? 'Tutup' : 'Baca selengkapnya' }}
                            </span>
                        </div>
                        <div v-reveal="240" class="flex flex-row gap-4">
                            <LoginModal>
                                <template #trigger>
                                    <button
                                        class="mt-6 flex cursor-pointer items-center justify-center gap-2 bg-gradient-main-26 px-3 py-2 font-unbounded text-xs text-white hover:bg-[#1c1cad] sm:px-5 sm:py-3 md:text-sm"
                                    >
                                        Vote Sekarang <img src="/images/arrow-white.svg" alt="arrow white icon" class="w-3" />
                                    </button>
                                </template>
                            </LoginModal>
                            <button
                                @click.prevent="scrollTo('candidate')"
                                class="mt-6 flex cursor-pointer items-center justify-center gap-2 border-[1px] border-[#E8E8E8] text-gradient-main-26 px-3 py-2 font-unbounded text-xs text-white hover:bg-[#1c1cad] sm:px-5 sm:py-3 md:text-sm"
                            >
                                Lihat Kandidat
                            </button>
                            <a href="#"></a>
                        </div>

                        <ErrorModal v-model:open="showModalError" :message="props.flash?.error" />
                    </div>

                    <HeroCardShuffle v-reveal.zoom="180" :candidates="heroCandidates" />
                </div>
            </div>
        </section>
        <!-- END HERO -->

        <!-- DECORATION HERO -->
        <div class="relative z-30 hidden w-full animate-wiggle items-center justify-center sm:mb-16 sm:flex">
            <div class="relative z-30 w-full">
                <img
                    src="/images/asset-homepage-hero-1.svg"
                    alt=""
                    aria-hidden="true"
                    class="absolute -right-10 -bottom-20 z-30 h-[260px] w-auto max-w-none md:bottom-0 md:h-[300px] 2xl:-bottom-8 2xl:h-[340px]"
                />
            </div>
        </div>
        <!-- DECORATION HERO -->

        <!-- RIBBON -->
        <RibbonMarquee v-reveal.zoom />
        <!-- RIBBON -->

        <!-- START ABOUT -->
        <section class="relative mb-16 flex w-full flex-col items-center justify-center px-0 md:mb-30" id="about">
            <div class="relative flex w-full max-w-7xl flex-col gap-10 pt-[10vw] md:gap-16 md:pt-[64px]">
                <!-- HEAD CONTENT -->
                <div class="flex w-full flex-col items-center justify-center gap-3 px-6 sm:px-0 md:items-center md:gap-12">
                    <div class="flex w-full flex-1 flex-col items-center justify-center gap-3">
                        <h2
                            v-reveal
                            class="relative text-gradient-main-26 text-center font-unbounded text-[6.1vw] leading-[8.4vw] font-bold text-black md:text-[52px] md:leading-[72px]"
                        >
                            Satu Langkah Menuju
                            <br />
                            <span class="text-gradient-secondary-26">Primakara Unggul</span>
                            <img
                                src="/images/asset26-2.svg"
                                alt="snow pink illustration"
                                class="absolute -top-[10vw] -right-6 w-[10vw] md:-top-[64px] md:w-[64px]"
                            />
                            <img
                                src="/images/asset26-3.svg"
                                alt="snow pink illustration"
                                class="absolute -bottom-5 -left-5 w-[10vw] md:-bottom-[8px] md:w-[64px]"
                            />
                        </h2>
                        <p v-reveal="140" class="w-full text-center font-dmsans text-sm leading-relaxed text-black opacity-80 sm:text-lg md:max-w-xl">
                            Pemira adalah wujud nyata demokrasi mahasiswa, di mana setiap suara memiliki makna dan kekuatan untuk menentukan arah
                            kepemimpinan.
                        </p>
                    </div>
                    <div class="flex max-w-xl flex-1 flex-col items-end justify-start"></div>
                </div>
                <!-- HEAD CONTENT -->

                <!-- BODY CONTENT -->
                <div class="w-full px-6 sm:px-10">
                    <AboutPemira :elections="elections" />
                </div>
                <!-- BODY CONTENT -->
            </div>
        </section>
        <!-- END ABOUT -->

        <!-- START LIVE COUNT -->
        <VoteCountContainer />
        <!-- START LIVE COUNT -->

        <!-- START CANDIDATES -->
        <section class="relative mb-16 flex w-full flex-col items-center justify-center px-0 md:mb-30" id="candidate">
            <div class="relative flex w-full flex-col items-center justify-center gap-10">
                <!-- HEAD CONTENT -->
                <div class="flex w-full max-w-7xl flex-col items-center justify-between gap-3 px-6 sm:px-10 md:flex-row md:items-start md:gap-12">
                    <div v-reveal.left class="flex max-w-2xl flex-1 flex-col items-start justify-start">
                        <h2
                            class="relative text-gradient-main-26 text-center font-unbounded text-[6.1vw] leading-[8.4vw] font-bold text-black md:text-left md:text-[40px] md:leading-[56px]"
                        >
                            Mari Lihat Bersama
                            <span class="text-gradient-secondary-26">Para Calon Pemimpin</span>
                            <img
                                src="/images/asset26-1.svg"
                                alt="snow pink illustration"
                                class="rotate-counter-clock absolute -top-10 -right-2 h-12 w-16 -rotate-12 sm:-right-8 sm:h-14 lg:h-16 xl:h-20"
                            />
                        </h2>
                    </div>
                    <div v-reveal.right="120" class="flex w-full max-w-xl flex-1 flex-col items-center justify-center md:items-end md:justify-start">
                        <p class="w-full max-w-sm text-center font-dmsans text-xs leading-relaxed text-black opacity-80 sm:text-base md:text-right">
                            Penasaran dengan para kandidat yang akan maju pada PEMIRA 2026? Yuk simak dan dukung mereka pada ajang kali ini!
                        </p>
                    </div>
                </div>
                <!-- BODY CONTENT -->
                <CandidateShowcase :elections="elections" />
            </div>
        </section>
        <!-- END CANDIDATES -->

        <!-- START TIMELINES -->
        <Timeline id="timeline" :entries="timelines" />
        <!-- END TIMELINES -->
        <!-- START FOOTER -->
        <section class="relative flex w-full flex-col items-center justify-center bg-[#030520]" id="contact">
            <div class="relative flex w-full flex-col items-center justify-center gap-10 overflow-hidden px-8 pt-12 pb-16">
                <!-- CONTENT -->
                <div class="relative z-10 flex max-w-7xl flex-col items-center justify-center gap-2 pt-10 sm:gap-4">
                    <h2
                        v-reveal
                        class="relative max-w-xl text-center font-unbounded text-2xl leading-[1.5em] font-bold text-white md:text-3xl lg:text-4xl xl:text-5xl"
                    >
                        Let’s Move to The New
                        <span class="text-blue">World</span>
                        <img
                            src="/images/asset26-1.svg"
                            alt=""
                            aria-hidden="true"
                            class="rotate-counter-clock absolute -top-14 -right-4 h-12 w-12 rotate-24 opacity-90 invert sm:-right-8 sm:h-14 sm:w-14 lg:h-16 lg:w-16"
                        />
                    </h2>
                    <p v-reveal="140" class="w-full max-w-lg text-center font-dmsans text-sm leading-relaxed text-white opacity-60 sm:text-base">
                        Penasaran dengan para kandidat yang akan maju pada PEMIRA 2026? Yuk simak dan dukung mereka pada ajang kali ini!
                    </p>
                    <LoginModal>
                        <template #trigger>
                            <button
                                class="mt-6 flex cursor-pointer items-center justify-center gap-2 bg-gradient-main-26 px-3 py-2 font-unbounded text-xs text-white transition-opacity hover:opacity-90 sm:px-5 sm:py-3 md:text-sm"
                            >
                                VOTE NOW <img src="/images/arrow-white.svg" alt="arrow white icon" class="w-3" />
                            </button>
                        </template>
                    </LoginModal>
                </div>
                <!-- MOVEMENT -->
                <div
                    class="absolute top-0 -left-1/4 flex w-full -rotate-45 flex-row items-center justify-center gap-8 overflow-hidden bg-gradient-main-26 py-3 sm:gap-10 sm:py-4 md:gap-12"
                >
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-left h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-left font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-left h-8 sm:h-10 md:h-14" />
                </div>
                <div
                    class="absolute -right-1/4 bottom-0 flex w-full -rotate-45 flex-row items-center justify-center gap-8 overflow-hidden bg-gradient-main-26 py-3 sm:py-4 md:gap-12"
                >
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-right h-8 sm:h-10 md:h-14" />
                    <p class="linear-move-right font-unbounded text-xl font-bold text-white sm:text-3xl md:text-5xl">VOTE</p>
                    <img src="/images/asset26-1.svg" alt="" aria-hidden="true" class="linear-move-right h-8 sm:h-10 md:h-14" />
                </div>
            </div>

            <div
                class="relative flex w-full max-w-7xl flex-col items-center justify-between gap-2 px-6 py-8 sm:gap-6 md:flex-row md:gap-4 md:px-10"
                style="border-top: 1px solid #d9d9d920"
            >
                <div class="flex flex-col items-center justify-center gap-2 md:items-start md:justify-start">
                    <a href="https://pemira.primakara.ac.id" class="font-unbounded text-xl font-bold text-white sm:text-2xl md:text-3xl">
                        PEMIRA <span class="text-blue">2026</span>
                    </a>
                    <span class="text-center font-dmsans text-sm text-white opacity-70 md:text-left"
                        >© 2026 PEMIRA Primakara. All rights reserved.</span
                    >
                </div>
                <div class="flex flex-col items-center justify-end gap-4 md:items-end">
                    <div class="hidden w-full flex-row items-center justify-end gap-5 md:flex">
                        <!-- ITEM SOSMED -->
                        <a href="https://www.instagram.com/dpmprimakara/" class="flex items-center justify-center gap-1">
                            <Instagram class="h-5 text-white opacity-70" />
                            <span class="cursor-pointer font-dmsans text-sm text-white opacity-70">@dpmprimakara</span>
                        </a>
                    </div>
                    <span class="text-center font-dmsans text-sm text-white opacity-70 md:text-right">Developed with ❤️ by PEMIRA Primakara.</span>
                </div>
            </div>
        </section>
        <!-- END FOOTER -->
    </div>
</template>
