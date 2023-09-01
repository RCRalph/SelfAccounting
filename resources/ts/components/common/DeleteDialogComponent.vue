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
                :loading="loading"
                @no="dialog = false"
                @yes="remove"
            ></CardActionsNoYesComponent>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref } from "vue"
import { useStatusStore } from "@stores/status"
import CardActionsNoYesComponent from "@components/common/card-actions/CardActionsNoYesComponent.vue"

const props = defineProps<{
    url: string,
    thing: string
}>()

const emit = defineEmits<{
    deleted: []
}>()

const status = useStatusStore()

function useDialogSettings() {
    const dialog = ref(false)
    const loading = ref(false)

    function remove() {
        loading.value = true

        axios.delete(`/web-api/${props.url}`)
            .then(() => {
                status.showSuccess(`deleted ${props.thing}`)
                emit("deleted")
                dialog.value = false
                loading.value = false
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value = false, 2000)
            })
    }

    return {remove, dialog, loading}
}

const {remove, dialog, loading} = useDialogSettings()
</script>
