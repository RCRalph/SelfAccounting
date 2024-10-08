<template>
    <v-dialog
        v-model="dialog"
        max-width="700"
    >
        <template v-slot:activator="{ props }">
            <v-btn
                v-bind="props"
                variant="outlined"
            >
                Add account
            </v-btn>
        </template>

        <v-card v-if="accountData">
            <CardTitleWithButtons title="Add account"></CardTitleWithButtons>

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
                                :error-messages="startBalance.error"
                                :hint="startBalance.hint"
                                label="Start balance"
                                variant="underlined"
                            >
                                <template v-slot:append-inner>
                                    <CalculatorTooltipComponent></CalculatorTooltipComponent>
                                </template>
                            </v-text-field>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <CardActionsSubmitComponent
                :loading="!!loading.submit"
                :can-submit="!!canSubmit"
                @submit="submit"
            ></CardActionsSubmitComponent>
        </v-card>

        <CardLoadingComponent
            v-else
            title="Add account"
        ></CardLoadingComponent>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref, onMounted, computed } from "vue"
import { cloneDeep } from "lodash"
import type { Account } from "@interfaces/Account"

import IconPickerComponent from "@components/app/icon-picker/IconPickerComponent.vue"
import CalculatorTooltipComponent from "@components/global/CalculatorTooltipComponent.vue"

import { useCurrenciesStore } from "@stores/currencies"
import { useStatusStore } from "@stores/status"
import { currentTimeZoneDate } from "@composables/useDates"
import useComponentState from "@composables/useComponentState"
import Validator from "@classes/Validator"
import Calculator from "@classes/Calculator"

const emit = defineEmits<{
    added: []
}>()

const currencies = useCurrenciesStore()
const status = useStatusStore()

function useData() {
    const accountData = ref<Account>()
    const commonValues = ref<Account>({
        name: undefined,
        icon: null,
        used_in_income: true,
        used_in_expenses: true,
        show_on_charts: true,
        count_to_summary: true,
        start_date: currentTimeZoneDate(),
        start_balance: 0,
    })

    const startBalance = computed(() => new Calculator(
        accountData.value?.start_balance,
        "value",
        {
            null: false,
            negative: true,
            zero: true,
        },
    ).resultObject)

    function setData() {
        accountData.value = cloneDeep(commonValues.value)
    }

    function submit() {
        loading.value.submit = true

        axios
            .post(`/web-api/accounts/${currencies.usedCurrency}`, {
                ...accountData.value,
                start_balance: startBalance.value.value,
            })
            .then(() => {
                emit("added")
                setData()
                dialog.value = false
                loading.value.submit = false
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.submit = false, 2000)
            })
    }

    return {accountData, startBalance, setData, submit}
}

const {canSubmit, dialog, loading} = useComponentState()
const {accountData, startBalance, setData, submit} = useData()

onMounted(setData)
</script>
