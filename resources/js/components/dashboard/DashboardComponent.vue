<template>
    <div v-if="ready">
        <v-row>
            <v-col xl="4" cols="12" class="pb-xl-3 pr-xl-0 pb-0 pr-4">
                <div class="overview-block" style="border-radius: 4px">
                    <v-row no-gutters>
                        <v-col xl="12" lg="4" md="6" cols="12" class="pb-4 pb-md-0 pb-xl-4 pr-xl-0 pr-md-3 pr-0">
                            <v-card class="d-flex justify-center flex-column" height="100%">
                                <v-card-title class="justify-center text-h5">Current balance</v-card-title>

                                <v-card-text>
                                    <div class="text-h4 text-center font-weight-regular mb-6" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">
                                        {{ currentBalanceSum | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}
                                    </div>

                                    <v-simple-table v-if="currentBalance.length > 1">
                                        <template v-slot:default>
                                            <tbody>
                                                <tr v-for="(item, i) in currentBalance" :key="i">
                                                    <td class="text-right text-h6" style="width: 50%">
                                                        <div class="d-flex justify-start align-center">
                                                            <div class="mr-2">
                                                                <v-icon style="min-width: 24px">{{ item.icon }}</v-icon>
                                                            </div>

                                                            <div class="d-flex justify-end align-center" style="width: 100%">
                                                                {{ item.name | addNoBreakSpaces }}
                                                            </div>
                                                        </div>
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
                        </v-col>

                        <v-col xl="12" lg="8" md="6" cols="12">
                            <v-card class="d-flex flex-column justify-space-between" height="100%">
                                <v-card-title>
                                    <v-row>
                                        <v-col cols="12" :sm="currentBalance.length && 6" :class="[
                                            'd-flex', 'align-center',
                                            !currentBalance.length && 'justify-center'
                                        ]">
                                            <span class="text-h5">Last 30 days</span>
                                        </v-col>

                                        <v-col sm="6" cols="12" v-if="currentBalance.length">
                                            <v-select
                                                v-model="currentChart"
                                                :items="charts"
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
                                    <BalanceHistoryChartComponent v-if="chartType == 'line'" :id="currentChart"></BalanceHistoryChartComponent>

                                    <DataByTypeChartComponent v-else-if="chartType == 'doughnut'" :id="currentChart"></DataByTypeChartComponent>
                                </div>

                                <v-card-text>
                                    <v-simple-table>
                                        <template v-slot:default>
                                            <tbody>
                                                <tr>
                                                    <td class="text-right text-h6" style="width: 50%">
                                                        Income
                                                    </td>

                                                    <td class="text-h6">
                                                        {{ last30Days.income | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-right text-h6">
                                                        Expences
                                                    </td>

                                                    <td class="text-h6">
                                                        {{ last30Days.expences | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-right text-h6">
                                                        Incoming transfers
                                                    </td>

                                                    <td class="text-h6">
                                                        {{ last30Days.transfersIn | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-right text-h6">
                                                        Outgoing transfers
                                                    </td>

                                                    <td class="text-h6">
                                                        {{ last30Days.transfersOut | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}
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
                                                        {{ last30DaysSummarySign }}{{ Math.round(Math.abs(last30DaysTotal) * 100) / 100 | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </template>
                                    </v-simple-table>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </div>
            </v-col>

            <v-col xl="8" cols="12">
                <v-card class="loading-height">
                    <v-card-title class="justify-center text-h5">Recent transactions</v-card-title>

                    <v-card-text>
                        <RecentTransactionsComponent
                            :categories="categories"
                            :accounts="accounts"
                        ></RecentTransactionsComponent>
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
            charts: [],
            categories: [],
            accounts: [],
            currentChart: null,
            currentBalance: [],
            last30Days: {},

            ready: false
        }
    },
    computed: {
        last30DaysTotal() {
            return this.last30Days.income + this.last30Days.transfersIn - this.last30Days.expences - this.last30Days.transfersOut;
        },
        last30DaysSummarySign() {
            return this.last30DaysTotal > 0 ? "+" : ( this.last30DaysTotal < 0 ? "-" : "" )
        },
        currentBalanceSum() {
            return this.currentBalance.length ?
                this.currentBalance
                    .map(item => item.balance)
                    .reduce((item1, item2) => item1 + item2)
                : 0
        },
        chartType() {
            return this.ready ? this.charts.find(item => item.id == this.currentChart).type : ""
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

                    this.categories = data.categories;
                    this.accounts = data.accounts;

                    this.charts = data.charts;
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
