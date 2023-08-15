<template>
    <v-snackbar
        v-model="props.modelValue"
        color="error"
    >
        An error has occurred, please try again...

        <template v-slot:actions>
            <v-btn
                variant="text"
                @click="emit('update:modelValue', false)"
            >
                Close
            </v-btn>
        </template>
    </v-snackbar>
</template>

<script setup lang="ts">
import {watch} from "vue"

const props = defineProps<{
    modelValue: boolean
}>()

const emit = defineEmits<{
    "update:modelValue": [payload: boolean]
}>()

watch(() => props.modelValue, () => {
    if (props.modelValue) {
        setTimeout(
            () => emit("update:modelValue", false),
            5000
        )
    }
})
</script>
