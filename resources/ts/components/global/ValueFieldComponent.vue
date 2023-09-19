<template>
    <div class="text-caption">
        Value
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

const props = defineProps<{
    value: number,
    iso: string
}>()

const formats = useFormats()
</script>