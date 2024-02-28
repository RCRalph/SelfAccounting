<template>
    <div
        class="d-flex justify-center align-center"
        style="height: calc(100vh - 64px - 2 * 12px)"
    >
        <v-row>
            <v-col
                cols="12"
                md="8"
                offset-md="2"
                lg="6"
                offset-lg="3"
                xl="4"
                offset-xl="4"
            >
                <v-card>
                    <v-form v-model="canSubmit">
                        <CardTitleWithButtons
                            title="Reset password"
                            large-font
                            extra-bottom
                        ></CardTitleWithButtons>

                        <v-card-text>
                            <v-row no-gutters>
                                <v-col cols="12" sm="6" offset-sm="3">
                                    <v-text-field
                                        v-model="email"
                                        :error-messages="emailValidation"
                                        variant="underlined"
                                        type="email"
                                        label="E-Mail address"
                                        counter="64"
                                        prepend-icon="mdi-email"
                                        @update:model-value="correctCredentials = true"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-card-text>

                        <CardActionsSubmitComponent
                            :loading="loading.submit"
                            :can-submit="canSubmit && !!email || success"
                            @submit="submit"
                        ></CardActionsSubmitComponent>
                    </v-form>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script setup lang="ts">
import axios from "axios"
import { computed, ref } from "vue"

import { useStatusStore } from "@stores/status"
import useComponentState from "@composables/useComponentState"
import Validator from "@classes/Validator"

import CardActionsSubmitComponent from "@components/global/card/CardActionsSubmitComponent.vue"
import CardTitleWithButtons from "@components/global/card/CardTitleWithButtonsComponent.vue"

const status = useStatusStore()

function useUserData() {
    const email = ref("")

    const success = ref(false)

    function submit() {
        loading.value.submit = true

        axios.post("/password/email", {
            email: email.value,
        })
            .then(() => {
                status.showSuccess("sent password reset link")
                loading.value.submit = false
                success.value = true
            })
            .catch(err => {
                if (
                    err.response.status == 422 &&
                    err.response.data.errors.email.includes("We can't find a user with that email address.")
                ) {
                    correctCredentials.value = false
                    loading.value.submit = false
                } else {
                    console.error(err)
                    setTimeout(() => status.showError(), 1000)
                    setTimeout(() => loading.value.submit = false, 2000)
                }
            })
    }

    return {email, success, submit}
}

function useValidation() {
    const correctCredentials = ref(true)

    const emailValidation = computed(() => {
        if (!correctCredentials.value) {
            return "We can't find a user with that e-mail address"
        }

        const validationMessage = Validator.email(true)(email.value)
        return typeof validationMessage == "string" ? validationMessage : undefined
    })

    return {correctCredentials, emailValidation}
}

const {canSubmit, loading} = useComponentState()
const {email, success, submit} = useUserData()
const {correctCredentials, emailValidation} = useValidation()
</script>
