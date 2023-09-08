<template>
    <v-dialog
        v-model="dialog"
        max-width="700"
    >
        <template v-slot:activator="{ props: dialogProps }: any">
            <v-btn
                v-bind="dialogProps"
                variant="outlined"
                class="mx-0"
            >
                Add transfer
            </v-btn>
        </template>

        <v-card v-if="ready && transferData != undefined">
            <CardTitleWithButtons title="Add transfer"></CardTitleWithButtons>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <v-row>
                        <v-col
                            cols="12"
                            md="4"
                            offset-md="4"
                            class="pb-0"
                        >
                            <v-text-field
                                v-model="transferData.date"
                                :min="minDate"
                                :rules="[
                                    Validator.date(false, minDate)
                                ]"
                                variant="underlined"
                                label="Date"
                                type="date"
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col
                            cols="12"
                            md="6"
                            class="pt-sm-0"
                        >
                            <div class="text-h5 text-center">
                                Source
                            </div>

                            <v-text-field
                                v-model="transferData.source.value"
                                :suffix="sourceData?.currency?.ISO"
                                :error-messages="valueModified.source ? sourceValue.error : undefined"
                                :hint="sourceValue.hint"
                                variant="underlined"
                                label="Value"
                                @input="valueModified.source = true"
                            >
                                <template v-slot:append-inner>
                                    <v-tooltip location="bottom">
                                        <template v-slot:activator="{ props }: any">
                                            <v-icon
                                                v-bind="props"
                                                class="ml-1"
                                                icon="mdi-calculator"
                                            ></v-icon>
                                        </template>

                                        Supported operations: <strong>+ - * / ^</strong>
                                    </v-tooltip>
                                </template>
                            </v-text-field>

                            <v-select
                                v-model="transferData.source.account_id"
                                :rules="[
                                    Validator.differentAccounts(transferData.target?.account_id)
                                ]"
                                :items="sourceAccounts"
                                item-title="name"
                                item-value="id"
                                label="Account"
                                variant="underlined"
                            >
                                <template v-slot:item="{ item, props }: any">
                                    <v-list-item v-bind="props">
                                        <template v-slot:prepend>
                                            <v-icon v-if="item.raw.icon">
                                                {{ formats.iconName(item.raw.icon) }}
                                            </v-icon>
                                        </template>
                                    </v-list-item>
                                </template>
                            </v-select>

                            <v-select
                                v-model="transferData.source.currency_id"
                                :items="availableCurrencyData"
                                item-title="ISO"
                                item-value="id"
                                label="Currency"
                                variant="underlined"
                                @update:model-value="resetAccount('source')"
                            ></v-select>
                        </v-col>

                        <v-col
                            cols="12"
                            md="6"
                            class="pt-sm-0"
                        >
                            <div class="text-h5 text-center">
                                Target
                            </div>

                            <v-text-field
                                v-model="transferData.target.value"
                                :suffix="targetData?.currency?.ISO"
                                :error-messages="valueModified.target ? targetValue.error : undefined"
                                :hint="targetValue.hint"
                                variant="underlined"
                                label="Value"
                                @input="valueModified.target = true"
                            >
                                <template v-slot:append-inner>
                                    <v-tooltip location="bottom">
                                        <template v-slot:activator="{ props }: any">
                                            <v-icon
                                                v-bind="props"
                                                class="ml-1"
                                                icon="mdi-calculator"
                                            ></v-icon>
                                        </template>

                                        Supported operations: <strong>+ - * / ^</strong>
                                    </v-tooltip>
                                </template>
                            </v-text-field>

                            <v-select
                                v-model="transferData.target.account_id"
                                :rules="[
                                    Validator.differentAccounts(transferData.source?.account_id)
                                ]"
                                :items="targetAccounts"
                                item-title="name"
                                item-value="id"
                                label="Account"
                                variant="underlined"
                            >
                                <template v-slot:item="{ item, props }: any">
                                    <v-list-item v-bind="props">
                                        <template v-slot:prepend>
                                            <v-icon v-if="item.raw.icon">
                                                {{ formats.iconName(item.raw.icon) }}
                                            </v-icon>
                                        </template>
                                    </v-list-item>
                                </template>
                            </v-select>

                            <v-select
                                v-model="transferData.target.currency_id"
                                :items="availableCurrencyData"
                                item-title="ISO"
                                item-value="id"
                                label="Currency"
                                variant="underlined"
                                @update:model-value="resetAccount('target')"
                            ></v-select>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <CardActionsSubmitComponent
                :can-submit="!!canSubmit && valueModified.source && valueModified.target"
                :loading="!!loading.submit"
                @submit="submit"
            ></CardActionsSubmitComponent>
        </v-card>

        <CardLoadingComponent v-else>
            Add transfer
        </CardLoadingComponent>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref, computed, watch } from "vue"
import type { Ref } from "vue"
import { isNull, cloneDeep } from "lodash"

import type { Account } from "@interfaces/Account"
import type { Transfer } from "@interfaces/Transfer"

import CardTitleWithButtons from "@components/common/CardTitleWithButtons.vue"
import CardLoadingComponent from "@components/common/CardLoadingComponent.vue"

import { useDialogSettings } from "@composables/useDialogSettings"
import { useStatusStore } from "@stores/status"
import { useCurrenciesStore } from "@stores/currencies"
import useFormats from "@composables/useFormats"
import Calculator from "@classes/Calculator"
import Validator from "@classes/Validator"
import CardActionsSubmitComponent from "@components/common/card-actions/CardActionsSubmitComponent.vue"

const emit = defineEmits<{
    added: []
}>()

const status = useStatusStore()
const currencies = useCurrenciesStore()
const formats = useFormats()

function useData() {
    const transferData: Ref<Transfer | undefined> = ref(undefined)
    const commonValues: Ref<Transfer> = ref({
        date: new Date().toISOString().split("T")[0],
        source: {
            value: "",
            account_id: undefined,
            currency_id: currencies.usedCurrency,
        },
        target: {
            value: "",
            account_id: undefined,
            currency_id: currencies.usedCurrency,
        },
    })

    const accounts: Ref<Record<number, Account[]>> = ref({})
    const availableCurrencies: Ref<number[]> = ref([])
    const availableCurrencyData = computed(() => currencies.selectCurrencies(availableCurrencies.value))

    const valueModified = ref({
        source: false,
        target: false,
    })

    const sourceData = computed(() => {
        if (typeof transferData.value == "undefined") return undefined

        const account = accounts.value[transferData.value.source.currency_id]
            .find(item => item.id == transferData.value?.source.account_id)

        return {
            currency: currencies.findCurrency(transferData.value.source.currency_id),
            account: typeof account != "undefined" && !isNull(account.start_date) ?
                {...account, date: new Date(account.start_date)} : undefined,
        }
    })

    const targetData = computed(() => {
        if (typeof transferData.value == "undefined") return undefined

        const account = accounts.value[transferData.value?.target.currency_id]
            .find(item => item.id == transferData.value?.target.account_id)

        return {
            currency: currencies.findCurrency(transferData.value.target.currency_id),
            account: typeof account != "undefined" && !isNull(account.start_date) ?
                {...account, date: new Date(account.start_date)} : undefined,
        }
    })

    const sourceAccounts = computed(() => {
        if (typeof transferData.value == "undefined") return []

        return accounts.value[transferData.value.source.currency_id]
    })

    const targetAccounts = computed(() => {
        if (typeof transferData.value == "undefined") return []

        return accounts.value[transferData.value.target.currency_id]
    })

    const minDate = computed(() => {
        let result = new Date("1970-01-01")

        if (
            typeof sourceData.value?.account != "undefined" &&
            result.getTime() < sourceData.value.account.date.getTime()
        ) {
            result = sourceData.value.account.date
        }

        if (
            typeof targetData.value?.account != "undefined" &&
            result.getTime() < targetData.value.account.date.getTime()
        ) {
            result = targetData.value.account.date
        }

        return result
    })

    function resetAccount(type: "source" | "target") {
        if (typeof transferData.value == "undefined") return

        transferData.value[type].account_id = undefined
    }

    function getData() {
        if (!dialog.value) return

        ready.value = false

        axios.get(`/web-api/transfers`)
            .then(response => {
                const data = response.data

                transferData.value = cloneDeep(commonValues.value)
                accounts.value = data.accounts
                availableCurrencies.value = data.availableCurrencies

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
            })
    }

    return {
        getData, minDate,
        sourceAccounts, sourceData,
        targetAccounts, targetData,
        transferData,
        valueModified,
        availableCurrencyData,
        resetAccount,
    }
}

function useCalculatedValues() {
    const calculatorAllowObject = {
        null: false,
        negative: false,
        zero: false,
    }

    const sourceValue = computed(() => new Calculator(
        transferData.value?.source.value || "",
        "price",
        calculatorAllowObject,
    ).resultObject)

    const targetValue = computed(() => new Calculator(
        transferData.value?.target.value || "",
        "price",
        calculatorAllowObject,
    ).resultObject)

    return {sourceValue, targetValue}
}

function useActions() {
    function submit() {
        loading.value.submit = true

        if (typeof transferData.value == "undefined") {
            throw new Error("Can't update data when it's undefined")
        }

        const data: any = cloneDeep(transferData.value)
        data.source.value = sourceValue.value.value
        data.target.value = targetValue.value.value
        delete data.source.currency_id
        delete data.target.currency_id

        axios.post(`/web-api/transfers`, data)
            .then(() => {
                emit("added")
                status.showSuccess("added transfer")
                dialog.value = false
                loading.value.submit = false
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.submit = false, 2000)
            })
    }

    return {submit}
}

const {canSubmit, dialog, loading, ready} = useDialogSettings()
const {
    getData, minDate,
    sourceAccounts, sourceData,
    targetAccounts, targetData,
    transferData,
    valueModified,
    availableCurrencyData,
    resetAccount,
} = useData()
const {sourceValue, targetValue} = useCalculatedValues()
const {submit} = useActions()

watch(dialog, getData)
</script>
