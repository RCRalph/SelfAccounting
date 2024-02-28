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
                            :can-submit="canSubmit && !!password && !!confirm"
                            text="Reset password"
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
import Validator from "@classes/Validator"
import CardActionsSubmitComponent from "@components/global/card/CardActionsSubmitComponent.vue"
import { useRoute } from "vue-router"

const route = useRoute()
const status = useStatusStore()

function useUserData() {
    const password = ref("")

    const confirm = ref("")

    const showPassword = ref(false)

    function submit() {
        loading.value.submit = true

        axios.post("/password/reset", {
            password: password.value,
            password_confirm: confirm.value,
            token: route.params.token,
            email: route.query.email,
        })
            .then(() => window.location.href = "/app")
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.submit = false, 2000)
            })
    }

    return {password, confirm, showPassword, submit}
}

function useValidation() {
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

    return {passwordValidation, confirmValidation}
}

const {canSubmit, loading} = useComponentState()
const {password, confirm, showPassword, submit} = useUserData()
const {passwordValidation, confirmValidation} = useValidation()
</script>
