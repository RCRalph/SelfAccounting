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
                Password
            </v-btn>
        </template>

        <v-card>
            <CardTitleWithButtons title="Password update"></CardTitleWithButtons>

            <v-card-text>
                <v-form
                    v-model="canSubmit"
                    ref="$form"
                >
                    <v-row no-gutters>
                        <v-col cols="12">
                            <v-text-field
                                v-model="passwordData.current.value"
                                :rules="[
                                    Validator.password(),
                                    () => currentPasswordMatch || `Password doesn't match our records`,
                                ]"
                                :append-icon="!passwordData.current.show ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="passwordData.current.show ? 'text' : 'password'"
                                variant="underlined"
                                label="Current password"
                                counter
                                @click:append="passwordData.current.show = !passwordData.current.show"
                                @update:model-value="currentPasswordMatch = true"
                                @blur="$form?.validate"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12">
                            <v-text-field
                                v-model="passwordData.new.value"
                                :rules="[
                                    Validator.password(),
                                    password => password != passwordData.current.value || `New password can't be the same as old password`
                                ]"
                                :append-icon="!passwordData.new.show ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="passwordData.new.show ? 'text' : 'password'"
                                variant="underlined"
                                label="New password"
                                counter
                                @click:append="passwordData.new.show = !passwordData.new.show"
                                @blur="$form?.validate"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12">
                            <v-text-field
                                v-model="passwordData.confirm.value"
                                :rules="[
                                    Validator.password(),
                                    password => password == passwordData.new.value || `Passwords don't match`
                                ]"
                                :append-icon="!passwordData.confirm.show ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="passwordData.confirm.show ? 'text' : 'password'"
                                variant="underlined"
                                label="Confirm password"
                                counter
                                @click:append="passwordData.confirm.show = !passwordData.confirm.show"
                                @blur="$form?.validate"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <CardActionsSubmitComponent
                :loading="!!loading.submit"
                :can-submit="!!canSubmit"
                @submit="update"
            ></CardActionsSubmitComponent>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref } from "vue"
import type { VForm } from "vuetify/components"

import CardTitleWithButtons from "@components/global/card/CardTitleWithButtonsComponent.vue"
import CardActionsSubmitComponent from "@components/global/card/CardActionsSubmitComponent.vue"

import type { UpdatePassword } from "@interfaces/User"
import { useStatusStore } from "@stores/status"
import { useDialogSettings } from "@composables/useDialogSettings"
import Validator from "@classes/Validator"

const $form = ref<VForm | null>(null)

const status = useStatusStore()

function useInformation() {
    const currentPasswordMatch = ref(true)

    const passwordData = ref<UpdatePassword>({
        current: {
            value: "",
            show: false,
        },
        new: {
            value: "",
            show: false,
        },
        confirm: {
            value: "",
            show: false,
        },
    })

    function resetFields() {
        passwordData.value.current.value = ""
        passwordData.value.new.value = ""
        passwordData.value.confirm.value = ""
    }

    async function update() {
        await $form.value?.validate()
        if (!canSubmit.value) return

        loading.value.submit = true

        axios.post("/web-api/profile/password", {
            current_password: passwordData.value.current.value,
            new_password: passwordData.value.new.value,
            new_password_confirmation: passwordData.value.confirm.value,
        })
            .then(() => {
                resetFields()
                status.showSuccess("updated password")
                dialog.value = false
                loading.value.submit = false
            })
            .catch(err => {
                if (
                    err.response.status == 422 &&
                    err.response.data.errors?.current_password?.includes("validation.current_password")
                ) {
                    currentPasswordMatch.value = false
                    $form.value?.validate()
                    loading.value.submit = false
                } else {
                    console.error(err)
                    setTimeout(() => status.showError(), 1000)
                    setTimeout(() => loading.value.submit = false, 2000)
                }
            })
    }

    return {passwordData, currentPasswordMatch, update}
}

const {canSubmit, dialog, loading} = useDialogSettings()
const {passwordData, currentPasswordMatch, update} = useInformation()
</script>