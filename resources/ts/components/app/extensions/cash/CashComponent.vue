<template>
    <v-row v-if="ready">
        <v-col
            cols="12"
            md="10"
            offset-md="1"
            lg="12"
            offset-lg="0"
            xl="8"
            offset-xl="2"
            class="pa-0"
        >
            <v-card>
                <CardTitleWithButtons
                    title="Cash management"
                    large-font
                    extra-bottom
                >
                    <SetCashAccountComponent
                        v-model="cashAccount"
                        :accounts="accounts"
                    ></SetCashAccountComponent>
                </CardTitleWithButtons>

                <v-card-text>
                    <v-form v-model="canSubmit">
                        <v-row>
                            <v-col
                                v-for="(value, id) in cash"
                                cols="12"
                                lg="6"
                            >
                                <v-row>
                                    <v-col
                                        cols="12"
                                        sm="4"
                                        class="d-flex flex-wrap flex-column align-center"
                                        style="overflow-x: hidden"
                                    >
                                        <div class="text-caption">
                                            Value
                                        </div>

                                        <div
                                            class="text-h5"
                                            style="overflow-x: hidden"
                                        >
                                            {{ format.numberWithCurrency(value, currencies.usedCurrencyObject.ISO) }}
                                        </div>
                                    </v-col>

                                    <v-col
                                        cols="12"
                                        sm="4"
                                    >
                                        <v-text-field
                                            v-model="ownedCash[id]"
                                            :rules="[Validator.cash(ownedCash[id])]"
                                            label="Amount"
                                            variant="underlined"
                                        ></v-text-field>
                                    </v-col>

                                    <v-col
                                        cols="12"
                                        sm="4"
                                        class="d-flex flex-wrap flex-column align-center"
                                        style="overflow-x: hidden"
                                    >
                                        <div class="text-caption">
                                            Sum
                                        </div>

                                        <div
                                            class="text-h5"
                                            style="overflow-x: hidden"
                                        >
                                            {{
                                                format.numberWithCurrency(
                                                    ownedCash[id] * value || 0,
                                                    currencies.usedCurrencyObject.ISO,
                                                )
                                            }}
                                        </div>
                                    </v-col>

                                    <v-divider
                                        v-if="xs"
                                        class="my-4"
                                    ></v-divider>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-form>

                    <v-divider
                        v-if="smAndUp"
                        class="my-4 mt-sm-0"
                    ></v-divider>

                    <v-row>
                        <v-col
                            cols="12"
                            sm="4"
                            class="d-flex flex-wrap flex-column align-center"
                            style="overflow-x: hidden"
                        >
                            <div class="text-caption">
                                Sum
                            </div>

                            <div
                                class="text-h5"
                                style="overflow-x: hidden"
                            >
                                {{
                                    format.numberWithCurrency(sum, currencies.usedCurrencyObject.ISO)
                                }}
                            </div>
                        </v-col>

                        <v-col
                            cols="12"
                            sm="4"
                            class="d-flex flex-wrap flex-column align-center"
                            style="overflow-x: hidden"
                        >
                            <div class="text-caption">
                                Current balance
                            </div>

                            <div
                                class="text-h5"
                                style="overflow-x: hidden"
                            >
                                {{
                                    format.numberWithCurrency(currentBalance, currencies.usedCurrencyObject.ISO)
                                }}
                            </div>
                        </v-col>

                        <v-col
                            cols="12"
                            sm="4"
                            class="d-flex flex-wrap flex-column align-center"
                            style="overflow-x: hidden"
                        >
                            <div class="text-caption">
                                Difference
                            </div>

                            <div
                                class="text-h5"
                                style="overflow-x: hidden"
                                :class="differenceColor"
                            >
                                {{
                                    format.numberWithCurrency(
                                        sum - currentBalance,
                                        currencies.usedCurrencyObject.ISO,
                                        true,
                                    )
                                }}
                            </div>
                        </v-col>
                    </v-row>
                </v-card-text>

                <CardActionsResetUpdateComponent
                    :loading="loading.submit"
                    :can-submit="canSubmit"
                    @reset="reset"
                    @update="submit"
                ></CardActionsResetUpdateComponent>
            </v-card>
        </v-col>
    </v-row>

    <v-overlay
        v-else
        :model-value="true"
        contained
    >
        <v-progress-circular
            indeterminate
            size="128"
        ></v-progress-circular>
    </v-overlay>
</template>

<script setup lang="ts">
import axios from "axios"
import { cloneDeep } from "lodash"
import { computed, onMounted, ref } from "vue"
import { useDisplay } from "vuetify"

import { useCurrenciesStore } from "@stores/currencies"
import { useStatusStore } from "@stores/status"
import useComponentState from "@composables/useComponentState"
import useFormats from "@composables/useFormats"
import Validator from "@classes/Validator"

import type { Account } from "@interfaces/Cash"

import SetCashAccountComponent from "@components/app/extensions/cash/SetCashAccountComponent.vue"

const currencies = useCurrenciesStore()
const status = useStatusStore()

function useCash() {
    const cash = ref<Record<string, number>>({})

    const cashAccount = ref<string>()

    const ownedCash = ref<Record<string, number>>({})

    const ownedCashCopy = ref<Record<string, number>>({})

    const accounts = ref<Account[]>([])

    const sum = computed(() => Object.keys(cash.value)
        .map(item => cash.value[item] * ownedCash.value[item] || 0)
        .reduce((carry, item) => carry + item, 0),
    )

    const currentBalance = computed(() => accounts.value
        .find(item => item.id == cashAccount.value)
        ?.balance ?? 0,
    )

    const differenceColor = computed(() =>
        sum.value - currentBalance.value == 0 ?
            "text-success" : "text-error",
    )

    function getData() {
        ready.value = false

        axios.get(`/web-api/extensions/cash/${currencies.usedCurrency}/list`)
            .then(response => {
                const data = response.data

                cash.value = data.cash
                cashAccount.value = data.cashAccount
                ownedCash.value = data.ownedCash
                ownedCashCopy.value = cloneDeep(data.ownedCash)
                accounts.value = data.accounts

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
            })
    }

    function reset() {
        ownedCash.value = cloneDeep(ownedCashCopy.value)
    }

    function submit() {
        loading.value.submit = true

        axios.post(`/web-api/extensions/cash/${currencies.usedCurrency}`, {
            cash: Object.keys(ownedCash.value).map(item => ({
                id: item,
                amount: ownedCash.value[item],
            })),
        })
            .then(() => {
                ownedCashCopy.value = cloneDeep(ownedCash.value)
                loading.value.submit = false
                status.showSuccess("updated cash")
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.submit = false, 2000)
            })
    }

    return {
        accounts,
        cash,
        cashAccount,
        currentBalance,
        differenceColor,
        getData,
        ownedCash,
        reset,
        submit,
        sum,
    }
}

const format = useFormats()
const {xs, smAndUp} = useDisplay()
const {canSubmit, loading, ready} = useComponentState()
const {
    accounts,
    cash,
    cashAccount,
    currentBalance,
    differenceColor,
    getData,
    ownedCash,
    reset,
    submit,
    sum,
} = useCash()

onMounted(() => {
    getData()
    currencies.$subscribe(getData)
})
</script>
