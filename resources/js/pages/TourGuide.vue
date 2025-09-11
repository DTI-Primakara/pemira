<script setup lang="ts">
import VoteLayout from '@/layouts/VoteLayout.vue';
import GuideContent from '@/components/sections/guide/GuideContent.vue';
import GuideRules from '@/components/sections/guide/GuideRules.vue';
</script>

<template>
    <VoteLayout>
        <GuideContent>
            <GuideRules/>
        </GuideContent>
    </VoteLayout>
</template>

<style scoped></style>
