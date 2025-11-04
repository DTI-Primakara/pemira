<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <slot name="trigger" />
        </DialogTrigger>
        <DialogContent class="space-y-2 sm:max-w-md">
            <DialogHeader>
                <DialogTitle class="font-unbounded">Login dengan Akun Siska</DialogTitle>
                <DialogDescription class="mt-2">
                    Proses login akan menggunakan email google yang terhubung dengan akun Siska kamu.
                </DialogDescription>
            </DialogHeader>
            <DialogFooter class="sm:justify-start">
                <DialogClose as-child>
                    <Button type="button" variant="outline" font="dmsans" rounded="lg"> Kembali </Button>
                </DialogClose>
                <a href="/auth/redirect">
                    <Button type="button" class="w-full" variant="default" font="dmsans" rounded="lg"> Ya, lanjutkan </Button>
                </a>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
