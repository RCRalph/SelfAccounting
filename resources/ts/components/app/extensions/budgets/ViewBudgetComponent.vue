<template>
    <div v-if="ready && budgetInformation" class="pagination-fixed-margin">
        <v-card>
            <CardTitleWithButtons
                :title="budgetInformation.title"
                large-font
            >
                <EditBudgetDialogComponent
                    v-if="budgetInformation.id"
                    :id="budgetInformation.id"
                    @updated="getData"
                ></EditBudgetDialogComponent>

                <AddBudgetEntriesDialogComponent
                    v-model:entries="budgetEntries"
                    :categories="categories"
                ></AddBudgetEntriesDialogComponent>
            </CardTitleWithButtons>

            <v-card-text>
                <v-row class="mb-3">
                    <v-col
                        cols="12"
                        md="8" offset-md="2"
                        lg="6" offset-lg="3"
                        xl="4" offset-xl="4"
                    >
                        <div class="text-h5 text-center mb-2">
                            Budget balance ({{ budgetInformation.start_date }} &rarr; {{ budgetInformation.end_date }})
                        </div>

                        <v-table>
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        Current value
                                    </th>

                                    <th class="text-center">
                                        Target value
                                    </th>

                                    <th class="text-center">
                                        Difference
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="item in budgetBalance">
                                    <td
                                        class="text-center"
                                        :class="formats.numberColorClass(item.current)"
                                    >
                                        {{
                                            formats.numberWithCurrency(
                                                item.current,
                                                item.currency,
                                                true,
                                            )
                                        }}
                                    </td>

                                    <td
                                        class="text-center"
                                        :class="formats.numberColorClass(item.target)"
                                    >
                                        {{
                                            formats.numberWithCurrency(
                                                item.target,
                                                item.currency,
                                                true,
                                            )
                                        }}
                                    </td>

                                    <td
                                        class="text-center"
                                        :class="formats.numberColorClass(item.current - item.target)"
                                    >
                                        {{
                                            formats.numberWithCurrency(
                                                item.current - item.target,
                                                item.currency,
                                                true,
                                            )
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                    </v-col>
                </v-row>

                <v-form
                    v-if="budgetEntries.length"
                    v-model="canSubmit"
                >
                    <v-row>
                        <v-col
                            cols="12"
                            lg="6"
                            class="pr-lg-7"
                        >
                            <h5 class="text-h5 text-center">
                                <v-icon icon="mdi-trending-up"></v-icon>

                                Income
                            </h5>

                            <v-table class="mt-3 mb-4">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            Current value
                                        </th>

                                        <th class="text-center">
                                            Target value
                                        </th>

                                        <th class="text-center">
                                            Difference
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="item in incomeBalance">
                                        <td
                                            class="text-center"
                                        >
                                            {{
                                                formats.numberWithCurrency(
                                                    item.current,
                                                    item.currency,
                                                )
                                            }}
                                        </td>

                                        <td
                                            class="text-center"
                                        >
                                            {{
                                                formats.numberWithCurrency(
                                                    item.target,
                                                    item.currency,
                                                )
                                            }}
                                        </td>

                                        <td
                                            class="text-center"
                                            :class="formats.numberColorClass(item.current - item.target)"
                                        >
                                            {{
                                                formats.numberWithCurrency(
                                                    item.current - item.target,
                                                    item.currency,
                                                    true,
                                                )
                                            }}
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>

                            <v-row>
                                <v-col
                                    v-for="item in budgetEntries.filter(x => x.transaction_type == 'income')"
                                    cols="12"
                                    lg="6"
                                >
                                    <BudgetEntryComponent
                                        :currency="currencyByCategory[Number(item.id)]"
                                        :entry="item"
                                        :current-value="currentIncomeValues[item.category_id] || 0"
                                        :category="categoryByID[item.category_id]"
                                    ></BudgetEntryComponent>
                                </v-col>
                            </v-row>
                        </v-col>

                        <v-col
                            cols="12"
                            lg="6"
                            class="pl-lg-7"
                        >
                            <h5 class="text-h5 text-center">
                                <v-icon icon="mdi-trending-down"></v-icon>

                                Expenses
                            </h5>

                            <v-table class="mt-3 mb-4">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            Current value
                                        </th>

                                        <th class="text-center">
                                            Target value
                                        </th>

                                        <th class="text-center">
                                            Difference
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="item in expenseBalance">
                                        <td
                                            class="text-center"
                                        >
                                            {{
                                                formats.numberWithCurrency(
                                                    item.current,
                                                    item.currency,
                                                )
                                            }}
                                        </td>

                                        <td
                                            class="text-center"
                                        >
                                            {{
                                                formats.numberWithCurrency(
                                                    item.target,
                                                    item.currency,
                                                )
                                            }}
                                        </td>

                                        <td
                                            class="text-center"
                                            :class="formats.numberColorClass(item.target - item.current)"
                                        >
                                            {{
                                                formats.numberWithCurrency(
                                                    item.target - item.current,
                                                    item.currency,
                                                    true,
                                                )
                                            }}
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>

                            <v-row>
                                <v-col
                                    v-for="item in budgetEntries.filter(x => x.transaction_type == 'expenses')"
                                    cols="12"
                                    xl="6"
                                >
                                    <BudgetEntryComponent
                                        :currency="currencyByCategory[Number(item.id)]"
                                        :entry="item"
                                        :current-value="currentExpenseValues[item.category_id] || 0"
                                        :category="categoryByID[item.category_id]"
                                    ></BudgetEntryComponent>
                                </v-col>
                            </v-row>
                        </v-col>
                    </v-row>
                </v-form>

                <div v-else>
                    <h4 class="text-h4 text-center py-4">
                        This budget is empty
                    </h4>

                    <h5 class="text-h5 text-center pb-4">
                        Click the <strong>Manage Entries</strong> button to add entries
                    </h5>
                </div>
            </v-card-text>

            <CardActionsResetUpdateComponent
                :loading="loading.submit"
                :can-submit="canSubmit"
                @reset="reset"
                @update="update"
            ></CardActionsResetUpdateComponent>
        </v-card>

        <div class="pagination-fixed">
            <v-card
                class="pa-1 flex-grow-1"
                elevation="6"
            >
                <v-pagination
                    v-model="currentBudgetIndex"
                    :length="budgetIDs.length"
                    variant="elevated"
                    class="flex-grow-1"
                ></v-pagination>
            </v-card>
        </div>
    </div>

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
import _ from "lodash"
import axios from "axios"
import { computed, onMounted, ref, watch } from "vue"
import { useRoute, useRouter } from "vue-router"

import type { BudgetEntry, BudgetInformation } from "@interfaces/Budget"
import type { Category } from "@interfaces/Category"

import EditBudgetDialogComponent from "@components/app/extensions/budgets/EditBudgetDialogComponent.vue"
import AddBudgetEntriesDialogComponent from "@components/app/extensions/budgets/ManageBudgetEntriesDialogComponent.vue"
import BudgetEntryComponent from "@components/app/extensions/budgets/BudgetEntryComponent.vue"
import CardActionsResetUpdateComponent from "@components/global/card/CardActionsResetUpdateComponent.vue"
import CardTitleWithButtons from "@components/global/card/CardTitleWithButtonsComponent.vue"

import { type Currency, useCurrenciesStore } from "@stores/currencies"
import { useStatusStore } from "@stores/status"
import useComponentState from "@composables/useComponentState"
import useFormats from "@composables/useFormats"

const route = useRoute()
const router = useRouter()
const status = useStatusStore()
const currencies = useCurrenciesStore()
const formats = useFormats()

function useNavigation() {
    const budgetIDs = ref<number[]>([])

    const currentBudgetIndex = ref<number>(0)

    watch(currentBudgetIndex, index => {
        if (currentBudgetIndex.value && route.params.id != budgetIDs.value[index - 1].toString()) {
            router.push(`/extensions/budgets/${budgetIDs.value[index - 1]}`)
                .then(getData)
        }
    })

    return {currentBudgetIndex, budgetIDs}
}

function useBudgetData() {
    const budgetInformation = ref<BudgetInformation>()

    const budgetEntries = ref<BudgetEntry[]>([])
    const budgetEntriesCopy = ref<BudgetEntry[]>([])

    const categories = ref<Record<number, Category[]>>({})

    const currentIncomeValues = ref<Record<number, number>>({})

    const currentExpenseValues = ref<Record<number, number>>({})

    const categoryByID = computed(() => {
        const result: Record<number, Category> = {}

        for (const categoryArray of Object.values(categories.value)) {
            for (const item of categoryArray) {
                if (!item.id) continue

                result[item.id] = item
            }
        }

        return result
    })

    const currencyByCategory = computed(() => {
        const result: Record<number, Currency> = {}

        for (const [currencyID, categoriesByCurrency] of Object.entries(categories.value)) {
            const currency = currencies.findCurrency(Number(currencyID))
            if (!currency) continue

            for (const item of categoriesByCurrency) {
                result[Number(item.id)] = currency
            }
        }

        return result
    })

    function getData() {
        ready.value = false

        axios.get(`/web-api/extensions/budgets/${route.params.id}`)
            .then(response => response.data)
            .then(data => {
                budgetInformation.value = data.budget
                budgetEntries.value = data.budget_entries
                budgetEntriesCopy.value = _.cloneDeep(budgetEntries.value)

                categories.value = data.categories
                budgetIDs.value = data.budgets

                currentIncomeValues.value = data.current_income_values
                currentExpenseValues.value = data.current_expenses_values

                currentBudgetIndex.value = budgetIDs.value.findIndex(item => item == Number(route.params.id)) + 1

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
            })
    }

    function reset() {
        budgetEntries.value = _.cloneDeep(budgetEntriesCopy.value)
    }

    function update() {
        loading.value.submit = true

        axios
            .patch(`/web-api/extensions/budgets/${route.params.id}/entries`, {
                entries: budgetEntries.value,
            })
            .then(() => {
                budgetEntriesCopy.value = _.cloneDeep(budgetEntries.value)
                status.showSuccess("updated budget")
                loading.value.submit = false
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.submit = false, 2000)
            })
    }

    return {
        budgetEntries,
        budgetInformation,
        categories,
        currentIncomeValues,
        currentExpenseValues,
        categoryByID,
        currencyByCategory,
        getData,
        reset,
        update,
    }
}

function useStatistics() {
    interface BudgetBalance {
        currency: string
        current: number
        target: number
    }

    const usedCategoriesByCurrency = computed(() => {
        const usedCategoryIDs = new Set<number>()
        for (const item of budgetEntries.value) {
            if (item.category_id) {
                usedCategoryIDs.add(item.category_id)
            }
        }

        const result: Record<number, Set<number>> = {}
        for (const [currencyID, categoriesForCurrency] of Object.entries(categories.value)) {
            for (const item of categoriesForCurrency) {
                if (!item.id || !usedCategoryIDs.has(item.id)) continue

                if (Number(currencyID) in result) {
                    result[Number(currencyID)].add(item.id)
                } else {
                    result[Number(currencyID)] = new Set([item.id])
                }
            }
        }

        return result
    })

    const budgetBalance = computed(() => {
        const result: BudgetBalance[] = []

        for (const [currencyID, categoriesForCurrency] of Object.entries(usedCategoriesByCurrency.value)) {
            const entries = budgetEntries.value
                .filter(item => categoriesForCurrency.has(item.category_id))

            result.push({
                currency: currencies.findCurrency(Number(currencyID))?.ISO ?? "",
                current: entries
                    .map(
                        item => item.transaction_type == "income" ?
                            currentIncomeValues.value[item.category_id] :
                            -currentExpenseValues.value[item.category_id],
                    )
                    .map(item => item || 0)
                    .reduce((carry, item) => carry + item, 0),
                target: entries
                    .map(item => item.value * (item.transaction_type == "income" ? 1 : -1))
                    .reduce((carry, item) => carry + item, 0),
            })
        }

        return result
    })

    const incomeBalance = computed(() => {
        const result: BudgetBalance[] = []

        for (const [currencyID, categoriesForCurrency] of Object.entries(usedCategoriesByCurrency.value)) {
            const entries = budgetEntries.value
                .filter(item => categoriesForCurrency.has(item.category_id))
                .filter(item => item.transaction_type == "income")

            result.push({
                currency: currencies.findCurrency(Number(currencyID))?.ISO ?? "",
                current: entries
                    .map(item => currentIncomeValues.value[item.category_id])
                    .map(item => item || 0)
                    .reduce((carry, item) => carry + item, 0),
                target: entries
                    .map(item => Number(item.value))
                    .reduce((carry, item) => carry + item, 0),
            })
        }

        return result
    })

    const expenseBalance = computed(() => {
        const result: BudgetBalance[] = []

        for (const [currencyID, categoriesForCurrency] of Object.entries(usedCategoriesByCurrency.value)) {
            const entries = budgetEntries.value
                .filter(item => categoriesForCurrency.has(item.category_id))
                .filter(item => item.transaction_type == "expenses")

            result.push({
                currency: currencies.findCurrency(Number(currencyID))?.ISO ?? "",
                current: entries
                    .map(item => currentExpenseValues.value[item.category_id])
                    .map(item => item || 0)
                    .reduce((carry, item) => carry + item, 0),
                target: entries
                    .map(item => Number(item.value))
                    .reduce((carry, item) => carry + item, 0),
            })
        }

        return result
    })

    return {budgetBalance, incomeBalance, expenseBalance}
}

const {loading, canSubmit, ready} = useComponentState()
const {currentBudgetIndex, budgetIDs} = useNavigation()
const {
    budgetEntries,
    budgetInformation,
    categories,
    currentIncomeValues,
    currentExpenseValues,
    categoryByID,
    currencyByCategory,
    getData,
    reset,
    update,
} = useBudgetData()
const {budgetBalance, incomeBalance, expenseBalance} = useStatistics()

onMounted(getData)
</script>
