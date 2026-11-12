import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
    isKorti: boolean;
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

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
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
    id_prodi?: string | null;
    title: string;
    type: string;
}

export interface Candidate {
    id: number;
    name_candidates: string;
    number: number;
    image: string;
    mission: string;
    vision: string;
    position: string;
    event?: Event;
}

/** A ballot step as served to the voting wizard. */
export interface BallotEvent extends Event {
    candidates: Candidate[];
}

/** Public tally: aggregate counts only, never individual vote rows. */
export interface TallyCandidate {
    id: number;
    name_candidates: string;
    number: number;
    image: string;
    votes_count: number;
}

export interface TallyEvent {
    id: number;
    title: string;
    type: string;
    candidates: TallyCandidate[];
}

export interface TallyGroup {
    key: string;
    title: string | null;
    subtitle: string | null;
    events: TallyEvent[];
}

export type HomepageProps = AppPageProps<{
    groups: TallyGroup[];
    voteCountVisible: boolean;
}>;
