<template>
    <v-dialog
        v-model="dialog"
        width="unset"
    >
        <template v-slot:activator="{ props: buttonProps }">
            <v-btn
                v-bind="buttonProps"
                variant="outlined"
            >
                Convert to {{ target }}
            </v-btn>
        </template>

        <v-card>
            <CardTitleWithButtons :title="`Convert to ${target}`"></CardTitleWithButtons>

            <v-card-text class="text-h7 mx-5">
                Are you sure you want to convert this {{ source }} to {{ target }}?
            </v-card-text>

            <CardActionsNoYesComponent
                :loading="loading"
                @no="dialog = false"
                @yes="convert"
            ></CardActionsNoYesComponent>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref, computed } from "vue"
import { useStatusStore } from "@stores/status"

const props = defineProps<{
    type: string,
    id: number
}>()

const emit = defineEmits<{
    converted: []
}>()

const status = useStatusStore()

function useDialogSettings() {
    const dialog = ref(false)
    const loading = ref(false)

    const source = computed(() => props.type == "expenses" ? "expense" : "income")

    const target = computed(() => props.type == "expenses" ? "income" : "expense")

    function convert() {
        loading.value = true

        axios.post(`/web-api/${props.type}/${props.id}/convert`)
            .then(() => {
                status.showSuccess(`converted ${source.value} into ${target.value}`)
                emit("converted")
                dialog.value = false
                loading.value = false
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value = false, 2000)
            })
    }

    return {convert, dialog, loading, source, target}
}

const {convert, dialog, loading, source, target} = useDialogSettings()
</script>
