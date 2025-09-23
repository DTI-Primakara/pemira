<script setup lang="ts">
import ConfirmVoting from '@/components/sections/dashboard/ConfirmVoting.vue';
import VotingCta from '@/components/sections/dashboard/VotingCta.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Voting',
        href: dashboard().url,
    },
];

// untuk testing awal
interface StatusVoting {
    open: boolean;
    doneVote: boolean;
    ended: boolean;
}

const statusVoting: StatusVoting = {
    open: true,
    doneVote: false,
    ended: false,
};

const page = usePage();
const user = JSON.parse(JSON.stringify(page.props.auth.user)).data;
</script>

<template>
    <Head title="Voting" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-8 flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-2xl p-4">
            <div class="grid auto-rows-min grid-cols-1 gap-4 md:grid-cols-3">
                <div class="col-span-3 mt-4 space-y-4">
                    <h1 class="scroll-m-20 font-unbounded text-4xl font-extrabold tracking-tight lg:text-5xl">
                        Good
                        {{
                            Number(new Date().toTimeString().slice(0, 2)) >= 6 && Number(new Date().toTimeString().slice(0, 2)) < 12
                                ? 'Morning'
                                : Number(new Date().toTimeString().slice(0, 2)) >= 12 && Number(new Date().toTimeString().slice(0, 2)) < 18
                                  ? 'Afternoon'
                                  : 'Night'
                        }}, <span class="text-secondary">{{ user.name }}</span>
                    </h1>
                    <p class="mb-4 font-dmsans text-xl text-muted-foreground">Selamat datang kembali di website resmi PEMIRA 2025</p>
                </div>
                <div
                    class="relative col-span-3 mb-4 flex items-center overflow-hidden rounded-lg border border-slate-200/70 bg-[#0047FF] p-8 md:px-12 md:py-16 dark:border-slate-800/70"
                >
                    <div class="absolute -top-15 -right-25 z-0">
                        <img src="/images/star.svg" class="h-full" alt="star" />
                    </div>
                    <div class="absolute right-72 bottom-0 z-0 md:right-72 md:-bottom-15">
                        <img src="/images/star.svg" class="h-full" alt="star" />
                    </div>
                    <div class="grid w-full items-center md:grid-cols-3">
                        <!-- text content -->
                        <div class="z-10 col-span-3 max-w-2xl space-y-6 text-center md:col-span-2 md:max-w-4xl md:text-left">
                            <h1 class="scroll-m-20 font-unbounded text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">
                                HEY, PEMIRA 2025
                            </h1>
                            <h2 class="mt-2 scroll-m-20 font-unbounded text-3xl font-extrabold tracking-tight sm:text-4xl lg:text-5xl">
                                <span class="text-[#DFFF00]">NOW ONLINE!</span>
                                <span class="text-[#FF00FF]">*</span>
                            </h2>
                            <p class="mt-6 font-dmsans text-base text-white/90 md:text-lg">
                                Tentukan pilihanmu secara langsung, terenkripsi dan aman tanpa keraguan
                            </p>
                        </div>

                        <!-- image -->
                        <div class="relative mr-10 hidden h-48 w-80 lg:block">
                            <div class="absolute inset-0 z-10 flex -rotate-6 transform items-center justify-center rounded-3xl bg-white shadow-2xl">
                                <img
                                    src="https://i.pinimg.com/736x/ac/ad/1d/acad1d464d090831b64890cae952e91e.jpg"
                                    alt="Placeholder Pemira 2025"
                                    class="aspect-3/2 rounded-2xl object-cover"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h1 class="scroll-m-20 font-unbounded text-2xl font-semibold tracking-tight">Dashboard</h1>

            <template v-if="statusVoting.open">
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

            <template v-else-if="statusVoting.doneVote">
                <VotingCta
                    variant="success"
                    title="You Have Voted"
                    desc="Terimakasih sudah memberikan suara"
                    icon="/images/done-voted.svg"
                    icon-alt="done-vote"
                />
            </template>

            <template v-else-if="statusVoting.ended">
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
