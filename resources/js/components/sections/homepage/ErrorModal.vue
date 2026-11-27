<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { CONTACT_ADMIN_ACCOUNT_URL } from '@/lib/contact';

const props = withDefaults(
    defineProps<{
        open: boolean;
        /**
         * The reason the login failed, as the server flashed it. Logins no
         * longer fail merely because an account is unknown — an unregistered
         * student signs in fine and is told on the dashboard — so this has to
         * show the real message instead of one fixed sentence.
         */
        message?: string | null;
    }>(),
    { message: null },
);

const emit = defineEmits(['update:open']);
</script>

<template>
    <Dialog :open="props.open">
        <DialogContent class="space-y-2 sm:max-w-md">
            <DialogHeader>
                <DialogTitle class="font-unbounded">Login Gagal</DialogTitle>
                <DialogDescription class="mt-2">
                    {{ props.message ?? 'Terjadi kesalahan saat login. Silakan coba lagi atau hubungi administrator.' }}
                </DialogDescription>
            </DialogHeader>
            <DialogFooter class="sm:justify-start">
                <DialogClose as-child @click="emit('update:open', false)">
                    <Button type="button" variant="outline" font="dmsans" rounded="lg"> Kembali </Button>
                </DialogClose>
                <a :href="CONTACT_ADMIN_ACCOUNT_URL" target="_blank" rel="noopener noreferrer">
                    <Button type="button" class="w-full" variant="default" font="dmsans" rounded="lg"> Hubungi Admin </Button>
                </a>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
