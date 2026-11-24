<script setup lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { SidebarMenu, SidebarMenuButton, SidebarMenuItem, useSidebar } from '@/components/ui/sidebar';
import { User } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { ChevronsUpDown } from 'lucide-vue-next';
import { computed } from 'vue';
import UserMenuContent from './UserMenuContent.vue';

const page = usePage();

/*
 * The signed-in user comes from the shared `auth` prop, which every response
 * carries. Reading a page-level `user` prop instead meant the sidebar only
 * worked on the one page that happened to send one, and crashed the whole app
 * everywhere else — a blank white screen, because a render error in the layout
 * takes the entire Vue tree down with it.
 *
 * Kept as a computed rather than unwrapped here: `.value` at setup time freezes
 * the value, so the name would go stale after a profile update.
 */
const user = computed(() => page.props.auth?.user as User | undefined);
const { isMobile, state } = useSidebar();
</script>

<template>
    <SidebarMenu v-if="user">
        <SidebarMenuItem>
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <SidebarMenuButton size="lg" class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground">
                        <UserInfo :user="user" />
                        <ChevronsUpDown class="ml-auto size-4" />
                    </SidebarMenuButton>
                </DropdownMenuTrigger>
                <DropdownMenuContent
                    class="w-(--reka-dropdown-menu-trigger-width) min-w-56 rounded-lg"
                    :side="isMobile ? 'bottom' : state === 'collapsed' ? 'left' : 'bottom'"
                    align="end"
                    :side-offset="4"
                >
                    <UserMenuContent :user="user" />
                </DropdownMenuContent>
            </DropdownMenu>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
