<template>
    <v-dialog
        v-model="dialog"
        max-width="400"
    >
        <template v-slot:activator="{ props: dialogProps }: any">
            <v-btn
                v-bind="dialogProps"
                variant="outlined"
                block
            >
                Settings
            </v-btn>
        </template>

        <v-card>
            <CardTitleWithButtons title="Profile settings"></CardTitleWithButtons>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <v-switch
                        class="d-flex justify-center"
                        v-model="userData.show_tutorials"
                        label="Show tutorials"
                    ></v-switch>
                </v-form>
            </v-card-text>

            <CardActionsResetUpdateComponent
                :loading="!!loading.submit"
                :can-submit="!!canSubmit"
                @reset="reset"
                @update="update"
            ></CardActionsResetUpdateComponent>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref } from "vue"

import type { UserData } from "@interfaces/User"

import CardTitleWithButtons from "@components/global/card/CardTitleWithButtonsComponent.vue"
import CardActionsResetUpdateComponent from "@components/global/card/CardActionsResetUpdateComponent.vue"

import { useDialogSettings } from "@composables/useDialogSettings"
import { useStatusStore } from "@stores/status"

const props = defineProps<{
    modelValue: UserData
}>()

const status = useStatusStore()

function useInformation() {
    const userData = ref({
        darkmode: props.modelValue.darkmode,
        show_tutorials: !props.modelValue.hide_all_tutorials,
    })

    function reset() {
        userData.value.darkmode = props.modelValue.darkmode
        userData.value.show_tutorials = !props.modelValue.hide_all_tutorials
    }

    function update() {
        loading.value.submit = true

        axios
            .post("/web-api/profile/settings", userData.value)
            .then(() => {
                props.modelValue.darkmode = userData.value.darkmode
                props.modelValue.hide_all_tutorials = !userData.value.show_tutorials

                status.showSuccess("updated profile settings")

                dialog.value = false
                loading.value.submit = false
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.submit = false, 2000)
            })
    }

    return {userData, reset, update}
}

const {canSubmit, dialog, loading} = useDialogSettings()
const {userData, reset, update} = useInformation()
</script>
