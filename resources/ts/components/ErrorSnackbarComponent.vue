<template>
    <v-snackbar
        v-model="props.modelValue"
        color="error"
    >
        An error occurred, please try again...

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
const props = defineProps<{
    modelValue: boolean
}>()

const emit = defineEmits<{
    "update:modelValue": [payload: boolean]
}>()
</script>
