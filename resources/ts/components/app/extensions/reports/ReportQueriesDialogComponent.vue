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
                text="Queries"
                block
            ></v-btn>
        </template>

        <v-card>
            <CardTitleWithButtons title="Queries"></CardTitleWithButtons>

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
                            sm="6"
                            md="3"
                        >
                            <v-text-field
                                v-model="item.min_date"
                                :max="item.max_date"
                                :rules="[
                                    Validator.date(true)
                                ]"
                                min="1970-01-01"
                                variant="underlined"
                                label="Minimal date"
                                type="date"
                            ></v-text-field>
                        </v-col>

                        <v-col
                            cols="12"
                            sm="6"
                            md="3"
                        >
                            <v-text-field
                                v-model="item.max_date"
                                :min="item.min_date || '1970-01-01'"
                                :rules="[
                                    Validator.date(true)
                                ]"
                                variant="underlined"
                                label="Maximal date"
                                type="date"
                            ></v-text-field>
                        </v-col>

                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-combobox
                                v-model="item.title"
                                :items="titles"
                                :loading="loading.title"
                                :rules="[
                                    Validator.title('Title', 64, true)
                                ]"
                                variant="underlined"
                                label="Title"
                                counter="64"
                            ></v-combobox>
                        </v-col>

                        <v-col
                            cols="12"
                            sm="6"
                            md="3"
                        >
                            <v-text-field
                                v-model="item.min_amount"
                                :error-messages="minAmount[i].error"
                                :hint="minAmount[i].hint"
                                label="Minimal amount"
                                variant="underlined"
                            >
                                <template v-slot:append-inner>
                                    <CalculatorTooltipComponent></CalculatorTooltipComponent>
                                </template>
                            </v-text-field>
                        </v-col>

                        <v-col
                            cols="12"
                            sm="6"
                            md="3"
                        >
                            <v-text-field
                                v-model="item.max_amount"
                                :error-messages="maxAmount[i].error"
                                :hint="maxAmount[i].hint"
                                label="Maximal amount"
                                variant="underlined"
                            >
                                <template v-slot:append-inner>
                                    <CalculatorTooltipComponent></CalculatorTooltipComponent>
                                </template>
                            </v-text-field>
                        </v-col>

                        <v-col
                            cols="12"
                            sm="6"
                            md="3"
                        >
                            <v-text-field
                                v-model="item.min_price"
                                :error-messages="minPrice[i].error"
                                :hint="minPrice[i].hint"
                                label="Minimal price"
                                variant="underlined"
                            >
                                <template v-slot:append-inner>
                                    <CalculatorTooltipComponent></CalculatorTooltipComponent>
                                </template>
                            </v-text-field>
                        </v-col>

                        <v-col
                            cols="12"
                            sm="6"
                            md="3"
                        >
                            <v-text-field
                                v-model="item.max_price"
                                :error-messages="maxPrice[i].error"
                                :hint="maxPrice[i].hint"
                                label="Maximal price"
                                variant="underlined"
                            >
                                <template v-slot:append-inner>
                                    <CalculatorTooltipComponent></CalculatorTooltipComponent>
                                </template>
                            </v-text-field>
                        </v-col>

                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-select
                                v-model="item.currency_id"
                                :items="currencyItems"
                                item-title="ISO"
                                item-value="id"
                                label="Currency"
                                variant="underlined"
                                @update:model-value="resetSelects"
                            ></v-select>
                        </v-col>

                        <v-col
                            cols="12"
                            md="3"
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
                            md="3"
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

                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-select
                                v-model="item.query_data"
                                :items="queryItems"
                                label="Query type"
                                variant="underlined"
                            ></v-select>
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
                    :disabled="!canSubmit || loading.submit"
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

import type { ReportQuery } from "@interfaces/Report"
import type { CategoryData } from "@interfaces/Category"
import type { AccountData } from "@interfaces/Account"

import { useCurrenciesStore } from "@stores/currencies"
import useComponentState from "@composables/useComponentState"
import useTitles from "@composables/useTitles"
import useFormats from "@composables/useFormats"
import Validator from "@classes/Validator"
import Calculator from "@classes/Calculator"

import CalculatorTooltipComponent from "@components/global/CalculatorTooltipComponent.vue"

const model = defineModel<ReportQuery[]>({default: []})

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
            query_data: "income",
            min_date: null,
            max_date: null,
            title: null,
            min_amount: null,
            max_amount: null,
            min_price: null,
            max_price: null,
            currency_id: null,
            category_id: null,
            account_id: null,
        })

        page.value = model.value.length - 1
    }

    function remove() {
        model.value.splice(page.value, 1)
        page.value = Math.min(model.value.length - 1, page.value)
    }

    function submit() {
        dialog.value = false
    }

    return {page, add, remove, submit}
}

function useCalculatedValues() {
    const calculatorAllowObject = {
        null: true,
        negative: false,
        zero: true,
    }

    const minAmount = computed(() => Array.from(
        model.value,
        item => new Calculator(
            item.min_amount,
            "amount",
            calculatorAllowObject,
            [
                value => value <= new Calculator(item.max_amount, "amount").resultValue ||
                    "Maximal amount has to be greater than minimal amount",
            ],
        ).resultObject,
    ))

    const maxAmount = computed(() => Array.from(
        model.value,
        item => new Calculator(
            item.max_amount,
            "amount",
            calculatorAllowObject,
            [
                value => new Calculator(item.min_amount, "amount").resultValue <= value ||
                    "Maximal amount has to be greater than minimal amount",
            ],
        ).resultObject,
    ))

    const minPrice = computed(() => Array.from(
        model.value,
        item => new Calculator(
            item.min_price,
            "price",
            calculatorAllowObject,
            [
                value => value <= new Calculator(item.max_price, "price").resultValue ||
                    "Maximal price has to be greater than minimal price",
            ],
        ).resultObject,
    ))

    const maxPrice = computed(() => Array.from(
        model.value,
        item => new Calculator(
            item.max_price,
            "price",
            calculatorAllowObject,
            [
                value => new Calculator(item.min_price, "price").resultValue <= value ||
                    "Maximal price has to be greater than minimal price",
            ],
        ).resultObject,
    ))

    return {minAmount, maxAmount, minPrice, maxPrice}
}

function useSelects() {
    const currencyItems = ref([
        {id: null, ISO: "All currencies"},
        ...currencies.currencies,
    ])

    const categoryItems = computed(() => {
        const result: CategoryData[] = [{id: null, name: "All categories"}]

        if (props.categories[Number(model.value[page.value].currency_id)]) {
            result.push(...props.categories[Number(model.value[page.value].currency_id)])
        }

        return result
    })

    const accountItems = computed(() => {
        const result: AccountData[] = [{
            id: null,
            name: "All accounts",
            start_date: "1970-01-01",
        }]

        if (props.accounts[Number(model.value[page.value].currency_id)]) {
            result.push(...props.accounts[Number(model.value[page.value].currency_id)])
        }

        return result
    })

    const queryItems = ref([
        {value: "income", title: "Income"},
        {value: "expenses", title: "Expenses"},
    ])

    function resetSelects() {
        model.value[page.value].category_id = null
        model.value[page.value].account_id = null
    }

    return {currencyItems, categoryItems, accountItems, queryItems, resetSelects}
}

const {dialog, canSubmit, loading} = useComponentState()
const {page, add, remove, submit} = useActions()
const {minAmount, maxAmount, minPrice, maxPrice} = useCalculatedValues()
const {currencyItems, categoryItems, accountItems, queryItems, resetSelects} = useSelects()
const {titles, getTitles} = useTitles(loading, "/web-api/extensions/reports/titles")

watch(() => model.value[page.value]?.title, (newValue, oldValue) => {
    if (typeof oldValue != "undefined" && Validator.title("", 64)(newValue) === true) {
        getTitles(model.value[page.value].title)
    }
})
</script>
