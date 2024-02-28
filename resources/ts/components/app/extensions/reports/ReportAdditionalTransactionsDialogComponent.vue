<template>
    <v-dialog
        v-model="dialog"
        max-width="775"
        persistent
    >
        <template v-slot:activator="{ props: dialogProps }">
            <v-btn
                v-bind="dialogProps"
                variant="outlined"
                class="ma-1"
                text="Transactions"
                block
            ></v-btn>
        </template>

        <v-card>
            <CardTitleWithButtons title="Additional transactions"></CardTitleWithButtons>

            <v-card-text v-if="model.length">
                <v-form
                    v-model="canSubmit"
                    class="disable-row-margin"
                >
                    <v-row
                        v-for="(item, i) in model"
                        v-show="page == i"
                    >
                        <v-col
                            cols="12"
                            md="4"
                        >
                            <v-text-field
                                v-model="item.date"
                                :rules="[
                                    Validator.date(false)
                                ]"
                                variant="underlined"
                                label="Date"
                                type="date"
                                min="1970-01-01"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="8">
                            <v-combobox
                                v-model="item.title"
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

                        <v-col
                            cols="12"
                            md="4"
                        >
                            <v-text-field
                                v-model="item.amount"
                                :error-messages="amounts[i].error"
                                :hint="amounts[i].hint"
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
                                v-model="item.price"
                                :suffix="currencies.usedCurrencyObject.ISO"
                                :error-messages="priceModified[i] ? prices[i].error : undefined"
                                :hint="prices[i].hint"
                                variant="underlined"
                                label="Price"
                                @input="priceModified[i] = true"
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
                                :value="values[i]"
                                :iso="currencies.findCurrency(item.currency_id)?.ISO"
                            ></ValueFieldComponent>
                        </v-col>

                        <v-col
                            cols="12"
                            md="4"
                        >
                            <v-select
                                v-model="item.currency_id"
                                :items="currencies.currencies"
                                item-title="ISO"
                                item-value="id"
                                label="Currency"
                                variant="underlined"
                                @update:model-value="resetSelects"
                            ></v-select>
                        </v-col>

                        <v-col
                            cols="12"
                            md="4"
                        >
                            <v-select
                                v-model="item.category_id"
                                :items="categoryItems"
                                :disabled="!item.currency_id"
                                item-title="name"
                                item-value="id"
                                label="Category"
                                variant="underlined"
                            >
                                <template v-slot:item="{ item: listItem, props: listItemProps }">
                                    <v-list-item v-bind="listItemProps">
                                        <template v-slot:prepend>
                                            <v-icon
                                                v-if="listItem.raw.icon"
                                                :icon="formats.iconName(listItem.raw.icon)"
                                            ></v-icon>
                                        </template>
                                    </v-list-item>
                                </template>
                            </v-select>
                        </v-col>

                        <v-col
                            cols="12"
                            md="4"
                        >
                            <v-select
                                v-model="item.account_id"
                                :items="accountItems"
                                :disabled="!item.currency_id"
                                item-title="name"
                                item-value="id"
                                label="Account"
                                variant="underlined"
                            >
                                <template v-slot:item="{ item: listItem, props: listItemProps }">
                                    <v-list-item v-bind="listItemProps">
                                        <template v-slot:prepend>
                                            <v-icon
                                                v-if="listItem.raw.icon"
                                                :icon="formats.iconName(listItem.raw.icon)"
                                            ></v-icon>
                                        </template>
                                    </v-list-item>
                                </template>
                            </v-select>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <v-card-text
                v-else
                class="text-center text-h6 pb-0"
            >
                Press + to add a query
            </v-card-text>

            <CardActionsNavigationComponent
                v-model:page="page"
                :data-length="model.length"
                can-be-empty
                @add="add"
                @remove="remove"
            >
                <v-btn
                    :disabled="canSubmit === false || loading.submit || !allPricesModified"
                    :loading="loading.submit"
                    text="Submit"
                    color="success"
                    class="mx-1"
                    width="90"
                    variant="outlined"
                    @click="submit"
                ></v-btn>
            </CardActionsNavigationComponent>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { computed, ref, watch } from "vue"
import { round } from "lodash"

import type { VForm } from "vuetify/components"

import type { ReportTransaction } from "@interfaces/Report"
import type { CategoryData } from "@interfaces/Category"
import type { AccountData } from "@interfaces/Account"

import { useCurrenciesStore } from "@stores/currencies"
import { currentTimeZoneDate } from "@composables/useDates"
import useComponentState from "@composables/useComponentState"
import useFormats from "@composables/useFormats"
import useTitles from "@composables/useTitles"
import Validator from "@classes/Validator"
import Calculator from "@classes/Calculator"

import CalculatorTooltipComponent from "@components/global/CalculatorTooltipComponent.vue"

const model = defineModel<ReportTransaction[]>({default: []})

const props = defineProps<{
    categories: Record<number, CategoryData[]>
    accounts: Record<number, AccountData[]>
}>()

const currencies = useCurrenciesStore()
const formats = useFormats()

function useActions() {
    const page = ref(0)

    function add() {
        model.value.push({
            date: currentTimeZoneDate(),
            title: undefined,
            amount: 1,
            price: "",
            category_id: null,
            account_id: null,
            currency_id: currencies.usedCurrency,
        })

        priceModified.value.push(false)
        page.value = model.value.length - 1
    }

    function remove() {
        model.value.splice(page.value, 1)
        priceModified.value.splice(page.value, 1)
        page.value = Math.min(model.value.length - 1, page.value)
    }

    function submit() {
        dialog.value = false
    }

    return {page, add, remove, submit}
}

function usePriceModified() {
    const priceModified = ref<boolean[]>([])

    const allPricesModified = computed(() => priceModified.value.every(item => item))

    return {priceModified, allPricesModified}
}

function useCalculatedValues() {
    const calculatorAllowObject = {
        null: false,
        negative: true,
        zero: true,
    }

    const amounts = computed(() => Array.from(
        model.value,
        item => new Calculator(
            item.amount,
            "amount",
            calculatorAllowObject,
        ).resultObject,
    ))

    const prices = computed(() => Array.from(
        model.value,
        item => new Calculator(
            item.price,
            "price",
            calculatorAllowObject,
        ).resultObject,
    ))

    const values = computed(() => Array.from(
        model.value,
        (_, i) => round(amounts.value[i].value * prices.value[i].value, 2) || 0,
    ))

    return {amounts, prices, values}
}

function useSelects() {
    const categoryItems = computed(() => {
        const result: CategoryData[] = [{id: null, name: "N/A"}]

        if (props.categories[Number(model.value[page.value].currency_id)]) {
            result.push(...props.categories[Number(model.value[page.value].currency_id)])
        }

        return result
    })

    const accountItems = computed(() => {
        const result: AccountData[] = [{
            id: null,
            name: "N/A",
            start_date: "1970-01-01",
        }]

        if (props.accounts[Number(model.value[page.value].currency_id)]) {
            result.push(...props.accounts[Number(model.value[page.value].currency_id)])
        }

        return result
    })

    function resetSelects() {
        model.value[page.value].category_id = null
        model.value[page.value].account_id = null
    }

    return {categoryItems, accountItems, resetSelects}
}

const {dialog, canSubmit, loading} = useComponentState()
const {priceModified, allPricesModified} = usePriceModified()
const {page, add, remove, submit} = useActions()
const {amounts, prices, values} = useCalculatedValues()
const {categoryItems, accountItems, resetSelects} = useSelects()
const {titles, getTitles} = useTitles(loading, "/web-api/extensions/reports/titles")

watch(() => model.value[page.value]?.title, (newValue, oldValue) => {
    if (typeof oldValue != "undefined" && Validator.title("", 64)(newValue) === true) {
        getTitles(model.value[page.value].title)
    }
})
</script>
