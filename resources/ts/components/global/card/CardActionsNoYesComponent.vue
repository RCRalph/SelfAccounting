<template>
    <v-card-actions class="d-flex justify-space-around">
        <v-btn
            :disabled="props.loading"
            class="mx-1"
            color="error"
            variant="outlined"
            width="85"
            @click="emit('no')"
        >
            No
        </v-btn>

        <v-btn
            :disabled="props.loading"
            :loading="props.loading"
            color="success"
            variant="outlined"
            class="mx-1"
            width="85"
            @click="emit('yes')"
        >
            Yes
        </v-btn>
    </v-card-actions>
</template>

<script setup lang="ts">
const props = defineProps<{
    loading: boolean
}>()

const emit = defineEmits<{
    no: [],
    yes: []
}>()
</script>