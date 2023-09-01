<template>
    <v-card>
        <v-card-title>Edit {{ props.thing }}</v-card-title>

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
    thing: string
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