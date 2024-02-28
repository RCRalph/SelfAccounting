<template>
    <v-card-title :class="titleCardClasses">
        <div :class="titleClasses">
            {{ props.title }}
        </div>

        <div class="multi-button-table-top">
            <slot></slot>
        </div>
    </v-card-title>
</template>

<script setup lang="ts">
import { computed, useSlots } from "vue"
import { useDisplay } from "vuetify"

const props = defineProps<{
    title: string,
    largeFont?: boolean,
    extraBottom?: boolean,
    noHorizontal?: boolean
}>()

const display = useDisplay()
const slots = useSlots()

const titleCardClasses = computed(() => {
    const result = [
        "d-flex",
        `px-${props.noHorizontal ? 0 : 4}`,
    ]

    if (!slots.default) {
        result.push("justify-center")
    } else if (display.mobile.value) {
        result.push("flex-wrap", "flex-column", "justify-center", "align-center")
    } else {
        result.push("justify-space-between")
    }

    if (!props.extraBottom) {
        result.push("pb-1")
    }

    return result
})

const titleClasses = computed(() => {
    const result = [
        "text-capitalize",
        display.mobile.value ? "mb-1" : "mb-0",
    ]

    if (props.largeFont) {
        result.push("text-h5")
    }

    return result
})
</script>