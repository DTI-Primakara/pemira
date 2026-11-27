<script lang="ts" setup>
import type { TimelineEntry } from '@/types';
import { computed } from 'vue';
import TimelineItem from './TimelineItem.vue';

const props = withDefaults(defineProps<{ entries?: TimelineEntry[] }>(), { entries: () => [] });

/**
 * Parse a plain YYYY-MM-DD as a local day. `new Date('2026-01-10')` would be
 * read as UTC midnight, which lands on the previous day west of Greenwich.
 */
function parseDay(day: string): Date {
    const [year, month, date] = day.split('-').map(Number);

    return new Date(year, month - 1, date);
}

const agenda = computed(() =>
    props.entries.map((entry, index) => ({
        key: entry.id,
        order: index + 1,
        title: entry.title,
        start: parseDay(entry.starts_on),
        end: entry.ends_on ? parseDay(entry.ends_on) : null,
    })),
);
</script>

<template>
    <section v-if="agenda.length" class="relative mb-16 flex w-full items-center justify-center px-6 md:mb-30 md:px-10">
        <div class="flex w-full max-w-6xl flex-col items-center justify-center gap-4 md:gap-10">
            <!-- HEAD CONTENT -->
            <h2 data-anim="heading" class="relative w-full text-center font-unbounded text-2xl leading-[1.5em] font-bold text-black sm:text-3xl md:text-4xl lg:text-5xl">
                Linimasa
                <span class="text-blue">Kegiatan</span>
                <img
                    src="/images/asset26-1.svg"
                    alt=""
                    aria-hidden="true"
                    class="rotate-counter-clock absolute -top-10 right-0 h-12 w-12 rotate-24 sm:h-14 sm:w-14 md:h-16 md:w-16"
                />
            </h2>
            <!-- TIMELINEBOX -->
            <div data-anim="stagger" class="flex w-full max-w-xl flex-col items-start justify-start">
                <!-- ITEM TIMELINE -->
                <TimelineItem
                    v-for="(item, index) in agenda"
                    :key="item.key"
                    :order="item.order"
                    :title="item.title"
                    :date-start="item.start"
                    :date-end="item.end"
                />
            </div>
        </div>
    </section>
</template>
