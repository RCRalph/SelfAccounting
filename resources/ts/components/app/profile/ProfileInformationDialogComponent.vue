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
                Information
            </v-btn>
        </template>

        <v-card>
            <CardTitleWithButtons title="Profile information"></CardTitleWithButtons>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <v-row>
                        <v-col cols="12">
                            <v-text-field
                                v-model="userData.username"
                                :rules="[
                                    Validator.title('Username', 32)
                                ]"
                                variant="underlined"
                                label="Username"
                                counter="32"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12">
                            <v-text-field
                                v-model="userData.email"
                                :error-messages="emailValidation"
                                variant="underlined"
                                type="email"
                                label="E-Mail address"
                                counter="64"
                                @update:model-value="emailIsUnique = true"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <CardActionsResetUpdateComponent
                :loading="loading.submit"
                :can-submit="canSubmit"
                @reset="reset"
                @update="update"
            ></CardActionsResetUpdateComponent>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { computed, ref } from "vue"

import type { UserData } from "@interfaces/User"

import CardTitleWithButtons from "@components/global/card/CardTitleWithButtonsComponent.vue"
import CardActionsResetUpdateComponent from "@components/global/card/CardActionsResetUpdateComponent.vue"

import { useStatusStore } from "@stores/status"
import useComponentState from "@composables/useComponentState"
import Validator from "@classes/Validator"

const props = defineProps<{
    modelValue: UserData
}>()

const status = useStatusStore()

function useInformation() {
    const userData = ref({
        username: props.modelValue.username,
        email: props.modelValue.email,
    })

    const emailIsUnique = ref(true)

    const emailValidation = computed(() => {
        if (!emailIsUnique.value) {
            return "This E-Mail address has already been taken"
        }

        const validationMessage = Validator.email()(userData.value.email)
        return typeof validationMessage == "string" ? validationMessage : undefined
    })

    function reset() {
        userData.value.username = props.modelValue.username
        userData.value.email = props.modelValue.email
        emailIsUnique.value = true
    }

    function update() {
        loading.value.submit = true

        axios.post("/web-api/profile/information", userData.value)
            .then(() => {
                props.modelValue.username = userData.value.username
                props.modelValue.email = userData.value.email

                status.showSuccess("updated profile information")

                dialog.value = false
                loading.value.submit = false
            })
            .catch(err => {
                if (
                    err.response.status == 422 &&
                    err.response.data.errors.email.includes("The email has already been taken.")
                ) {
                    emailIsUnique.value = false
                    loading.value.submit = false
                } else {
                    console.error(err)
                    setTimeout(() => status.showError(), 1000)
                    setTimeout(() => loading.value.submit = false, 2000)
                }
            })
    }

    return {userData, emailIsUnique, emailValidation, reset, update}
}

const {canSubmit, dialog, loading} = useComponentState()
const {emailIsUnique, userData, emailValidation, reset, update} = useInformation()
</script>
