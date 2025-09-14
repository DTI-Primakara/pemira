<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

interface Props {
    ssoUri: string;
    clientId: string;
    redirectUri: string;
}

const props = defineProps<Props>();
</script>

<template>
    <nav class="fixed top-0 left-0 z-50 flex w-full items-center justify-center bg-white px-10 py-4">
        <div class="container flex w-full max-w-7xl items-center justify-between gap-4">
            <Link href="/" class="flex items-start justify-start">
                <img src="/images/logo-color.svg" alt="logo color pemira" class="h-12" />
            </Link>
            <div class="flex flex-row items-center justify-end gap-8">
                <div class="flex flex-row items-center justify-end gap-5">
                    <Link href="" class="font-dmsans text-base opacity-80 hover:opacity-100">Home</Link>
                    <Link href="" class="font-dmsans text-base opacity-80 hover:opacity-100">About</Link>
                    <Link href="" class="font-dmsans text-base opacity-80 hover:opacity-100">Candidates</Link>
                    <Link href="" class="font-dmsans text-base opacity-80 hover:opacity-100">Timeline</Link>
                    <Link href="" class="font-dmsans text-base opacity-80 hover:opacity-100">Contact</Link>
                </div>
                <a
                    :href="
                        props.ssoUri +
                        '/sso/oauth/authorize?client_id=' +
                        props.clientId +
                        '&redirect_uri=' +
                        props.redirectUri +
                        '&response_type=code&scope=*'
                    "
                >
                    <button class="cursor-pointer bg-[#4342FF] px-5 py-3 font-unbounded text-sm text-white hover:bg-[#1c1cad]">VOTE NOW</button>
                </a>
            </div>
        </div>
    </nav>
</template>
