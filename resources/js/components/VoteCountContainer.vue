<script lang="ts" setup>
import { AppPageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import VoteCount from './sections/homepage/VoteCount.vue';

const { props } = usePage<AppPageProps>();

const isVoteCountVisible = computed(() => {
    return props.config?.find((item) => item.title === 'is_vote_count_visible')?.value === 'true';
});
</script>

<template>
    <section v-if="isVoteCountVisible" class="relative mb-16 flex w-full items-center justify-center px-3 md:mb-30 md:px-10">
        <div class="flex w-full max-w-7xl flex-col items-stretch justify-between gap-4 md:gap-16">
            <div class="flex flex-col items-center justify-center gap-4">
                <h2
                    class="relative max-w-3xl text-center font-unbounded text-2xl leading-[1.5em] font-bold text-black sm:text-3xl md:text-4xl lg:text-5xl"
                >
                    Live Tracking for
                    <span class="text-blue">Transparent</span>
                    Voting
                    <img
                        src="/images/snow-pink.svg"
                        alt="snow pink illustration"
                        class="rotate-counter-clock absolute -top-10 right-0 h-12 -rotate-12 sm:h-14 lg:h-16 xl:h-20"
                    />
                </h2>

                <p class="mt-4 w-full max-w-[764px] text-center font-dmsans text-xs leading-relaxed text-black opacity-80 sm:text-base">
                    Pantau pergerakan suara secara langsung. Data diperbarui secara berkala untuk memastikan setiap suara tercatat dengan transparan
                    dan sesuai jalurnya.
                </p>
            </div>

            <VoteCount />
        </div>
    </section>
</template>
