<template>
    <v-dialog
        v-model="dialog"
        max-width="700"
    >
        <template v-slot:activator="{ props: buttonProps }">
            <v-btn
                v-bind="buttonProps"
                variant="outlined"
            >
                Common values
            </v-btn>
        </template>

        <v-card>
            <CardTitleWithButtons title="Common values"></CardTitleWithButtons>

            <v-card-text>
                <v-form v-model="canUpdate">
                    <v-row>
                        <v-col
                            cols="12"
                            md="4"
                        >
                            <v-text-field
                                v-model="commonValues.date"
                                :min="usedAccount?.start_date"
                                :rules="[
                                    Validator.date(true, usedAccount?.date)
                                ]"
                                variant="underlined"
                                label="Date"
                                type="date"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="8">
                            <v-combobox
                                v-model="commonValues.title"
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
                    </v-row>

                    <v-row>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-text-field
                                v-model="commonValues.amount"
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
                            md="6"
                        >
                            <v-text-field
                                v-model="commonValues.price"
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
                    </v-row>

                    <v-row>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-select
                                v-model="commonValues.category_id"
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
                                v-model="commonValues.account_id"
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

            <v-card-actions class="d-flex justify-center">
                <v-btn
                    :disabled="!canUpdate || props.disableUpdate"
                    color="success"
                    variant="outlined"
                    @click="update"
                >
                    Update
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { watch, ref, computed } from "vue"
import { cloneDeep, isNull } from "lodash"

import type { Transaction } from "@interfaces/Transaction"
import type { AccountData } from "@interfaces/Account"
import type { CategoryData } from "@interfaces/Category"

import { useCurrenciesStore } from "@stores/currencies"
import useFormats from "@composables/useFormats"
import useComponentState from "@composables/useComponentState"
import useTitles from "@composables/useTitles"
import Calculator from "@classes/Calculator"
import Validator from "@classes/Validator"

import CalculatorTooltipComponent from "@components/global/CalculatorTooltipComponent.vue"

const props = defineProps<{
    transactionData: Transaction[],
    commonValues: Transaction,
    accounts: AccountData[],
    categories: CategoryData[],
    disableUpdate: boolean,
    type: "income" | "expenses"
}>()

const emit = defineEmits<{
    "update:common-values": [payload: Transaction],
    priceChange: []
}>()

const currencies = useCurrenciesStore()
const formats = useFormats()

function useCommonValues() {
    const commonValues = ref(cloneDeep(props.commonValues))

    const usedAccount = computed(() => {
        const result = props.accounts.find(item => item.id == commonValues.value?.account_id)

        if (typeof result == "undefined") {
            return undefined
        } else if (!isNull(result.start_date)) {
            return {...result, date: new Date(result.start_date)}
        } else {
            throw new Error("Account start date is null, which it shouldn't be.")
        }
    })

    function update() {
        const changes = cloneDeep(commonValues.value)

        for (let key of Object.keys(changes) as (keyof Transaction)[]) {
            if (props.commonValues[key] == changes[key] || changes[key] == "") {
                delete changes[key]
            }
        }

        if (changes.hasOwnProperty("price")) {
            emit("priceChange")
        }

        props.transactionData.forEach((item, i) => {
            props.transactionData[i] = {...item, ...changes}
        })

        emit("update:common-values", commonValues.value)

        dialog.value = false
    }

    return {commonValues, update, usedAccount}
}

function useCalculatedValues() {
    const calculatorAllowObject = {
        null: true,
        negative: false,
        zero: false,
    }

    const amount = computed(() => new Calculator(
        commonValues.value?.amount,
        "amount",
        calculatorAllowObject,
    ).resultObject)

    const price = computed(() => new Calculator(
        commonValues.value?.price,
        "price",
        calculatorAllowObject,
    ).resultObject)

    return {amount, price}
}

const {dialog, canSubmit: canUpdate, loading} = useComponentState()
const {commonValues, usedAccount, update} = useCommonValues()
const {amount, price} = useCalculatedValues()
const {titles, getTitles} = useTitles(
    loading,
    `/web-api/${props.type}/currency/${currencies.usedCurrency}/titles`,
)

watch(() => commonValues.value?.title, (newValue, oldValue) => {
    if (typeof oldValue != "undefined" && Validator.title("", 64)(newValue) === true) {
        getTitles(commonValues.value?.title)
    }
})

watch(() => props.commonValues, () => {
    commonValues.value = cloneDeep(props.commonValues)
})
</script>
