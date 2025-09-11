<script setup lang="ts">
import { Button } from "@/components/ui/button"
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog"
import { guide } from '@/routes';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <slot name="trigger"/>
        </DialogTrigger>
        <DialogContent class="sm:max-w-md space-y-2">
            <DialogHeader>
                <DialogTitle class="font-unbounded">Kamu Siap Memulai?</DialogTitle>
                <DialogDescription class="mt-2">
                    Pastikan kamu siap memilih. Pilihanmu bersifat final dan tidak dapat diubah lagi.
                </DialogDescription>
            </DialogHeader>
            <DialogFooter class="sm:justify-start">
                <DialogClose as-child>
                    <Button type="button" variant="outline" font="dmsans" rounded="lg">
                        Kembali
                    </Button>
                </DialogClose>
                <Link :href="guide().url">
                    <Button type="button" class="w-full" variant="default" font="dmsans" rounded="lg">
                        Ya, lanjutkan
                    </Button>
                </Link>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
