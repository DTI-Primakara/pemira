<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface Member {
    id: number;
    name: string;
    nim: string | null;
    has_voted: boolean;
}

const props = defineProps<{
    classroom: { id: number; name: string };
    members: Member[];
    summary: { total: number; voted: number; notVoted: number };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Voting', href: dashboard().url },
    { title: 'Koordinator', href: '/koordinator' },
];

const search = ref('');
const filter = ref<'all' | 'voted' | 'not-voted'>('all');

const percentage = computed(() => (props.summary.total === 0 ? 0 : Math.round((props.summary.voted / props.summary.total) * 100)));

const visibleMembers = computed(() => {
    const query = search.value.trim().toLowerCase();

    return props.members.filter((member) => {
        if (filter.value === 'voted' && !member.has_voted) return false;
        if (filter.value === 'not-voted' && member.has_voted) return false;
        if (!query) return true;
        return member.name.toLowerCase().includes(query) || (member.nim ?? '').includes(query);
    });
});

const filters: { key: typeof filter.value; label: string }[] = [
    { key: 'all', label: 'Semua' },
    { key: 'not-voted', label: 'Belum Memilih' },
    { key: 'voted', label: 'Sudah Memilih' },
];

/**
 * Feedback for the copy button, shown on the button itself. An alert() box
 * would be the one piece of raw browser chrome in an otherwise styled page.
 */
const copyState = ref<'idle' | 'copied' | 'failed'>('idle');
let copyTimer: ReturnType<typeof setTimeout> | undefined;

const copyLabel = computed(() => (copyState.value === 'copied' ? 'Tersalin!' : copyState.value === 'failed' ? 'Gagal menyalin' : 'Salin yang belum'));

const copyNotVoted = async () => {
    const names = props.members
        .filter((member) => !member.has_voted)
        .map((member) => `${member.nim ?? '-'} ${member.name}`)
        .join('\n');

    try {
        await navigator.clipboard.writeText(names);
        copyState.value = 'copied';
    } catch {
        copyState.value = 'failed';
    }

    clearTimeout(copyTimer);
    copyTimer = setTimeout(() => (copyState.value = 'idle'), 2500);
};
</script>

<template>
    <Head title="Koordinator" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-clip p-4 px-6 md:mx-8 md:p-4">
            <!-- HEAD -->
            <div class="mt-4 space-y-2">
                <p class="font-dmsans text-xs font-semibold tracking-[0.2em] text-[#2A29D6] uppercase">Koordinator Kelas</p>
                <h1 class="font-unbounded text-2xl leading-snug font-extrabold tracking-tight md:text-3xl lg:text-4xl">
                    Kelas <span class="text-gradient-main-26">{{ classroom.name }}</span>
                </h1>
                <p class="font-dmsans text-sm text-muted-foreground sm:text-base">
                    Pantau siapa yang sudah menggunakan hak suaranya, lalu ingatkan yang belum.
                </p>
            </div>

            <!-- PARTISIPASI -->
            <div class="relative flex w-full flex-col">
                <div class="relative z-10 overflow-hidden rounded-3xl bg-gradient-main-26 p-6 pb-16 sm:p-8 sm:pb-20 md:p-9 md:pb-20">
                    <img
                        src="/images/asset26-1.svg"
                        alt=""
                        aria-hidden="true"
                        class="rotate-counter-clock pointer-events-none absolute -top-8 -right-8 h-24 w-24 opacity-15 invert sm:h-32 sm:w-32"
                    />

                    <p class="font-dmsans text-xs text-white opacity-70 sm:text-sm">Partisipasi kelas</p>

                    <div class="mt-1 flex items-end gap-3">
                        <p class="font-unbounded text-5xl leading-none font-extrabold text-white sm:text-6xl">{{ percentage }}</p>
                        <span class="mb-1 font-unbounded text-xl font-bold text-[#DFFF00] sm:text-2xl">%</span>
                    </div>

                    <div class="mt-5 h-3 w-full overflow-hidden rounded-full bg-white/20">
                        <div class="h-full rounded-full bg-[#DFFF00] transition-all duration-700" :style="{ width: percentage + '%' }"></div>
                    </div>

                    <p class="mt-3 font-dmsans text-xs text-white opacity-70 sm:text-sm">
                        {{ summary.voted }} dari {{ summary.total }} anggota sudah memberikan suara.
                    </p>
                </div>

                <!-- Strip angka, menumpuk di bawah kartu gradient -->
                <div
                    class="relative z-20 mx-4 -mt-8 grid grid-cols-3 gap-2 rounded-2xl border border-border bg-card p-4 shadow-[0_24px_50px_-28px_rgba(1,0,120,0.45)] sm:mx-6 sm:gap-4 sm:p-5"
                >
                    <div class="flex flex-col items-center gap-1 text-center">
                        <p class="text-gradient-main-26 font-unbounded text-xl font-bold sm:text-3xl">{{ summary.total }}</p>
                        <p class="font-dmsans text-[10px] leading-tight text-muted-foreground sm:text-xs">Total anggota</p>
                    </div>
                    <div class="flex flex-col items-center gap-1 border-x border-border text-center">
                        <p class="font-unbounded text-xl font-bold text-emerald-600 sm:text-3xl">{{ summary.voted }}</p>
                        <p class="font-dmsans text-[10px] leading-tight text-muted-foreground sm:text-xs">Sudah memilih</p>
                    </div>
                    <div class="flex flex-col items-center gap-1 text-center">
                        <p class="font-unbounded text-xl font-bold text-amber-500 sm:text-3xl">{{ summary.notVoted }}</p>
                        <p class="font-dmsans text-[10px] leading-tight text-muted-foreground sm:text-xs">Belum memilih</p>
                    </div>
                </div>
            </div>

            <!-- KONTROL -->
            <div class="mt-2 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-wrap gap-2">
                    <button
                        v-for="option in filters"
                        :key="option.key"
                        type="button"
                        @click="filter = option.key"
                        :class="[
                            'cursor-pointer rounded-full px-4 py-1.5 font-dmsans text-sm transition-colors',
                            filter === option.key ? 'bg-gradient-main-26 font-semibold text-white' : 'bg-[#F1F1FF] text-[#2A29D6] hover:bg-[#E4E4FF]',
                        ]"
                    >
                        {{ option.label }}
                    </button>
                </div>

                <div class="flex gap-2">
                    <input
                        v-model="search"
                        type="search"
                        placeholder="Cari nama atau NIM..."
                        class="w-full rounded-xl border border-border bg-background px-4 py-2 font-dmsans text-sm text-foreground outline-none placeholder:text-muted-foreground focus:border-[#4342FF] sm:w-64"
                    />
                    <button
                        v-if="summary.notVoted > 0"
                        type="button"
                        @click="copyNotVoted"
                        :class="[
                            'shrink-0 cursor-pointer rounded-xl border px-4 py-2 font-dmsans text-sm transition-colors',
                            copyState === 'copied'
                                ? 'border-emerald-300 bg-emerald-50 text-emerald-700'
                                : copyState === 'failed'
                                  ? 'border-red-300 bg-red-50 text-red-700'
                                  : 'border-border hover:bg-muted',
                        ]"
                    >
                        {{ copyLabel }}
                    </button>
                </div>
            </div>

            <!-- DAFTAR ANGGOTA -->
            <div class="overflow-hidden rounded-3xl border border-border bg-card shadow-[0_24px_50px_-34px_rgba(1,0,120,0.35)]">
                <p v-if="visibleMembers.length === 0" class="px-6 py-12 text-center font-dmsans text-sm text-muted-foreground">
                    Tidak ada anggota yang cocok.
                </p>

                <ul v-else>
                    <li
                        v-for="(member, index) in visibleMembers"
                        :key="member.id"
                        class="flex items-center gap-4 px-5 py-4 not-last:border-b not-last:border-border sm:px-6"
                    >
                        <span
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#F1F1FF] font-unbounded text-xs font-bold text-[#2A29D6] sm:text-sm"
                        >
                            {{ String(index + 1).padStart(2, '0') }}
                        </span>

                        <div class="flex min-w-0 flex-col gap-0.5">
                            <p class="truncate font-unbounded text-xs font-bold text-[#11114E] sm:text-sm">{{ member.name }}</p>
                            <p class="font-dmsans text-xs text-muted-foreground">{{ member.nim ?? '—' }}</p>
                        </div>

                        <span
                            :class="[
                                'ml-auto inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1 font-dmsans text-xs font-medium',
                                member.has_voted ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700',
                            ]"
                        >
                            <span :class="['h-1.5 w-1.5 rounded-full', member.has_voted ? 'bg-emerald-600' : 'bg-amber-600']"></span>
                            {{ member.has_voted ? 'Sudah' : 'Belum' }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </AppLayout>
</template>
