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

const copyNotVoted = async () => {
    const names = props.members
        .filter((member) => !member.has_voted)
        .map((member) => `${member.nim ?? '-'} ${member.name}`)
        .join('\n');

    try {
        await navigator.clipboard.writeText(names);
        alert('Daftar yang belum memilih sudah disalin.');
    } catch {
        alert('Gagal menyalin. Salin manual dari daftar di bawah.');
    }
};
</script>

<template>
    <Head title="Koordinator" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4 px-6 md:mx-8 md:p-4">
            <div class="mt-4 space-y-2">
                <h1 class="font-unbounded text-2xl font-extrabold tracking-tight md:text-3xl lg:text-4xl">
                    Kelas <span class="text-secondary">{{ classroom.name }}</span>
                </h1>
                <p class="font-dmsans text-sm text-muted-foreground sm:text-base">
                    Pantau siapa yang sudah menggunakan hak suaranya, lalu ingatkan yang belum.
                </p>
            </div>

            <!-- SUMMARY -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-lg border border-slate-200/70 p-5 dark:border-slate-800/70">
                    <p class="font-dmsans text-sm text-muted-foreground">Total Anggota</p>
                    <p class="mt-1 font-unbounded text-3xl font-extrabold">{{ summary.total }}</p>
                </div>
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900/50 dark:bg-emerald-950/30">
                    <p class="font-dmsans text-sm text-emerald-700 dark:text-emerald-400">Sudah Memilih</p>
                    <p class="mt-1 font-unbounded text-3xl font-extrabold text-emerald-700 dark:text-emerald-400">{{ summary.voted }}</p>
                </div>
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-5 dark:border-amber-900/50 dark:bg-amber-950/30">
                    <p class="font-dmsans text-sm text-amber-700 dark:text-amber-400">Belum Memilih</p>
                    <p class="mt-1 font-unbounded text-3xl font-extrabold text-amber-700 dark:text-amber-400">{{ summary.notVoted }}</p>
                </div>
            </div>

            <!-- PROGRESS -->
            <div class="space-y-2">
                <div class="flex items-center justify-between font-dmsans text-sm">
                    <span class="text-muted-foreground">Partisipasi kelas</span>
                    <span class="font-semibold">{{ percentage }}%</span>
                </div>
                <div class="h-3 w-full overflow-hidden rounded-full bg-slate-200 dark:bg-slate-800">
                    <div class="h-full rounded-full bg-[#4342FF] transition-all duration-500" :style="{ width: percentage + '%' }"></div>
                </div>
            </div>

            <!-- CONTROLS -->
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-wrap gap-2">
                    <button
                        v-for="option in filters"
                        :key="option.key"
                        @click="filter = option.key"
                        :class="[
                            'rounded-full px-4 py-1.5 font-dmsans text-sm transition-colors',
                            filter === option.key
                                ? 'bg-[#4342FF] text-white'
                                : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300',
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
                        class="w-full rounded-md border border-slate-200 px-3 py-1.5 font-dmsans text-sm outline-none focus:border-[#4342FF] sm:w-64 dark:border-slate-700 dark:bg-slate-900"
                    />
                    <button
                        v-if="summary.notVoted > 0"
                        @click="copyNotVoted"
                        class="shrink-0 rounded-md border border-slate-200 px-3 py-1.5 font-dmsans text-sm hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800"
                    >
                        Salin yang belum
                    </button>
                </div>
            </div>

            <!-- LIST -->
            <div class="overflow-hidden rounded-lg border border-slate-200/70 dark:border-slate-800/70">
                <table class="w-full text-left font-dmsans text-sm">
                    <thead class="bg-slate-50 text-xs text-muted-foreground uppercase dark:bg-slate-900">
                        <tr>
                            <th class="px-4 py-3">NIM</th>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="visibleMembers.length === 0">
                            <td colspan="3" class="px-4 py-8 text-center text-muted-foreground">Tidak ada anggota yang cocok.</td>
                        </tr>
                        <tr v-for="member in visibleMembers" :key="member.id" class="border-t border-slate-100 dark:border-slate-800">
                            <td class="px-4 py-3 font-mono text-xs">{{ member.nim ?? '-' }}</td>
                            <td class="px-4 py-3">{{ member.name }}</td>
                            <td class="px-4 py-3 text-right">
                                <span
                                    :class="[
                                        'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium',
                                        member.has_voted
                                            ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400'
                                            : 'bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-400',
                                    ]"
                                >
                                    <span :class="['h-1.5 w-1.5 rounded-full', member.has_voted ? 'bg-emerald-600' : 'bg-amber-600']"></span>
                                    {{ member.has_voted ? 'Sudah' : 'Belum' }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
