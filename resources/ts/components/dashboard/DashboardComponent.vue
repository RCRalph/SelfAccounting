<template>
    <div
        v-if="ready"
        style="margin: 12px"
    >
        <v-row>
            <v-col
                xl="4" cols="12"
                class="pb-xl-3 pr-xl-0 pb-0 pr-4"
            >
                <div
                    class="overview-block"
                    style="border-radius: 4px"
                >
                    <v-row :no-gutters="true">
                        <v-col
                            xl="12" lg="4" md="6" cols="12"
                            class="pb-4 pb-md-0 pb-xl-4 pr-xl-0 pr-md-3 pr-0"
                        >
                            <v-card class="h-100">
                                <div class="d-flex justify-center flex-column w-100 h-100">
                                    <div>
                                        <v-card-title class="text-center text-h5 py-4">
                                            Current balance
                                        </v-card-title>

                                        <v-card-text>
                                            <div
                                                class="text-h4 text-center font-weight-regular mb-6"
                                                :class="themeIsDark ? 'white--text' : 'black--text'"
                                            >
                                                {{
                                                    formats.numberWithCurrency(
                                                        currentBalanceSum,
                                                        currencies.usedCurrencyObject.ISO,
                                                    )
                                                }}
                                            </div>

                                            <v-table v-if="currentBalance.length > 1" density="comfortable">
                                                <tbody>
                                                    <tr
                                                        v-for="(item, i) in currentBalance"
                                                        :key="i"
                                                    >
                                                        <td
                                                            class="text-right text-h6"
                                                            style="width: 50%"
                                                        >
                                                            <div class="d-flex justify-start align-center">
                                                                <div class="mr-2">
                                                                    <v-icon style="min-width: 24px">
                                                                        {{ formats.iconName(item.icon) }}
                                                                    </v-icon>
                                                                </div>

                                                                <div class="d-flex justify-end align-center w-100">
                                                                    {{ formats.textWithNBSP(item.name) }}
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td class="text-h6">
                                                            {{
                                                                formats.numberWithCurrency(
                                                                    item.balance,
                                                                    currencies.usedCurrencyObject.ISO,
                                                                )
                                                            }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </v-table>
                                        </v-card-text>
                                    </div>
                                </div>
                            </v-card>
                        </v-col>

                        <v-col
                            xl="12" lg="8" md="6" cols="12"
                        >
                            <v-card
                                class="d-flex flex-column justify-space-between"
                                height="100%"
                                style="position: relative"
                            >
                                <v-card-title>
                                    <v-row>
                                        <v-col
                                            cols="12"
                                            :sm="currentBalance.length && 6"
                                            :class="[
                                                'd-flex',
                                                'align-center',
                                                !currentBalance.length && 'justify-center'
                                            ]"
                                        >
                                            <span class="text-h5">Last 30 days</span>
                                        </v-col>

                                        <v-col
                                            v-if="currentBalance.length"
                                            sm="6"
                                            cols="12"
                                        >
                                            <v-select
                                                v-model="currentChart"
                                                :items="charts"
                                                :hide-details="true"
                                                variant="outlined"
                                                density="compact"
                                                item-value="id"
                                                item-title="name"
                                            ></v-select>
                                        </v-col>
                                    </v-row>
                                </v-card-title>

                                <div
                                    v-if="currentBalance.length && currentChart != undefined"
                                    class="mx-3"
                                >
                                    <BalanceHistoryChartComponent
                                        v-if="chartType == 'line'"
                                        :id="currentChart"
                                    ></BalanceHistoryChartComponent>

                                    <DataByTypeChartComponent
                                        v-else-if="chartType == 'doughnut'"
                                        :id="currentChart"
                                    ></DataByTypeChartComponent>
                                </div>

                                <v-card-text>
                                    <v-table>
                                        <tbody>
                                            <tr>
                                                <td
                                                    class="text-right text-h6"
                                                    style="width: 50%"
                                                >
                                                    Income
                                                </td>

                                                <td class="text-h6">
                                                    {{
                                                        formats.numberWithCurrency(
                                                            last30Days.income,
                                                            currencies.usedCurrencyObject.ISO,
                                                        )
                                                    }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-right text-h6">
                                                    Expenses
                                                </td>

                                                <td class="text-h6">
                                                    {{
                                                        formats.numberWithCurrency(
                                                            last30Days.expenses,
                                                            currencies.usedCurrencyObject.ISO,
                                                        )
                                                    }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-right text-h6">
                                                    Incoming transfers
                                                </td>

                                                <td class="text-h6">
                                                    {{
                                                        formats.numberWithCurrency(
                                                            last30Days.transfersIn,
                                                            currencies.usedCurrencyObject.ISO,
                                                        )
                                                    }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-right text-h6">
                                                    Outgoing transfers
                                                </td>

                                                <td class="text-h6">
                                                    {{
                                                        formats.numberWithCurrency(
                                                            last30Days.transfersOut,
                                                            currencies.usedCurrencyObject.ISO,
                                                        )
                                                    }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-right text-h6">
                                                    Total
                                                </td>

                                                <td :class="[
                                                    'text-h6',
                                                    last30DaysTotal > 0 && 'text-success',
                                                    last30DaysTotal < 0 && 'text-error'
                                                ]">
                                                    {{
                                                        formats.numberWithCurrency(
                                                            last30DaysTotal,
                                                            currencies.usedCurrencyObject.ISO,
                                                            true,
                                                        )
                                                    }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </v-table>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </div>
            </v-col>

            <v-col xl="8" cols="12">
                <v-card class="loading-height">
                    <v-card-title class="text-center text-h5">
                        Recent transactions
                    </v-card-title>

                    <v-card-text>
                        <RecentTransactionsComponent
                            :accounts="accounts"
                            :categories="categories"
                            @updated="getBalanceData"
                        ></RecentTransactionsComponent>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>

    <v-overlay
        v-else
        :model-value="true"
        :contained="true"
    >
        <v-progress-circular
            indeterminate
            size="128"
        ></v-progress-circular>
    </v-overlay>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref, computed, onMounted } from "vue"
import type { Ref } from "vue"

import type { CurrentBalance, Chart } from "@interfaces/Dashboard"
import type { Category } from "@interfaces/Category"
import type { Account } from "@interfaces/Account"

import BalanceHistoryChartComponent from "@components/dashboard/BalanceHistoryChartComponent.vue"
import DataByTypeChartComponent from "@components/dashboard/DataByTypeChartComponent.vue"
import RecentTransactionsComponent from "@components/dashboard/RecentTransactionsComponent.vue"

import { useCurrenciesStore } from "@stores/currencies"
import { useStatusStore } from "@stores/status"
import useFormats from "@composables/useFormats"
import useThemeSettings from "@composables/useThemeSettings"

const currencies = useCurrenciesStore()
const status = useStatusStore()
const formats = useFormats()
const {themeIsDark} = useThemeSettings()

function useCurrentBalance() {
    const currentBalance: Ref<CurrentBalance[]> = ref([])

    const currentBalanceSum = computed(() => {
        if (currentBalance.value.length) {
            return currentBalance.value
                .map(item => item.balance)
                .reduce((item1, item2) => item1 + item2)
        } else return 0
    })

    return {currentBalance, currentBalanceSum}
}

function useLast30Days() {
    const last30Days = ref({
        income: 0,
        expenses: 0,
        transfersIn: 0,
        transfersOut: 0,
    })

    const last30DaysTotal = computed(() =>
        last30Days.value.income -
        last30Days.value.expenses +
        last30Days.value.transfersIn -
        last30Days.value.transfersOut,
    )

    return {last30Days, last30DaysTotal}
}

function useCharts() {
    const currentChart: Ref<number | undefined> = ref(undefined)

    const charts: Ref<Chart[]> = ref([])

    const chartType = computed(() => {
        if (ready.value) {
            const foundChart = charts.value.find(item => item.id == currentChart.value)

            if (typeof foundChart != "undefined") {
                return foundChart.type
            }
        }

        return ""
    })

    return {chartType, charts, currentChart}
}

function useRecentTransactions() {
    const accounts: Ref<Account[]> = ref([])
    const categories: Ref<Category[]> = ref([])

    return {accounts, categories}
}

function getBalanceData() {
    ready.value = false

    axios.get(`/web-api/dashboard/${currencies.usedCurrency}`)
        .then(response => {
            const data = response.data

            currentBalance.value = data.currentBalance
            last30Days.value = data.last30Days
            charts.value = data.charts

            if (charts.value.length) {
                currentChart.value = charts.value[0].id
            }

            accounts.value = data.accounts
            categories.value = data.categories

            ready.value = true
        })
        .catch(err => {
            console.error(err)
            setTimeout(() => status.showError(), 1000)
        })
}

const ready = ref(false)
const {currentBalance, currentBalanceSum} = useCurrentBalance()
const {last30Days, last30DaysTotal} = useLast30Days()
const {chartType, charts, currentChart} = useCharts()
const {accounts, categories} = useRecentTransactions()

onMounted(() => {
    getBalanceData()
    currencies.$subscribe(() => getBalanceData())
})
</script>
