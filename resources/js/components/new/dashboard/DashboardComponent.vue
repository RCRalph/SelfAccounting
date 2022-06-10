<template>
    <v-row v-if="ready">
        <v-col class="col-lg-4 col-md-6 col-12">
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
                <v-card-title class="font-weight-bold justify-center text-h5">
                    <v-row>
                        <v-col class="col-sm-6 col-12">
                            <span class="font-weight-bold justify-center text-h5">Last 30 days</span>
                        </v-col>

                        <v-col class="col-sm-6 col-12">
                            <v-select
                                v-model="currentChart"
                                :items="chartTypes"
                                dense
                                outlined
                                hide-details
                            ></v-select>
                        </v-col>
                    </v-row></v-card-title>

                <div class="mx-3">
                    <BalanceHistoryChartComponent v-if="currentChart == 1"></BalanceHistoryChartComponent>
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
                                        {{ last30Days.income | addSpaces }}&nbsp;PLN
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-right text-h6">
                                        Outcome
                                    </td>

                                    <td class="text-h6">
                                        {{ last30Days.outcome | addSpaces }}&nbsp;PLN
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
        </v-col>

        <v-col class="col-lg-8 col-md-6 col-12">

        </v-col>
    </v-row>

    <v-overlay v-else :value="true" opacity="1" absolute>
        <v-progress-circular
            indeterminate
            size="96"

        ></v-progress-circular>
    </v-overlay>
</template>

<script>
import { useCurrenciesStore } from "../../../stores/currencies";
import BalanceHistoryChartComponent from "./BalanceHistoryChartComponent.vue";

export default {
    components: {
        BalanceHistoryChartComponent
    },
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    data() {
        return {
            chartTypes: [
                {
                    value: 1,
                    text: "Balance history"
                },
                {
                    value: 2,
                    text: "Outcome by categories"
                }
            ],
            currentChart: 1,
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
    filters: {
        addSpaces(value) {
            return value
                .toLocaleString("en", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })
                .split(",")
                .join(String.fromCharCode(160));
        },
        addNoBreakSpaces(value) {
            return value.split(" ").join(String.fromCharCode(160));
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

                    this.ready = true;
                })
        }
    },
    mounted() {
        // Doesn't need calling getBalanceData() here, because the component
        // is always rendered before the parent component receives response
        this.currencies.$subscribe(() => this.getBalanceData());
    }
}
</script>
