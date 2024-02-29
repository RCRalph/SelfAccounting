<template>
    <v-dialog
        v-model="dialog"
        max-width="400"
    >
        <template v-slot:activator="{ props }">
            <v-btn
                v-bind="props"
                variant="outlined"
                block
            >
                Password
            </v-btn>
        </template>

        <v-card>
            <CardTitleWithButtons title="Password update"></CardTitleWithButtons>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <v-row no-gutters>
                        <v-col cols="12">
                            <v-text-field
                                v-model="passwordData.current.value"
                                :error-messages="validateCurrentPassword"
                                :append-icon="!passwordData.current.show ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="passwordData.current.show ? 'text' : 'password'"
                                variant="underlined"
                                label="Current password"
                                counter
                                @click:append="passwordData.current.show = !passwordData.current.show"
                                @update:model-value="currentPasswordMatch = true"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12">
                            <v-text-field
                                v-model="passwordData.new.value"
                                :error-messages="validateNewPassword"
                                :append-icon="!passwordData.new.show ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="passwordData.new.show ? 'text' : 'password'"
                                variant="underlined"
                                label="New password"
                                counter
                                @click:append="passwordData.new.show = !passwordData.new.show"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12">
                            <v-text-field
                                v-model="passwordData.confirm.value"
                                :error-messages="validateConfirmPassword"
                                :append-icon="!passwordData.confirm.show ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="passwordData.confirm.show ? 'text' : 'password'"
                                variant="underlined"
                                label="Confirm password"
                                counter
                                @click:append="passwordData.confirm.show = !passwordData.confirm.show"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <CardActionsSubmitComponent
                :loading="loading.submit"
                :can-submit="
                    canSubmit &&
                    !!passwordData.current.value &&
                    !!passwordData.new.value &&
                    !!passwordData.confirm.value
                "
                @submit="update"
            ></CardActionsSubmitComponent>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { computed, ref } from "vue"

import CardTitleWithButtons from "@components/global/card/CardTitleWithButtonsComponent.vue"
import CardActionsSubmitComponent from "@components/global/card/CardActionsSubmitComponent.vue"

import type { UpdatePassword } from "@interfaces/User"
import { useStatusStore } from "@stores/status"
import useComponentState from "@composables/useComponentState"
import Validator from "@classes/Validator"

const status = useStatusStore()

function useInformation() {
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

    const currentPasswordMatch = ref(true)

    function resetFields() {
        currentPasswordMatch.value = true
        passwordData.value.current.value = ""
        passwordData.value.new.value = ""
        passwordData.value.confirm.value = ""
    }

    function update() {
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
                    err.response.data?.errors?.current_password?.includes("validation.current_password")
                ) {
                    currentPasswordMatch.value = false
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

function usePasswordValidation() {
    const validateCurrentPassword = computed(() => {
        if (!currentPasswordMatch.value) {
            return "Password doesn't match our records"
        }

        const validationMessage = Validator.password(true)(passwordData.value.current.value)
        return typeof validationMessage == "string" ? validationMessage : undefined
    })

    const validateNewPassword = computed(() => {
        if (passwordData.value.new.value && passwordData.value.current.value == passwordData.value.new.value) {
            return "New password can't be the same as old password"
        }

        const validationMessage = Validator.password(true)(passwordData.value.new.value)
        return typeof validationMessage == "string" ? validationMessage : undefined
    })

    const validateConfirmPassword = computed(() => {
        if (passwordData.value.new.value != passwordData.value.confirm.value) {
            return "Passwords don't match"
        }

        const validationMessage = Validator.password(true)(passwordData.value.confirm.value)
        return typeof validationMessage == "string" ? validationMessage : undefined
    })

    return {validateCurrentPassword, validateNewPassword, validateConfirmPassword}
}

const {canSubmit, dialog, loading} = useComponentState()
const {passwordData, currentPasswordMatch, update} = useInformation()
const {validateCurrentPassword, validateNewPassword, validateConfirmPassword} = usePasswordValidation()
</script>