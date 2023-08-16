<template>
    <v-snackbar
        :model-value="status.error.value"
        color="error"
    >
        An error has occurred, please try again...

        <template v-slot:actions>
            <v-btn
                variant="text"
                @click="status.hideError()"
            >
                Close
            </v-btn>
        </template>
    </v-snackbar>
</template>

<script setup lang="ts">
import {useStatusStore} from "@stores/status"

const status = useStatusStore()
</script>
