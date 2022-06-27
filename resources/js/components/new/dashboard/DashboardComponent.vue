<template>
    <div v-if="ready">
        <v-row>
            <v-col lg="4" md="5" cols="12">
                <div class="sticky-panel">
                    <v-card class="mb-4">
                        <v-card-title class="font-weight-bold justify-center text-h5">Current balance</v-card-title>

                        <v-card-text>
                            <div :class="['text-h4', 'text-center', 'font-weight-regular', 'mb-6', $vuetify.theme.dark ? 'white--text' : 'black--text']">
                                {{ currentBalanceSum | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}
                            </div>

                            <v-simple-table v-if="currentBalance.length > 1">
                                <template v-slot:default>
                                    <tbody>
                                        <tr v-for="(item, i) in currentBalance" :key="i">
                                            <td class="text-right text-h6">
                                                {{ item.name | addNoBreakSpaces }}
                                            </td>

                                            <td class="text-h6">
                                                {{ item.balance | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>
                        </v-card-text>
                    </v-card>

                    <v-card>
                        <v-card-title>
                            <v-row>
                                <v-col cols="12" :sm="currentBalance.length && 6" :class="[
                                    'd-flex', 'align-center',
                                    !currentBalance.length && 'justify-center'
                                ]">
                                    <span :class="['font-weight-bold', 'text-h5']">Last 30 days</span>
                                </v-col>

                                <v-col sm="6" cols="12" v-if="currentBalance.length">
                                    <v-select
                                        v-model="currentChart"
                                        :items="chartTypes"
                                        item-value="id"
                                        item-text="name"
                                        dense
                                        outlined
                                        hide-details
                                    ></v-select>
                                </v-col>
                            </v-row>
                        </v-card-title>

                        <div class="mx-3" v-if="currentBalance.length">
                            <BalanceHistoryChartComponent v-if="currentChart == 1" :id="1"></BalanceHistoryChartComponent>

                            <DataByTypeChartComponent v-else-if="currentChart <= 5" :id="currentChart"></DataByTypeChartComponent>
                        </div>

                        <v-card-text>
                            <v-simple-table>
                                <template v-slot:default>
                                    <tbody>
                                        <tr>
                                            <td class="text-right text-h6">
                                                Income
                                            </td>

                                            <td class="text-h6">
                                                {{ last30Days.income | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-right text-h6">
                                                Outcome
                                            </td>

                                            <td class="text-h6">
                                                {{ last30Days.outcome | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-right text-h6">
                                                Total
                                            </td>

                                            <td :class="[
                                                'text-h6',
                                                last30DaysSummarySign == '+' && 'success--text',
                                                last30DaysSummarySign == '-' && 'error--text'
                                            ]">
                                                {{ last30DaysSummarySign }}{{ Math.round(Math.abs(last30Days.income - last30Days.outcome) * 100) / 100 | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>
                        </v-card-text>
                    </v-card>
                </div>
            </v-col>

            <v-col lg="8" md="7" cols="12">
                <v-card class="sticky-panel loading-height">
                    <v-card-title class="font-weight-bold justify-center text-h5">Recent transactions</v-card-title>

                    <v-card-text>
                        <RecentTransactionsComponent></RecentTransactionsComponent>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>

    <v-overlay v-else :value="true" opacity="1" absolute>
        <v-progress-circular
            indeterminate
            size="96"
        ></v-progress-circular>
    </v-overlay>
</template>

<script>
import { useCurrenciesStore } from "&/stores/currencies";
import main from "&/mixins/main";

import BalanceHistoryChartComponent from "@/dashboard/BalanceHistoryChartComponent.vue";
import DataByTypeChartComponent from "@/dashboard/DataByTypeChartComponent.vue";
import RecentTransactionsComponent from "@/dashboard/RecentTransactionsComponent.vue";

export default {
    components: {
        BalanceHistoryChartComponent,
        DataByTypeChartComponent,
        RecentTransactionsComponent
    },
    mixins: [main],
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    data() {
        return {
            chartTypes: [],
            currentChart: null,
            currentBalance: [],
            last30Days: {},

            ready: false
        }
    },
    computed: {
        last30DaysSummarySign() {
            const sum = this.last30Days.income - this.last30Days.outcome;
            return sum > 0 ? "+" : ( sum < 0 ? "-" : "" )
        },
        currentBalanceSum() {
            return this.currentBalance.length ?
                this.currentBalance
                    .map(item => item.balance)
                    .reduce((item1, item2) => item1 + item2)
                : 0
        }
    },
    methods: {
        getBalanceData() {
            this.ready = false;

            axios
                .get(`/web-api/dashboard/${this.currencies.usedCurrency}`)
                .then(response => {
                    const data = response.data;
                    this.currentBalance = data.currentBalance;
                    this.last30Days = data.last30Days;
                    this.chartTypes = data.charts;
                    if (data.charts.length) {
                        this.currentChart = data.charts[0].id
                    }

                    this.ready = true;
                })
        }
    },
    mounted() {
        this.getBalanceData()
        this.currencies.$subscribe(() => this.getBalanceData());
    }
}
</script>
