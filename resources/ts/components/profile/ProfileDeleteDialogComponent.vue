<template>
    <v-dialog
        v-model="dialog"
        width="unset"
    >
        <template v-slot:activator="{ props }">
            <v-btn
                v-bind="props"
                variant="outlined"
                color="error"
                block
            >
                Delete account
            </v-btn>
        </template>

        <v-card>
            <CardTitleWithButtons title="Delete account"></CardTitleWithButtons>

            <v-card-text>
                <h3>Are you sure you want to delete your account?</h3>
            </v-card-text>

            <CardActionsNoYesComponent
                :loading="!!loading.submit"
                @yes="remove"
                @no="dialog = false"
            ></CardActionsNoYesComponent>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"

import CardActionsNoYesComponent from "@components/global/card/CardActionsNoYesComponent.vue"
import CardTitleWithButtons from "@components/global/card/CardTitleWithButtonsComponent.vue"

import { useDialogSettings } from "@composables/useDialogSettings"
import { useStatusStore } from "@stores/status"

const status = useStatusStore()

function remove() {
    loading.value.submit = true

    axios.delete("/web-api/profile")
        .then(() => {
            window.location.href = "/"

            dialog.value = false
            loading.value.submit = false
        })
        .catch(err => {
            console.error(err)
            setTimeout(() => status.hideError(), 1000)
            setTimeout(() => loading.value.submit = false, 2000)
        })
}

const {dialog, loading} = useDialogSettings()
</script>
