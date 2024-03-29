<template>
    <v-dialog
        v-model="dialog"
        max-width="800"
    >
        <template v-slot:activator="{ props: dialogProps }">
            <v-tooltip
                :text="`Edit ${typeSingular}`"
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

        <v-card v-if="ready && transactionData != undefined">
            <CardTitleWithButtons :title="`Edit ${typeSingular}`">
                <ConvertTransactionDialogComponent
                    :type="type"
                    :id="id"
                    @converted="emit('updated')"
                ></ConvertTransactionDialogComponent>
            </CardTitleWithButtons>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <v-row>
                        <v-col
                            cols="12"
                            md="4"
                        >
                            <v-text-field
                                v-model="transactionData.date"
                                :min="usedAccount?.start_date"
                                :rules="[
                                    Validator.date(false, usedAccount?.date)
                                ]"
                                variant="underlined"
                                label="Date"
                                type="date"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="8">
                            <v-combobox
                                v-model="transactionData.title"
                                :items="titles"
                                :loading="loading.title"
                                :rules="[
                                    Validator.title('Title', 64)
                                ]"
                                variant="underlined"
                                label="Title"
                                counter="64"
                            ></v-combobox>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col
                            cols="12"
                            md="4"
                        >
                            <v-text-field
                                v-model="transactionData.amount"
                                :error-messages="amount.error"
                                :hint="amount.hint"
                                variant="underlined"
                                label="Amount"
                            >
                                <template v-slot:append-inner>
                                    <CalculatorTooltipComponent></CalculatorTooltipComponent>
                                </template>
                            </v-text-field>
                        </v-col>

                        <v-col
                            cols="12"
                            md="4"
                        >
                            <v-text-field
                                v-model="transactionData.price"
                                :suffix="currencies.usedCurrencyObject.ISO"
                                :error-messages="price.error"
                                :hint="price.hint"
                                variant="underlined"
                                label="Price"
                            >
                                <template v-slot:append-inner>
                                    <CalculatorTooltipComponent></CalculatorTooltipComponent>
                                </template>
                            </v-text-field>
                        </v-col>

                        <v-col
                            cols="12"
                            md="4"
                        >
                            <ValueFieldComponent
                                :value="value"
                                :iso="currencies.usedCurrencyObject.ISO"
                            ></ValueFieldComponent>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-select
                                v-model="transactionData.category_id"
                                :items="categories"
                                item-title="name"
                                item-value="id"
                                label="Category"
                                variant="underlined"
                            >
                                <template v-slot:item="{ item, props: listItemProps }">
                                    <v-list-item v-bind="listItemProps">
                                        <template v-slot:prepend>
                                            <v-icon v-if="item.raw.icon">
                                                {{ formats.iconName(item.raw.icon) }}
                                            </v-icon>
                                        </template>
                                    </v-list-item>
                                </template>
                            </v-select>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-select
                                v-model="transactionData.account_id"
                                :items="accounts"
                                item-title="name"
                                item-value="id"
                                label="Account"
                                variant="underlined"
                            >
                                <template v-slot:item="{ item, props: listItemProps }">
                                    <v-list-item v-bind="listItemProps">
                                        <template v-slot:prepend>
                                            <v-icon v-if="item.raw.icon">
                                                {{ formats.iconName(item.raw.icon) }}
                                            </v-icon>
                                        </template>
                                    </v-list-item>
                                </template>
                            </v-select>
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
            :title="`Edit ${typeSingular}`"
        ></CardLoadingComponent>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { round } from "lodash"
import { cloneDeep, isNull } from "lodash"
import { ref, computed, watch } from "vue"

import type { Transaction } from "@interfaces/Transaction"
import type { CategoryData } from "@interfaces/Category"
import type { AccountData } from "@interfaces/Account"

import ConvertTransactionDialogComponent from "@components/app/transactions/ConvertTransactionDialogComponent.vue"
import CalculatorTooltipComponent from "@components/global/CalculatorTooltipComponent.vue"

import useTitles from "@composables/useTitles"
import useComponentState from "@composables/useComponentState"
import { useStatusStore } from "@stores/status"
import { useCurrenciesStore } from "@stores/currencies"
import useFormats from "@composables/useFormats"
import Calculator from "@classes/Calculator"
import Validator from "@classes/Validator"

const props = defineProps<{
    type: "income" | "expenses"
    id: number
}>()

const emit = defineEmits<{
    updated: []
}>()

const status = useStatusStore()
const currencies = useCurrenciesStore()
const formats = useFormats()

function useData() {
    const transactionData = ref<Transaction>()
    const transactionDataCopy = ref<Transaction>()

    const categories = ref<CategoryData[]>([])
    const accounts = ref<AccountData[]>([])

    const usedAccount = computed(() => {
        const result = accounts.value.find(item => item.id == transactionData.value?.account_id)

        if (typeof result == "undefined") {
            return undefined
        } else if (!isNull(result.start_date)) {
            return {...result, date: new Date(result.start_date)}
        } else {
            throw new Error("Account start date is null, which it shouldn't be.")
        }
    })

    function getData() {
        if (!dialog.value) return

        ready.value = false

        axios.get(`/web-api/${props.type}/${props.id}`)
            .then(response => {
                const data = response.data

                transactionData.value = data.data
                transactionDataCopy.value = cloneDeep(data.data)

                accounts.value = data.accounts
                categories.value = data.categories

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
            })
    }

    return {accounts, categories, getData, transactionData, transactionDataCopy, usedAccount}
}

function useCalculatedValues() {
    const calculatorAllowObject = {
        null: false,
        negative: false,
        zero: false,
    }

    const amount = computed(() => new Calculator(
        transactionData.value?.amount,
        "amount",
        calculatorAllowObject,
    ).resultObject)

    const price = computed(() => new Calculator(
        transactionData.value?.price,
        "price",
        calculatorAllowObject,
    ).resultObject)

    const value = computed(() => round(amount.value.value * price.value.value, 2) || 0)

    return {amount, price, value}
}

function useActions() {
    function reset() {
        transactionData.value = cloneDeep(transactionDataCopy.value)
    }

    function update() {
        loading.value.submit = true

        axios.patch(`/web-api/${props.type}/${props.id}`, {
            ...transactionData.value,
            amount: amount.value.value,
            price: price.value.value,
        })
            .then(() => {
                emit("updated")
                status.showSuccess(`updated ${typeSingular.value}`)
                transactionDataCopy.value = cloneDeep(transactionData.value)
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

const typeSingular = computed(() => props.type == "expenses" ? "expense" : "income")
const {canSubmit, dialog, loading, ready} = useComponentState()
const {accounts, categories, getData, transactionData, transactionDataCopy, usedAccount} = useData()
const {amount, price, value} = useCalculatedValues()
const {reset, update} = useActions()
const {getTitles, titles} = useTitles(
    loading,
    `/web-api/${props.type}/currency/${currencies.usedCurrency}/titles`,
)

watch(dialog, getData)

watch(() => transactionData.value?.title, (newValue, oldValue) => {
    if (typeof oldValue != "undefined" && Validator.title("", 64)(newValue) === true) {
        getTitles(transactionData.value?.title)
    }
})
</script>
