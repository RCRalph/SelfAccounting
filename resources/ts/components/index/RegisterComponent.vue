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
                            title="Register"
                            large-font
                            extra-bottom
                        ></CardTitleWithButtons>

                        <v-card-text>
                            <v-row no-gutters>
                                <v-col cols="12" sm="6" offset-sm="3">
                                    <v-text-field
                                        v-model="username"
                                        :rules="[Validator.title('Username', 32, true)]"
                                        variant="underlined"
                                        label="Username"
                                        counter="64"
                                        prepend-icon="mdi-account"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12" sm="6" offset-sm="3">
                                    <v-text-field
                                        v-model="email"
                                        :error-messages="emailValidation"
                                        variant="underlined"
                                        type="email"
                                        label="E-Mail address"
                                        counter="64"
                                        prepend-icon="mdi-email"
                                        @update:model-value="emailUnique = true"
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
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12" sm="6" offset-sm="3">
                                    <v-text-field
                                        v-model="confirm"
                                        :error-messages="confirmValidation"
                                        :append-inner-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                        :type="showPassword ? 'text' : 'password'"
                                        variant="underlined"
                                        label="Confirm password"
                                        prepend-icon="mdi-lock"
                                        counter
                                        @click:append-inner="showPassword = !showPassword"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-card-text>

                        <CardActionsSubmitComponent
                            :loading="loading.submit"
                            :can-submit="canSubmit && !!username && !!email && !!password && !!confirm"
                            text="Register"
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
import CardTitleWithButtons from "@components/global/card/CardTitleWithButtonsComponent.vue"
import CardActionsSubmitComponent from "@components/global/card/CardActionsSubmitComponent.vue"
import Validator from "@classes/Validator"

const status = useStatusStore()

function useUserData() {
    const username = ref("")

    const email = ref("")

    const password = ref("")

    const confirm = ref("")

    const showPassword = ref(false)

    function submit() {
        loading.value.submit = true

        axios.post("/register", {
            username: username.value,
            email: email.value,
            password: password.value,
            password_confirmation: confirm.value,
        })
            .then(() => window.location.href = "/app")
            .catch(err => {
                if (
                    err.response.status == 422 &&
                    err.response.data.errors.email.includes("The email has already been taken.")
                ) {
                    emailUnique.value = false
                    loading.value.submit = false
                } else {
                    console.error(err)
                    setTimeout(() => status.showError(), 1000)
                    setTimeout(() => loading.value.submit = false, 2000)
                }
            })
    }

    return {username, email, password, confirm, showPassword, submit}
}

function useValidation() {
    const emailUnique = ref(true)

    const emailValidation = computed(() => {
        if (!emailUnique.value) {
            return "The email has already been taken"
        }

        const validationMessage = Validator.email(true)(email.value)
        return typeof validationMessage == "string" ? validationMessage : undefined
    })

    const passwordValidation = computed(() => {
        if (password.value != confirm.value) {
            return "Passwords don't match"
        }

        const validationMessage = Validator.password(true)(password.value)
        return typeof validationMessage == "string" ? validationMessage : undefined
    })

    const confirmValidation = computed(() => {
        if (password.value != confirm.value) {
            return "Passwords don't match"
        }

        const validationMessage = Validator.password(true)(confirm.value)
        return typeof validationMessage == "string" ? validationMessage : undefined
    })

    return {emailUnique, emailValidation, passwordValidation, confirmValidation}
}

const {canSubmit, loading} = useComponentState()
const {username, email, password, confirm, showPassword, submit} = useUserData()
const {emailUnique, emailValidation, passwordValidation, confirmValidation} = useValidation()
</script>
