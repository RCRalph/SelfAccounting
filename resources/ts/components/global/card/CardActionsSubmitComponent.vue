<template>
    <v-card-actions class="d-flex justify-space-around">
        <v-btn
            :disabled="props.loading || !props.canSubmit"
            :loading="props.loading"
            :text="props.text ?? 'Submit'"
            color="success"
            variant="outlined"
            @click="emit('submit')"
        ></v-btn>
    </v-card-actions>
</template>

<script setup lang="ts">
const props = defineProps<{
    loading: boolean,
    canSubmit: boolean | null,
    text?: string
}>()

const emit = defineEmits<{
    submit: []
}>()
</script>