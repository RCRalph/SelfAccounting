<template>
    <v-dialog
        v-model="dialog"
        max-width="800"
        persistent
    >
        <template v-slot:activator="{ props: dialogProps }">
            <v-btn
                v-bind="dialogProps"
                variant="outlined"
                text="Manage entries"
            ></v-btn>
        </template>

        <v-card>
            <CardTitleWithButtons title="Manage entries" large-font></CardTitleWithButtons>

            <v-card-text class="pt-0">
                <v-row no-gutters>
                    <v-col cols="12" lg="4" offset-lg="4">
                        <v-select
                            v-model="currentCurrency"
                            :items="availableCurrencies"
                            item-title="ISO"
                            item-value="id"
                            variant="underlined"
                            label="Currency"
                            class="py-3"
                            hide-details
                        ></v-select>
                    </v-col>
                </v-row>

                <v-row no-gutters>
                    <v-col
                        v-for="(item, i) in props.categories[currentCurrency]"
                        cols="6"
                        class="d-flex"
                        :class="i % 2 ? 'pl-3' : 'pr-3'"
                    >
                        <v-row class="ma-0">
                            <v-col cols="1" class="d-flex align-center">
                                <v-icon :icon="formats.iconName(item.icon)" class="mr-2"></v-icon>
                            </v-col>

                            <v-col cols="7" class="d-flex align-center">
                                {{ item.name }}
                            </v-col>

                            <v-col cols="4">
                                <div class="d-flex align-center h-100 justify-space-between">
                                    <v-tooltip
                                        :text="buttonTooltip(item, 'income')"
                                        location="bottom"
                                    >
                                        <template v-slot:activator="{ props: tooltipProps }">
                                            <div v-bind="tooltipProps">
                                                <v-btn
                                                    :color="buttonColor(item, 'income')"
                                                    :disabled="!buttonColor(item, 'income')"
                                                    icon="mdi-trending-up"
                                                    variant="outlined"
                                                    density="comfortable"
                                                    @click="toggleEntry(item, 'income')"
                                                ></v-btn>
                                            </div>
                                        </template>
                                    </v-tooltip>

                                    <v-tooltip
                                        :text="buttonTooltip(item, 'expenses')"
                                        location="bottom"
                                    >
                                        <template v-slot:activator="{ props: tooltipProps }">
                                            <div v-bind="tooltipProps">
                                                <v-btn
                                                    :color="buttonColor(item, 'expenses')"
                                                    :disabled="!buttonColor(item, 'expenses')"
                                                    icon="mdi-trending-down"
                                                    variant="outlined"
                                                    density="comfortable"
                                                    @click="toggleEntry(item, 'expenses')"
                                                ></v-btn>
                                            </div>
                                        </template>
                                    </v-tooltip>
                                </div>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-card-text>

            <CardActionsResetUpdateComponent
                @reset="resetEntries"
                @update="update"
            ></CardActionsResetUpdateComponent>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { computed, ref, watch } from "vue"

import type { Category } from "@interfaces/Category"
import type { BudgetEntriesGrouped, BudgetEntry } from "@interfaces/Budget"

import { useCurrenciesStore } from "@stores/currencies"
import useComponentState from "@composables/useComponentState"
import useFormats from "@composables/useFormats"

const props = defineProps<{
    categories: Record<number, Category[]>,
}>()

const entries = defineModel<BudgetEntry[]>("entries", {required: true})

const currencies = useCurrenciesStore()
const formats = useFormats()

function useBudgetEntries() {
    const availableCurrencies = computed(
        () => currencies.selectCurrencies(Object.keys(props.categories).map(Number)),
    )

    const currentCurrency = ref<number>(currencies.usedCurrency)

    const budgetEntries = ref<BudgetEntriesGrouped>()

    function buttonTooltip(category: Category, type: keyof BudgetEntriesGrouped) {
        if (
            !budgetEntries.value || !category.id ||
            type == "income" && !category.used_in_income ||
            type == "expenses" && !category.used_in_expenses
        ) {
            return `Category is disabled for ${type}`
        } else if (category.id in budgetEntries.value[type]) {
            return `Remove from ${type}`
        } else {
            return `Add to ${type}`
        }
    }

    function buttonColor(category: Category, type: keyof BudgetEntriesGrouped) {
        if (
            !budgetEntries.value || !category.id ||
            type == "income" && !category.used_in_income ||
            type == "expenses" && !category.used_in_expenses
        ) {
            return ""
        } else if (category.id in budgetEntries.value[type]) {
            return "success"
        } else {
            return "error"
        }
    }

    function toggleEntry(category: Category, type: keyof BudgetEntriesGrouped) {
        if (
            !budgetEntries.value || !category.id ||
            type == "income" && !category.used_in_income ||
            type == "expenses" && !category.used_in_expenses
        ) return

        if (category.id in budgetEntries.value[type]) {
            delete budgetEntries.value[type][category.id]
        } else {
            budgetEntries.value[type][category.id] = {
                category_id: category.id,
                transaction_type: type,
                value: 0,
            }
        }
    }

    function resetEntries() {
        budgetEntries.value = {
            income: {},
            expenses: {},
        }

        for (const item of entries.value) {
            budgetEntries.value[item.transaction_type][item.category_id] = item
        }
    }

    function update() {
        if (!budgetEntries.value) return

        entries.value.length = 0
        for (const typeEntries of Object.values(budgetEntries.value)) {
            for (const budgetEntry of Object.values(typeEntries)) {
                entries.value.push(budgetEntry as BudgetEntry)
            }
        }

        dialog.value = false
    }

    return {availableCurrencies, currentCurrency, buttonColor, buttonTooltip, toggleEntry, resetEntries, update}
}

const {dialog} = useComponentState()
const {
    availableCurrencies,
    currentCurrency,
    buttonColor,
    buttonTooltip,
    toggleEntry,
    resetEntries,
    update,
} = useBudgetEntries()

watch(dialog, resetEntries)
</script>