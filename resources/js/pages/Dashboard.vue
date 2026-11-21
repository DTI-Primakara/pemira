<script setup lang="ts">
import ConfirmVoting from '@/components/sections/dashboard/ConfirmVoting.vue';
import VotingCta from '@/components/sections/dashboard/VotingCta.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { CONTACT_ADMIN_VOTER_URL } from '@/lib/contact';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Voting',
        href: dashboard().url,
    },
];

interface StatusVoting {
    open: boolean;
    status: 'voted' | 'not voted' | 'passed';
    ended: boolean;
    /** Whether this student is on the active period's voter roll. */
    enrolled: boolean;
}

interface User {
    name: string;
}

const page = usePage();
const user = computed(() => page.props.user as User);

const statusVoting = computed<StatusVoting>(() => ({
    open: page.props.votingOpen as boolean,
    status: page.props.status as 'voted' | 'not voted' | 'passed',
    ended: page.props.votingEnded as boolean,
    enrolled: page.props.enrolled as boolean,
}));
</script>

<template>
    <Head title="Voting" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-2xl p-4 px-6 md:mx-8 md:p-4">
            <div class="grid auto-rows-min grid-cols-1 gap-4 md:grid-cols-3">
                <div class="col-span-3 mt-4 space-y-2 sm:space-y-4">
                    <h1 class="scroll-m-20 font-unbounded text-2xl leading-snug font-extrabold tracking-tight md:text-3xl lg:text-4xl xl:text-5xl">
                        Selamat
                        {{
                            Number(new Date().toTimeString().slice(0, 2)) >= 6 && Number(new Date().toTimeString().slice(0, 2)) < 12
                                ? 'Pagi'
                                : Number(new Date().toTimeString().slice(0, 2)) >= 12 && Number(new Date().toTimeString().slice(0, 2)) < 18
                                  ? 'Siang'
                                  : 'Malam'
                        }}, <span class="text-gradient-main-26">{{ user.name }}</span>
                    </h1>
                    <p class="mb-4 font-dmsans text-sm text-muted-foreground sm:text-base lg:text-lg xl:text-xl">
                        Selamat datang kembali di website resmi PEMIRA 2026
                    </p>
                </div>
                <div
                    class="relative col-span-3 mb-4 flex items-center overflow-hidden rounded-lg border border-slate-200/70 bg-gradient-main-26 dark:border-slate-800/70"
                >
                    <div class="c-box-header flex w-full flex-row items-stretch justify-between gap-4">
                        <!-- text content -->
                        <div
                            class="c-box-header-content z-10 col-span-3 max-w-2xl space-y-6 p-5 py-8 sm:p-8 md:col-span-2 md:max-w-4xl md:px-12 md:py-16 md:text-left"
                        >
                            <h2
                                class="mb-0 max-w-[200px] min-w-[200px] scroll-m-20 font-unbounded text-xl leading-[1.4em] font-extrabold tracking-tight text-white sm:max-w-[656px] sm:text-3xl md:min-w-[440px] md:text-4xl lg:min-w-[656px] lg:text-5xl"
                            >
                                PESTA DEMOKRASI <span class="text-[#DFFF00]">PEMIRA 2026!</span>
                            </h2>
                            <p class="mt-2 font-dmsans text-xs text-white/90 sm:text-base md:mt-6 md:min-w-[440px] md:text-lg lg:min-w-[656px]">
                                Tentukan pilihanmu secara langsung, terenkripsi dan aman tanpa keraguan
                            </p>
                        </div>

                        <!-- image -->
                        <div class="c-box-header-image relative flex w-80 min-w-80 items-center justify-end object-cover">
                            <img src="/images/dashboard-bg.svg" alt="" class="absolute bottom-0 h-[120%]" />
                        </div>
                    </div>
                </div>
            </div>

            <h1 class="scroll-m-20 font-unbounded text-base font-semibold tracking-tight sm:text-lg md:text-xl lg:text-2xl">Dashboard</h1>

            <!-- Signing in only proves who you are. Being allowed to vote is a
                 separate, per-period decision the admin makes, so someone who is
                 not on the roll is told here rather than at the SSO. -->
            <template v-if="!statusVoting.enrolled">
                <VotingCta
                    variant="disabled"
                    title="Belum Terdaftar Memilih"
                    desc="Anda belum termasuk dalam daftar pemilih PEMIRA 2026. Silakan hubungi admin untuk didaftarkan."
                    icon="/images/not-open.svg"
                    icon-alt="belum-terdaftar"
                    action-label="Hubungi Admin"
                    :action-href="CONTACT_ADMIN_VOTER_URL"
                />
            </template>

            <template v-else-if="statusVoting.open && statusVoting.status === 'not voted'">
                <ConfirmVoting>
                    <template #trigger>
                        <VotingCta
                            variant="default"
                            title="Start Vote Now"
                            desc="Voting masih berlangsung"
                            icon="/images/join-vote.svg"
                            icon-alt="join-vote"
                        />
                    </template>
                </ConfirmVoting>
            </template>

            <template v-else-if="statusVoting.open && statusVoting.status === 'voted'">
                <VotingCta
                    variant="success"
                    title="You Have Voted"
                    desc="Terimakasih sudah memberikan suara"
                    icon="/images/done-voted.svg"
                    icon-alt="done-vote"
                />
            </template>

            <template v-else-if="statusVoting.ended || statusVoting.status === 'passed'">
                <VotingCta
                    variant="error"
                    title="Voting Closed"
                    desc="Masa voting telah usai"
                    icon="/images/vote-closed.svg"
                    icon-alt="vote-closed"
                />
            </template>

            <template v-else>
                <VotingCta
                    variant="disabled"
                    title="Voting Not Yet Open"
                    desc="Voting belum dibuka"
                    icon="/images/not-open.svg"
                    icon-alt="not-open"
                />
            </template>
        </div>
    </AppLayout>
</template>
