<template>
    <v-card-actions class="d-flex justify-space-between">
        <v-checkbox
            v-model="rememberMe"
            label="Remember me"
            hide-details
        ></v-checkbox>

        <v-btn
            :disabled="props.loading || !props.canSubmit"
            :loading="props.loading"
            color="success"
            variant="outlined"
            width="85"
            class="mr-4"
            text="Login"
            @click="emit('login')"
        ></v-btn>
    </v-card-actions>
</template>

<script setup lang="ts">
const rememberMe = defineModel<boolean>("rememberMe", {default: false, required: true})

const props = defineProps<{
    loading: boolean,
    canSubmit: boolean | null
}>()

const emit = defineEmits<{
    login: []
}>()
</script>