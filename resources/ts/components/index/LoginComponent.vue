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
                            title="Login"
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

                                <v-col cols="12" sm="6" offset-sm="3">
                                    <v-text-field
                                        v-model="password"
                                        :error-messages="passwordValidation"
                                        :append-inner-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                        :type="showPassword ? 'text' : 'password'"
                                        variant="underlined"
                                        label="Password"
                                        prepend-icon="mdi-lock"
                                        counter
                                        @click:append-inner="showPassword = !showPassword"
                                        @update:model-value="correctCredentials = true"
                                    ></v-text-field>
                                </v-col>

                                <v-col
                                    cols="12"
                                    class="text-center"
                                >
                                    <router-link
                                        to="/password/reset"
                                        class="text-primary"
                                    >
                                        Forgot your password?
                                    </router-link>
                                </v-col>
                            </v-row>
                        </v-card-text>

                        <CardActionsLoginComponent
                            v-model:remember-me="rememberMe"
                            :loading="loading.submit"
                            :can-submit="canSubmit && !!email && !!password"
                            @login="submit"
                        ></CardActionsLoginComponent>
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
import CardTitleWithButtons from "@components/global/card/CardTitleWithButtonsComponent.vue"
import Validator from "@classes/Validator"
import CardActionsLoginComponent from "@components/global/card/CardActionsLoginComponent.vue"

const status = useStatusStore()

function useUserData() {
    const email = ref("")

    const password = ref("")

    const rememberMe = ref(false)

    const showPassword = ref(false)

    function submit() {
        loading.value.submit = true

        axios.post("/login", {
            email: email.value,
            password: password.value,
            remember: rememberMe.value,
        })
            .then(() => window.location.href = "/app" + window.location.hash)
            .catch(err => {
                if (
                    err.response.status == 422 &&
                    err.response.data.errors.email.includes("These credentials do not match our records.")
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

    return {email, password, rememberMe, showPassword, submit}
}

function useValidation() {
    const correctCredentials = ref(true)

    const emailValidation = computed(() => {
        if (!correctCredentials.value) {
            return "These credentials do not match our records"
        }

        const validationMessage = Validator.email(true)(email.value)
        return typeof validationMessage == "string" ? validationMessage : undefined
    })

    const passwordValidation = computed(() => {
        if (!correctCredentials.value) {
            return "These credentials do not match our records"
        }

        const validationMessage = Validator.password(true)(password.value)
        return typeof validationMessage == "string" ? validationMessage : undefined
    })

    return {correctCredentials, emailValidation, passwordValidation}
}

const {canSubmit, loading} = useComponentState()
const {email, password, rememberMe, showPassword, submit} = useUserData()
const {correctCredentials, emailValidation, passwordValidation} = useValidation()
</script>
