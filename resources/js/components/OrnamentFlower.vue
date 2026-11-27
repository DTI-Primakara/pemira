<script setup lang="ts">
/**
 * The four-petal PEMIRA 2026 mark, as plain vector paths.
 *
 * The same shape ships in public/images/asset26-*.svg, but each of those files
 * masks it over an embedded 1350×1688 PNG and weighs 2.1 MB — a quarter of a
 * second of a phone's data budget to draw a 96px decoration. Here it is a few
 * hundred bytes in the bundle, with no request at all.
 *
 * Colour comes from `currentColor`, so callers tint it with a text utility
 * (`text-white/20`) instead of filtering a bitmap.
 */
withDefaults(defineProps<{ title?: string }>(), { title: '' });
</script>

<template>
    <svg viewBox="0 0 96 96" fill="currentColor" xmlns="http://www.w3.org/2000/svg" :aria-hidden="title ? undefined : true" role="img">
        <title v-if="title">{{ title }}</title>
        <path d="M49.2692 0L0.0301112 0C0.0301112 0 -1.44526 23.4718 16.2587 37.4821C33.9627 51.4924 49.2692 48.7631 49.2692 48.7631L49.2692 0Z" />
        <path
            d="M46.6488 95.918L95.8879 95.918C95.8879 95.918 97.3632 72.4462 79.6593 58.4359C61.9553 44.4256 46.6488 47.1549 46.6488 47.1549L46.6488 95.918Z"
        />
        <path
            d="M95.9258 49.2731L95.9258 0.0340174C95.9258 0.0340174 72.454 -1.44136 58.4437 16.2626C44.4334 33.9666 47.1627 49.2731 47.1627 49.2731L95.9258 49.2731Z"
        />
        <path
            d="M0.00390646 46.6488L0.00390625 95.8879C0.00390625 95.8879 23.4757 97.3632 37.486 79.6593C51.4963 61.9553 48.767 46.6488 48.767 46.6488L0.00390646 46.6488Z"
        />
    </svg>
</template>
