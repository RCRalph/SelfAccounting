<template>
    <v-dialog
        v-model="dialog"
        max-width="700"
    >
        <template v-slot:activator="{ props: dialogProps }">
            <v-tooltip
                text="Edit account"
                location="bottom"
            >
                <template v-slot:activator="{ props: tooltipProps }">
                    <v-icon
                        v-bind="{ ...dialogProps, ...tooltipProps }"
                        class="mx-1 cursor-pointer"
                        icon="mdi-pencil"
                    ></v-icon>
                </template>
            </v-tooltip>
        </template>

        <v-card v-if="ready && accountData">
            <CardTitleWithButtons title="Edit Account"></CardTitleWithButtons>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <v-row>
                        <v-col cols="12" sm="4" class="d-flex justify-center align-center">
                            <IconPickerComponent
                                v-model="accountData.icon"
                                type="accounts"
                            ></IconPickerComponent>
                        </v-col>

                        <v-col cols="12" sm="8">
                            <v-text-field
                                v-model="accountData.name"
                                :rules="[
                                    Validator.title('Name', 32)
                                ]"
                                variant="underlined"
                                label="Name"
                                counter="32"
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="6" sm="4">
                            <v-switch
                                v-model="accountData.used_in_income"
                                label="Show in income"
                            ></v-switch>
                        </v-col>

                        <v-col cols="6" sm="4">
                            <v-switch
                                v-model="accountData.used_in_expenses"
                                label="Show in expenses"
                            ></v-switch>
                        </v-col>

                        <v-col cols="6" sm="4">
                            <v-switch
                                v-model="accountData.show_on_charts"
                                label="Show on charts"
                            ></v-switch>
                        </v-col>

                        <v-col cols="6" sm="4">
                            <v-switch
                                v-model="accountData.count_to_summary"
                                label="Count to summary"
                            ></v-switch>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <v-text-field
                                v-model="accountData.start_date"
                                :max="accountData.max_date"
                                :rules="[
                                    Validator.date(false)
                                ]"
                                type="date"
                                label="Start date"
                                min="1970-01-01"
                                variant="underlined"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <v-text-field
                                v-model="accountData.start_balance"
                                :rules="[
                                    Validator.price(
                                        false,
                                        true,
                                        true
                                    )
                                ]"
                                label="Start balance"
                                variant="underlined"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <CardActionsResetUpdateComponent
                :loading="!!loading.submit"
                :can-submit="!!canSubmit"
                @reset="reset"
                @update="update"
            ></CardActionsResetUpdateComponent>
        </v-card>

        <CardLoadingComponent
            v-else
            title="Edit account"
        ></CardLoadingComponent>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref, watch } from "vue"
import { cloneDeep } from "lodash"

import type { Account } from "@interfaces/Account"

import IconPickerComponent from "@components/icon-picker/IconPickerComponent.vue"

import { useStatusStore } from "@stores/status"
import useComponentState from "@composables/useComponentState"
import Validator from "@classes/Validator"

const props = defineProps<{
    id: number
}>()

const emit = defineEmits<{
    updated: []
}>()

const status = useStatusStore()

function useData() {
    const accountData = ref<Account>()
    const accountDataCopy = ref<Account>()

    function getData() {
        if (!dialog.value) return

        ready.value = false

        axios.get(`/web-api/accounts/account/${props.id}`)
            .then(response => {
                const data = response.data

                accountData.value = data.data
                accountDataCopy.value = cloneDeep(data.data)

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
            })
    }

    return {accountData, accountDataCopy, getData}
}

function useActions() {
    function reset() {
        accountData.value = cloneDeep(accountDataCopy.value)
    }

    function update() {
        loading.value.submit = true

        axios.patch(`/web-api/accounts/account/${props.id}`, accountData.value)
            .then(() => {
                emit("updated")
                status.showSuccess("updated account")
                accountDataCopy.value = cloneDeep(accountData.value)
                dialog.value = false
                loading.value.submit = false
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.submit = false, 2000)
            })
    }

    return {reset, update}
}

const {canSubmit, dialog, loading, ready} = useComponentState()
const {accountData, accountDataCopy, getData} = useData()
const {reset, update} = useActions()

watch(dialog, getData)
</script>
