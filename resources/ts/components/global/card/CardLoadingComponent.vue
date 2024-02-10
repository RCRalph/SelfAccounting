<template>
    <v-card :class="props.cardClass">
        <v-card-title
            v-if="props.title"
            :class="props.titleClass"
        >
            {{ props.title }}
        </v-card-title>

        <v-card-text class="card-loading">
            <v-progress-circular
                indeterminate
                size="96"
            ></v-progress-circular>
        </v-card-text>
    </v-card>
</template>

<script setup lang="ts">
const props = defineProps<{
    title?: string,
    cardClass?: string
    titleClass?: string
}>()
</script>

<style scoped lang="scss">
$padding: 20px;

.card-loading {
    display: flex;
    justify-content: center;
    align-items: center;
    height: #{96px + 2 * $padding};
    padding: $padding !important;
}
</style>