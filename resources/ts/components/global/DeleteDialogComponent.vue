<template>
    <v-dialog
        v-model="dialog"
        width="unset"
    >
        <template v-slot:activator="{props: dialogProps}: any">
            <v-tooltip location="bottom">
                <template v-slot:activator="{props: tooltipProps}: any">
                    <v-icon
                        v-bind="{ ...dialogProps, ...tooltipProps }"
                        class="mx-1 cursor-pointer"
                        icon="mdi-delete"
                    ></v-icon>
                </template>

                <span>
                    Delete {{ props.thing }}
                </span>
            </v-tooltip>
        </template>

        <v-card>
            <v-card-title class="d-flex justify-center pb-0">
                Delete {{ props.thing }}
            </v-card-title>

            <v-card-text class="text-h7 mx-5">
                Are you sure you want to delete this {{ props.thing }}?
            </v-card-text>

            <CardActionsNoYesComponent
                :loading="!!loading.submit"
                @no="dialog = false"
                @yes="remove"
            ></CardActionsNoYesComponent>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { useStatusStore } from "@stores/status"
import { useDialogSettings } from "@composables/useDialogSettings"

const props = defineProps<{
    url: string,
    thing: string
}>()

const emit = defineEmits<{
    deleted: []
}>()

const status = useStatusStore()
const {dialog, loading} = useDialogSettings()

function remove() {
    loading.value.submit = true

    axios.delete(`/web-api/${props.url}`)
        .then(() => {
            status.showSuccess(`deleted ${props.thing}`)
            emit("deleted")
            dialog.value = false
            loading.value.submit = false
        })
        .catch(err => {
            console.error(err)
            setTimeout(() => status.showError(), 1000)
            setTimeout(() => loading.value.submit = false, 2000)
        })
}
</script>
