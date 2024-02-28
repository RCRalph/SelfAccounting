<template>
    <v-snackbar
        :model-value="status.success.value"
        color="success"
    >
        Successfully {{ status.success.info }}!

        <template v-slot:actions>
            <v-btn
                variant="text"
                @click="status.hideSuccess()"
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
