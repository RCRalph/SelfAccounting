<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-exchange-alt"></i>
                Exchange means of payment
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <ExchangeDataComponent
                    :titles="titles"
                    :mindate="minDate"
                    v-model="data"
                ></ExchangeDataComponent>

                <hr class="hr-dashed">

                <div class="text-center h2 font-weight-bold mb-3">Income:</div>
                <ExchangeIncomeOutcomeComponent
                    :currencies="currencies"
                    :categories="categories"
                    :means="means"
                    :samemeans="sameMeansOfPayment"
                    v-model="income"
                ></ExchangeIncomeOutcomeComponent>

                <hr class="hr-dashed">

                <div class="text-center h2 font-weight-bold mb-3">Outcome:</div>
                <ExchangeIncomeOutcomeComponent
                    :currencies="currencies"
                    :categories="categories"
                    :means="means"
                    :samemeans="sameMeansOfPayment"
                    v-model="outcome"
                ></ExchangeIncomeOutcomeComponent>

                <div v-if="cashMeanUsed.length">
                    <hr class="hr">

                    <CashExchangeComponent
                        :currencies="currencies"
                        :used="cashMeanUsed"
                        :cash="cash"
                        :incomeSum="incomeSum"
                        :outcomeSum="outcomeSum"
                        :incomeCurrency="Number(cashMeans[income.currency_id] == income.mean_id && income.currency_id)"
                        :outcomeCurrency="Number(cashMeans[outcome.currency_id] == outcome.mean_id && outcome.currency_id)"
                        :usersCash="usersCash"
                        v-model="cashUsed"
                    ></CashExchangeComponent>
                </div>

                <hr class="hr">

                <div class="row">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <button
                            type="button"
                            class="big-button-success"
                            @click="submit"
                            :disabled="submitted || !canSubmit"
                        >
                            <div v-if="!submitted">Submit</div>

                            <span
                                v-else
                                class="spinner-border spinner-border-sm"
                                role="status"
                                aria-hidden="true"
                            ></span>
                        </button>
                    </div>
                </div>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import Loading from "../../Loading.vue";
import ExchangeDataComponent from "./ExchangeDataComponent.vue";
import ExchangeIncomeOutcomeComponent from "./ExchangeIncomeOutcomeComponent.vue";
import CashExchangeComponent from "../../../bundles/cash/components/CashExchangeComponent.vue";

export default {
    props: ["type"],
    components: {
        Loading,
        ExchangeDataComponent,
        ExchangeIncomeOutcomeComponent,
        CashExchangeComponent
    },
    data() {
        return {
            ready: false,
            submitted: false,

            currencies: {},
            categories: {},
            means: {},
            titles: [],
            cash: {},
            cashMeans: {},
            usersCash: [],

            data: {
                date: "",
                title: ""
            },
            income: {
                amount: "",
                price: "",
                currency_id: 0,
                category_id: null,
                mean_id: null
            },
            outcome: {
                amount: "",
                price: "",
                currency_id: 0,
                category_id: null,
                mean_id: null
            },

            cashUsed: {}
        }
    },
    computed: {
        minDate() {
            const meansForCurrencyIncome = this.means[this.income.currency_id],
                meansForCurrencyOutcome = this.means[this.outcome.currency_id];

            if (meansForCurrencyIncome == undefined && meansForCurrencyOutcome == undefined) {
                return "1970-01-01";
            }

			const currentMeanIncome = meansForCurrencyIncome.find(item => item.id == this.income.mean_id),
                currentMeanOutcome = meansForCurrencyOutcome.find(item => item.id == this.outcome.mean_id);

            let retVal;
            if (currentMeanIncome && currentMeanOutcome) {
                const epochIncome = Date.parse(currentMeanIncome.first_entry_date),
                    epochOutcome = Date.parse(currentMeanOutcome.first_entry_date);
                retVal = epochIncome > epochOutcome ?
                    currentMeanOutcome.first_entry_date : currentMeanIncome.first_entry_date;
            }
            else if (currentMeanIncome) {
                retVal = currentMeanIncome.first_entry_date;
            }
            else if (currentMeanOutcome) {
                retVal = currentMeanOutcome.first_entry_date;
            }
            else {
                retVal = "1970-01-01";
            }

            return retVal;
        },
        canSubmit() {
            if (this.sameMeansOfPayment) {
                return false;
            }

            const validDate = this.data.date !== "" &&
                !isNaN(Date.parse(this.data.date)) &&
                new Date(this.data.date) >= new Date(this.minDate).getTime();

            const validTitle = this.data.title.length &&
                this.data.title.length <= 64;

            let toNumber = {
                amountIncome: this.income.amount,
                amountOutcome: this.outcome.amount,
                priceIncome: this.income.price,
                priceOutcome: this.outcome.price
            }

            for (let i in toNumber) {
                toNumber[i] = Number(toNumber[i]);
            }

            const validAmount =
                !isNaN(toNumber.amountIncome) &&
                toNumber.amountIncome <= 1e6 &&
                toNumber.amountIncome > 0 &&
                !isNaN(toNumber.amountOutcome) &&
                toNumber.amountOutcome <= 1e6 &&
                toNumber.amountOutcome > 0;

            const validPrice =
                !isNaN(toNumber.priceIncome) &&
                toNumber.priceIncome <= 1e11 &&
                toNumber.priceIncome > 0 &&
                !isNaN(toNumber.priceOutcome) &&
                toNumber.priceOutcome <= 1e11 &
                toNumber.priceOutcome > 0;

            if (this.cashMeanUsed) {
                for (let i in this.cashUsed) {
                    if (!this.isValidCashAmount(this.cashUsed[i], i)) {
                        return false;
                    }
                }
            }

            return validDate && validTitle && validAmount && validPrice;
        },
        cashMeanUsed() {
            let retArr = [];

            if (this.income.mean_id != null &&
                this.income.mean_id == this.cashMeans[this.income.currency_id]
            ) {
                retArr.push(this.income.currency_id);
            }

            if (this.outcome.mean_id != null &&
                this.outcome.mean_id == this.cashMeans[this.outcome.currency_id]
            ) {
                retArr.push(this.outcome.currency_id);
            }

            return retArr;
        },
        incomeSum() {
            return Math.round(this.income.amount * this.income.price * 100) / 100;
        },
        outcomeSum() {
            return Math.round(this.outcome.amount * this.outcome.price * 100) / 100;
        },
        sameMeansOfPayment() {
            return this.income.mean_id == 0 ?
                this.income.currency_id == this.outcome.currency_id && this.outcome.mean_id == 0 :
                this.income.mean_id == this.outcome.mean_id;
        }
    },
    methods: {
        submit() {
            this.submitted = true;

            let submitObj = {
                data: this.data,
                income: this.income,
                outcome: this.outcome
            }

            if (this.cashMeanUsed) {
                submitObj.cash = [];

                if (this.cashMeans[this.income.currency_id] == this.income.mean_id) {
                    this.cash[this.income.currency_id]
                        .map(item => item.id)
                        .forEach(item => {
                            if (this.cashUsed[item] > 0) {
                                submitObj.cash.push({
                                    id: item,
                                    amount: this.cashUsed[item]
                                })
                            }
                        })
                }

                if (this.cashMeans[this.outcome.currency_id] == this.outcome.mean_id) {
                    this.cash[this.outcome.currency_id]
                        .map(item => item.id)
                        .forEach(item => {
                            if (this.cashUsed[item] > 0) {
                                submitObj.cash.push({
                                    id: item,
                                    amount: this.cashUsed[item]
                                })
                            }
                        })
                }
            }

            axios
                .post(`/webapi/${this.type}/exchange`, submitObj)
                .then(() => {
                    //window.location.href = `/${this.type}`
                    this.submitted = false;
                })
                .catch(err => {
                    console.log(err);
                    this.submitted = false;
                })
        },
        isValidCashAmount(amount, id) {
            if (amount === "") {
                return false;
            }

            amount = Number(amount);
            if (isNaN(amount)) {
                return false;
            }

            if (!(amount >= 0 && Math.floor(amount) == amount && amount < Math.pow(2, 63))) {
                return false;
            }

            // Check for valid outcome amount
            if (this.cash[this.outcome.currency_id].find(item => item.id == id)) {
                return this.usersCash[id] == undefined || this.usersCash[id] >= amount;
            }

            return true;
        }
    },
    mounted() {
        axios
            .get(`/webapi/${this.type}/create`, {})
            .then(response => {
                const data = response.data.data;

                this.currencies = data.currencies;
                this.categories = data.categories;
                this.means = data.means;
                this.titles = data.titles;

                this.income.currency_id = data.last.currency;
                this.income.category_id = data.last.category || null;
                this.income.mean_id = data.last.mean || null;

                this.outcome.currency_id = data.last.currency;

                if (data.cash != undefined) {
                    this.cash = data.cash;
                    this.cashMeans = data.cashMeans;
                    this.usersCash = data.usersCash;

                    // This way the values inside this.cashUsed will be getters and setters instead of actual values
                    const tempCashValues = {};
                    for (let i in this.cash) {
                        this.cash[i].forEach(item => {
                            tempCashValues[item.id] = 0;
                        })
                    }
                    this.cashUsed = tempCashValues;
                }

                this.data.date = (new Date(this.minDate).getTime() > new Date().getTime() ?
                    new Date(this.minDate) : new Date())
                    .toISOString().split("T")[0];

                this.ready = true;
            });
    }
}
</script>
