<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import LoginModal from '../sections/homepage/LoginModal.vue';
</script>

<template>
    <nav class="fixed top-0 left-0 z-50 flex w-full items-center justify-center bg-white px-6 py-4 md:px-10">
        <div class="container flex w-full max-w-7xl items-center justify-between gap-4">
            <Link href="/" class="flex items-start justify-start">
                <img src="/images/logo-color.svg" alt="logo color pemira" class="h-8 sm:h-10 md:h-12" />
            </Link>
            <div class="flex flex-row items-center justify-end gap-8">
                <div class="hidden flex-row items-center justify-end gap-5 md:flex">
                    <Link href="" class="font-dmsans text-base opacity-80 hover:opacity-100">Home</Link>
                    <Link href="" class="font-dmsans text-base opacity-80 hover:opacity-100">About</Link>
                    <Link href="" class="font-dmsans text-base opacity-80 hover:opacity-100">Candidates</Link>
                    <Link href="" class="font-dmsans text-base opacity-80 hover:opacity-100">Timeline</Link>
                    <Link href="" class="font-dmsans text-base opacity-80 hover:opacity-100">Contact</Link>
                </div>
                <LoginModal>
                    <template #trigger>
                        <button
                            class="flex cursor-pointer items-center justify-center gap-2 bg-[#4342FF] px-3 py-2 font-unbounded text-xs text-white hover:bg-[#1c1cad] sm:px-5 sm:py-3 md:text-sm"
                        >
                            VOTE NOW <img src="/images/arrow-white.svg" alt="arrow white icon" class="w-3" />
                        </button>
                    </template>
                </LoginModal>
            </div>
        </div>
    </nav>
</template>
