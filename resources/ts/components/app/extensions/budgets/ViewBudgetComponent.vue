<template>
    <div v-if="ready && budgetInformation">
        <v-card class="mb-3">
            <CardTitleWithButtons
                :title="budgetInformation.title"
                large-font
                extra-bottom
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
                <v-row
                    v-if="budgetEntries.length"
                    class="pagination-fixed-margin"
                >
                    <v-col
                        cols="12"
                        lg="6"
                        class="pr-lg-7"
                    >
                        <h4 class="text-h4 text-center">
                            Income
                        </h4>

                        <v-row>
                            <v-col
                                v-for="item in budgetEntries.filter(x => x.transaction_type == 'income')"
                                cols="12"
                                lg="6"
                            >
                                <BudgetEntryComponent
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
                        <h4 class="text-h4 text-center">
                            Expenses
                        </h4>

                        <v-row>
                            <v-col
                                v-for="item in budgetEntries.filter(x => x.transaction_type == 'expenses')"
                                cols="12"
                                xl="6"
                            >
                                <BudgetEntryComponent
                                    :entry="item"
                                    :current-value="currentExpenseValues[item.category_id] || 0"
                                    :category="categoryByID[item.category_id]"
                                ></BudgetEntryComponent>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>

                <div v-else>
                    <h4 class="text-h4 text-center py-4">
                        This budget is empty
                    </h4>

                    <h5 class="text-h5 text-center pb-4">
                        Click the <strong>Manage Entries</strong> button to add entries
                    </h5>
                </div>
            </v-card-text>
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
import axios from "axios"
import { computed, onMounted, ref, watch } from "vue"
import { useRoute, useRouter } from "vue-router"

import type { BudgetEntry, BudgetInformation } from "@interfaces/Budget"
import type { Category } from "@interfaces/Category"

import EditBudgetDialogComponent from "@components/app/extensions/budgets/EditBudgetDialogComponent.vue"
import AddBudgetEntriesDialogComponent from "@components/app/extensions/budgets/ManageBudgetEntriesDialogComponent.vue"

import { useStatusStore } from "@stores/status"
import useComponentState from "@composables/useComponentState"
import BudgetEntryComponent from "@components/app/extensions/budgets/BudgetEntryComponent.vue"

const status = useStatusStore()

const route = useRoute()
const router = useRouter()

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

    function getData() {
        ready.value = false

        axios.get(`/web-api/extensions/budgets/${route.params.id}`)
            .then(response => response.data)
            .then(data => {
                budgetInformation.value = data.budget
                budgetEntries.value = data.budget_entries
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

    return {
        budgetEntries,
        budgetInformation,
        categories,
        currentIncomeValues,
        currentExpenseValues,
        categoryByID,
        getData,
    }
}

const {ready} = useComponentState()
const {currentBudgetIndex, budgetIDs} = useNavigation()
const {
    budgetEntries,
    budgetInformation,
    categories,
    currentIncomeValues,
    currentExpenseValues,
    categoryByID,
    getData,
} = useBudgetData()

onMounted(getData)
</script>
