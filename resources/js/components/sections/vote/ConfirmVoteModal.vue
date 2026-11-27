<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';

defineProps<{
    open: boolean;
    submitting: boolean;
    /** What the voter picked, one line per election. */
    summary: { eventId: number; type: string; title: string; candidateNumber: number; candidateName: string }[];
}>();

const emit = defineEmits<{
    (e: 'update:open', value: boolean): void;
    (e: 'confirm'): void;
}>();
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="sm:max-w-lg">
            <DialogHeader>
                <DialogTitle class="font-unbounded">Kirim suara sekarang?</DialogTitle>
                <DialogDescription>
                    Periksa sekali lagi pilihanmu. Suara yang sudah dikirim tidak bisa diubah atau diulang.
                </DialogDescription>
            </DialogHeader>

            <ul class="flex flex-col gap-2">
                <li v-for="item in summary" :key="item.eventId" class="flex items-center justify-between gap-4 rounded-lg bg-[#F5F5FA] px-4 py-3">
                    <div class="flex flex-col">
                        <span class="font-unbounded text-xs font-bold text-[#11114E]">{{ item.type }}</span>
                        <span class="text-xs text-black opacity-60">{{ item.title }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-right">
                        <span class="font-unbounded text-lg font-bold text-blue">{{ String(item.candidateNumber).padStart(2, '0') }}</span>
                        <span class="max-w-[9rem] truncate text-xs font-semibold text-[#11114E]">{{ item.candidateName }}</span>
                    </div>
                </li>
            </ul>

            <DialogFooter class="gap-2 sm:justify-end">
                <Button type="button" variant="outline" font="dmsans" rounded="lg" :disabled="submitting" @click="emit('update:open', false)">
                    Periksa Lagi
                </Button>
                <Button type="button" variant="default" font="dmsans" rounded="lg" :disabled="submitting" @click="emit('confirm')">
                    {{ submitting ? 'Mengirim...' : 'Ya, Kirim Suara' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
