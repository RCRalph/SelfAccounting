<template>
    <v-dialog
        v-model="dialog"
        max-width="400"
    >
        <template v-slot:activator="{ props: buttonProps }">
            <v-btn
                v-bind="buttonProps"
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

import useComponentState from "@composables/useComponentState"
import { useStatusStore } from "@stores/status"
import { useUserStore } from "@stores/user"

const props = defineProps<{
    modelValue: UserData
}>()

const user = useUserStore()
const status = useStatusStore()

function useInformation() {
    const userData = ref({
        show_tutorials: !props.modelValue.hide_all_tutorials,
    })

    function reset() {
        userData.value.show_tutorials = !props.modelValue.hide_all_tutorials
    }

    function update() {
        loading.value.submit = true

        axios
            .post("/web-api/profile/settings", userData.value)
            .then(() => {
                props.modelValue.hide_all_tutorials = !userData.value.show_tutorials
                user.updateTutorials(props.modelValue.hide_all_tutorials)

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

const {canSubmit, dialog, loading} = useComponentState()
const {userData, reset, update} = useInformation()
</script>
