<template>
    <div class="text-caption">
        {{ props.title }}
    </div>

    <div
        class="text-h6"
        style="overflow-x: hidden"
    >
        {{
            formats.numberWithCurrency(props.value, props.iso)
        }}
    </div>
</template>

<script setup lang="ts">
import useFormats from "@composables/useFormats"

const props = withDefaults(
    defineProps<{
        title?: string
        value: number,
        iso: string
    }>(),
    {
        title: "Value",
    },
)

const formats = useFormats()
</script>