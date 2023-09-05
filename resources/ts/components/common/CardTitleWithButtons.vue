<template>
    <v-card-title :class="titleClasses">
        <slot></slot>
    </v-card-title>
</template>

<script setup lang="ts">
import { computed } from "vue"
import { useDisplay } from "vuetify"

const display = useDisplay()

const titleClasses = computed(() => {
    const result = ["d-flex", "pb-1", "px-6"]

    if (display.mobile.value) {
        result.push("flex-wrap", "flex-column", "justify-center", "align-center")
    } else {
        result.push("justify-space-between")
    }

    return result
})
</script>