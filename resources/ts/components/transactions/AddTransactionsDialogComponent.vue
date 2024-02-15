<template>
    <v-dialog
        v-model="dialog"
        max-width="800"
    >
        <template v-slot:activator="{ props: dialogProps }">
            <v-btn
                v-bind="dialogProps"
                variant="outlined"
            >
                Add {{ props.type }}
            </v-btn>
        </template>

        <v-card v-if="ready">
            <CardTitleWithButtons :title="`Add ${props.type}`">
                <CommonValuesComponent
                    v-model:transaction-data="transactionData"
                    v-model:common-values="commonValues"
                    :categories="categories"
                    :accounts="accounts"
                    :type="props.type"
                    :disable-update="!!loading.submit"
                    :titles="titles"
                    @price-change="commonValuesPriceChange"
                ></CommonValuesComponent>
            </CardTitleWithButtons>

            <v-card-text v-if="transactionData.length">
                <v-form v-model="canSubmit">
                    <v-row>
                        <v-col
                            cols="12"
                            md="4"
                        >
                            <v-text-field
                                v-model="transactionData[page].date"
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
                                v-model="transactionData[page].title"
                                v-model:menu="titleMenuShow"
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
                                v-model="transactionData[page].amount"
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
                                v-model="transactionData[page].price"
                                :suffix="currencies.usedCurrencyObject.ISO"
                                :error-messages="priceModified[page] ? price.error : undefined"
                                :hint="price.hint"
                                variant="underlined"
                                label="Price"
                                @input="priceModified[page] = true"
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
                                v-model="transactionData[page].category_id"
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
                                v-model="transactionData[page].account_id"
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

                <div class="text-center text-h5" v-if="transactionData.length > 1">
                    Sum: {{ formats.numberWithCurrency(sum, currencies.usedCurrencyObject.ISO) }}
                </div>
            </v-card-text>

            <CardActionsNavigationComponent
                v-model:page="page"
                :data-length="transactionData.length"
                @add="add"
                @remove="remove"
            >
                <!--<CashTransactionsDialogComponent
                    v-if="extensions.hasExtension('cashan')"
                    v-model="cash"
                    :accountIDs="accountIDs"
                    :disabled="loading"
                    :sumByAccounts="sumByAccounts"
                    :type="type"
                ></CashTransactionsDialogComponent>-->

                <v-btn
                    color="success"
                    class="mx-1"
                    width="90"
                    variant="outlined"
                    :disabled="!canSubmit || !allPricesModified || loading.submit"
                    :loading="loading.submit"
                    @click="submit"
                >
                    Submit
                </v-btn>
            </CardActionsNavigationComponent>
        </v-card>

        <CardLoadingComponent
            v-else
            :title="`Add ${props.type}`"
        ></CardLoadingComponent>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { isNull, round, cloneDeep } from "lodash"
import { ref, computed, watch } from "vue"

import useFormats from "@composables/useFormats"
import useTitles from "@composables/useTitles"
import { useDialogSettings } from "@composables/useDialogSettings"
import { currentTimeZoneDate } from "@composables/useDates"
import { useStatusStore } from "@stores/status"
import { useCurrenciesStore } from "@stores/currencies"
import Validator from "@classes/Validator"

import CommonValuesComponent from "@components/transactions/CommonValuesComponent.vue"
import CalculatorTooltipComponent from "@components/global/CalculatorTooltipComponent.vue"

import type { Transaction } from "@interfaces/Transaction"
import type { CategoryData } from "@interfaces/Category"
import type { AccountData } from "@interfaces/Account"
import Calculator from "@classes/Calculator"

const props = defineProps<{
    type: "income" | "expenses"
}>()

const emit = defineEmits<{
    added: []
}>()

const status = useStatusStore()
const currencies = useCurrenciesStore()
const formats = useFormats()

function usePriceModified() {
    const priceModified = ref<boolean[]>([])

    const allPricesModified = computed(() => {
        if (!priceModified.value.length) return true

        return priceModified.value.reduce((item1, item2) => item1 && item2)
    })

    function commonValuesPriceChange() {
        priceModified.value = priceModified.value.map(() => true)
    }

    return {priceModified, allPricesModified, commonValuesPriceChange}
}

function useData() {
    const transactionData = ref<Transaction[]>([])
    const commonValues = ref<Transaction>({
        date: currentTimeZoneDate(),
        title: undefined,
        amount: 1,
        price: "",
        category_id: null,
        account_id: null,
        currency_id: currencies.usedCurrency,
    })
    const categories = ref<CategoryData[]>([])
    const accounts = ref<AccountData[]>([])

    const page = ref(0)

    const usedAccount = computed(() => {
        const result = accounts.value.find(item => item.id == transactionData.value[page.value]?.account_id)

        if (typeof result == "undefined") {
            return undefined
        } else if (!isNull(result.start_date)) {
            return {...result, date: new Date(result.start_date)}
        } else {
            throw new Error("Account start date is null, which it shouldn't be.")
        }
    })

    function appendData() {
        transactionData.value.push(cloneDeep(commonValues.value))
        priceModified.value.push(false)
    }

    function getData() {
        if (!dialog.value) return

        ready.value = false

        axios.get(`/web-api/${props.type}/currency/${currencies.usedCurrency}/data`)
            .then(response => {
                const data = response.data

                accounts.value = data.accounts
                categories.value = data.categories

                if (!transactionData.value.length) {
                    page.value = 0
                    appendData()
                }

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
            })
    }

    return {accounts, appendData, categories, commonValues, getData, page, transactionData, usedAccount}
}

function useCalculatedValues() {
    const calculatorAllowObject = {
        null: false,
        negative: false,
        zero: false,
    }

    const amount = computed(() => new Calculator(
        transactionData.value[page.value]?.amount || "",
        "amount",
        calculatorAllowObject,
    ).resultObject)

    const price = computed(() => new Calculator(
        transactionData.value[page.value]?.price || "",
        "price",
        calculatorAllowObject,
    ).resultObject)

    const value = computed(() => round(amount.value.value * price.value.value, 2) || 0)

    const sum = computed(() => {
        if (!transactionData.value.length) return 0

        return transactionData.value.map(item => {
            return round(
                new Calculator(item.amount, "amount").resultValue *
                new Calculator(item.price, "price").resultValue,
                2,
            )
        }).reduce((item1, item2) => item1 + item2)
    })

    return {amount, price, value, sum}
}

function useActions() {
    function add() {
        appendData()
        page.value = transactionData.value.length - 1
    }

    function remove() {
        transactionData.value.splice(page.value, 1)
        priceModified.value.splice(page.value, 1)
        page.value = Math.min(transactionData.value.length - 1, page.value)
    }

    function submit() {
        loading.value.submit = true

        const data = cloneDeep(transactionData.value)
        for (let item of data) {
            item.amount = new Calculator(item.amount, "amount").resultValue
            item.price = new Calculator(item.price, "price").resultValue
        }

        axios.post(`/web-api/${props.type}/currency/${currencies.usedCurrency}`, {
            data,
            //cash: cashArray
        })
            .then(() => {
                emit("added")
                status.showSuccess(`added ${props.type}`)
                dialog.value = false
                loading.value.submit = false
                transactionData.value = []
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.submit = false, 2000)
            })
    }

    return {add, remove, submit}
}

const {allPricesModified, priceModified, commonValuesPriceChange} = usePriceModified()
const {dialog, ready, canSubmit, loading} = useDialogSettings()
const {accounts, appendData, categories, commonValues, getData, page, transactionData, usedAccount} = useData()
const {amount, price, value, sum} = useCalculatedValues()
const {add, remove, submit} = useActions()
const {getTitles, titleMenuShow, titles} = useTitles(
    loading,
    `/web-api/${props.type}/currency/${currencies.usedCurrency}/titles`,
)

watch(dialog, getData)

watch(() => transactionData.value[page.value]?.title, (newValue, oldValue) => {
    if (typeof oldValue != "undefined" && Validator.title("", 64)(newValue) === true) {
        getTitles(transactionData.value[page.value]?.title)
    }
})
</script>
