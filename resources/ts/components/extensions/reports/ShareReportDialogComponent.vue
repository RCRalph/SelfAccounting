<template>
    <v-dialog
        v-model="dialog"
        max-width="500"
        persistent
    >
        <template v-slot:activator="{ props: dialogProps }">
            <v-btn
                v-bind="dialogProps"
                variant="outlined"
                text="Share"
            ></v-btn>
        </template>

        <v-card>
            <CardTitleWithButtons title="Share"></CardTitleWithButtons>

            <v-card-text
                v-if="model.length"
                class="d-flex justify-center flex-wrap"
            >
                <v-chip
                    v-for="item in model"
                    :prepend-avatar="item.profile_picture_link"
                    :text="item.username"
                    size="x-large"
                    variant="outlined"
                    class="large-chip-avatar"
                    closable
                    @click:close="removeUser(item.email)"
                ></v-chip>
            </v-card-text>

            <v-card-text
                v-else
                class="text-center text-h6 pb-0"
            >
                Add a user by entering an e-mail&nbsp;address&nbsp;below
            </v-card-text>

            <v-card-text>
                <v-row>
                    <v-col
                        cols="12"
                        sm="6"
                        offset-sm="2"
                    >
                        <v-form
                            v-model="canSubmit"
                            ref="$form"
                        >
                            <v-text-field
                                v-model="email"
                                :rules="[
                                    Validator.email(),
                                    () => !emailExists || `This user doesn't exist`,
                                    value => !model.map(item => item.email).includes(value) || `This user is already added`
                                ]"
                                type="email"
                                label="E-Mail address"
                                counter="64"
                                variant="underlined"
                                @update:model-value="emailExists = false"
                            ></v-text-field>
                        </v-form>
                    </v-col>

                    <v-col
                        cols="12"
                        sm="4"
                    >
                        <div
                            class="d-flex justify-sm-start justify-center align-center mx-3 my-1"
                            style="height: 100%"
                        >
                            <v-btn
                                :disabled="!canSubmit"
                                :loading="loading.submit"
                                variant="outlined"
                                text="Add"
                                @click="addUser"
                            ></v-btn>
                        </div>
                    </v-col>
                </v-row>
            </v-card-text>

            <CardActionsSubmitComponent
                :loading="loading.submit"
                :can-submit="true"
                @submit="update"
            ></CardActionsSubmitComponent>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref } from "vue"

import type { ReportUser } from "@interfaces/Reports"

import useComponentState from "@composables/useComponentState"
import Validator from "@classes/Validator"
import { VForm } from "vuetify/components"
import { useStatusStore } from "@stores/status"

const model = defineModel<ReportUser[]>({default: []})

const status = useStatusStore()

const $form = ref<VForm>()

function useUsers() {
    const email = ref<string>("")

    const emailExists = ref(false)

    function addUser() {
        loading.value.submit = true

        axios.post("/web-api/extensions/reports/user-info", {email: email.value})
            .then(response => {
                const data = response.data

                $form.value?.reset()
                model.value.push(data.user)

                loading.value.submit = false
            })
            .catch(async err => {
                if (
                    err.response.status == 422 &&
                    err.response.data.errors.email.includes("The selected email is invalid.")
                ) {
                    emailExists.value = true
                    await $form.value?.validate()
                    loading.value.submit = false
                } else {
                    console.error(err)
                    setTimeout(() => status.showError(), 1000)
                    setTimeout(() => loading.value.submit = false, 2000)
                }
            })
    }

    function removeUser(email: string) {
        model.value = model.value.filter(item => item.email != email)
    }

    function update() {
        dialog.value = false
    }

    return {email, emailExists, addUser, removeUser, update}
}

const {dialog, loading, canSubmit} = useComponentState()
const {email, emailExists, addUser, removeUser, update} = useUsers()
</script>
