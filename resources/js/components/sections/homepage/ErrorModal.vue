<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';

const props = defineProps({
    open: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(['update:open']);
</script>

<template>
    <Dialog :open="props.open">
        <DialogContent class="space-y-2 sm:max-w-md">
            <DialogHeader>
                <DialogTitle class="font-unbounded">Akun Tidak Terdaftar</DialogTitle>
                <DialogDescription class="mt-2">
                    Maaf, akun kamu belum terdaftar dalam sistem Pemira 2025. Silakan hubungi administrator untuk informasi lebih lanjut.
                </DialogDescription>
            </DialogHeader>
            <DialogFooter class="sm:justify-start">
                <DialogClose as-child @click="emit('update:open', false)">
                    <Button type="button" variant="outline" font="dmsans" rounded="lg"> Kembali </Button>
                </DialogClose>
                <a
                    href="https://wa.me/62895622771393?text=Halo%20kak%2C%20bisa%20tolong%20dibantu%20untuk%20aktivasi%20akun%20Pemira%20saya%3F
"
                >
                    <Button type="button" class="w-full" variant="default" font="dmsans" rounded="lg"> Hubungi Admin </Button>
                </a>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
