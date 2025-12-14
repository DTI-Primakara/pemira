import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
    iconUrl?: string;
}

export interface ConfigItem {
    title: string;
    value: string;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    config: ConfigItem[];
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    nim?: string | null;
    avatar?: string | null;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface Event {
    id: number;
    id_prodi?: number;
    title: string;
    type: string;
}

export interface Vote {
    id: number;
    candidate_id: number;
    event_id: number;
}

export interface Candidate {
    id: number;
    name_candidates: string;
    number: number;
    image: string;
    mision: string;
    vision: string;
    position: string;
    event: Event;
    votes: [Vote];
}

export interface CandidateProps {
    datas: {
        bem: [Candidate];
        dpm: [Candidate];
        hima_if: [Candidate];
        hima_si: [Candidate];
        hima_sia: [Candidate];
        hima_dkv: [Candidate];
        hima_manajemen: [Candidate];
        hima_akuntansi: [Candidate];
        hima_bd: [Candidate];
    };
}
