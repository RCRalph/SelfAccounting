<template>
    <v-card-actions class="d-flex justify-space-around">
        <v-btn
            :disabled="props.loading || !props.canSubmit"
            :loading="props.loading"
            color="success"
            variant="outlined"
            width="85"
            @click="emit('submit')"
        >
            Submit
        </v-btn>
    </v-card-actions>
</template>

<script setup lang="ts">
const props = defineProps<{
    loading: boolean,
    canSubmit: boolean
}>()

const emit = defineEmits<{
    submit: []
}>()
</script>