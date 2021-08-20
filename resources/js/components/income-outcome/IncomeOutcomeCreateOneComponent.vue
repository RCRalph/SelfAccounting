<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i
                    :class="[
                        'fas',
                        type == 'income' ? 'fa-sign-in-alt' : 'fa-sign-out-alt'
                    ]"
                ></i>
                Add single {{ type }}
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <CreateForm
                    v-model="data"
                    :currencies="currencies"
                    :categories="categories"
                    :means="means"
                    :titles="titles"
                ></CreateForm>

                <div v-if="cashMeanUsed">
                    <hr class="hr">

                    <IncomeOutcomeCashComponent
                        :currencies="currencies"
                        :used="[data.currency_id]"
                        :cash="cash"
                        :sums="sumObject"
                        :type="type"
                        :usersCash="usersCash"
                        v-model="cashUsed"
                    ></IncomeOutcomeCashComponent>
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
import Loading from "../Loading.vue";
import CreateForm from "./CreateFormComponent.vue";
import IncomeOutcomeCashComponent from "../../bundles/cash/components/IncomeOutcomeCashComponent.vue";

export default {
    props: ["type"],
    components: {
        Loading,
        CreateForm,
        IncomeOutcomeCashComponent
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
                title: "",
                amount: "",
                price: "",
                currency_id: "",
                category_id: "",
                mean_id: ""
            },
            cashUsed: {}
        }
    },
    computed: {
        minDate() {
            const meansForCurrency = this.means[this.data.currency_id];
            if (meansForCurrency == undefined) {
                return "1970-01-01";
            }
			const currentMean = meansForCurrency.filter(item => item.id == this.data.mean_id);
			return (currentMean.length && currentMean.id != null) ? currentMean[0].first_entry_date : "1970-01-01";
        },
        canSubmit() {
            const validDate = this.data.date !== "" &&
                !isNaN(Date.parse(this.data.date)) &&
                new Date(this.data.date) >= new Date(this.minDate).getTime();

            const validTitle = this.data.title.length &&
                this.data.title.length <= 64;

            const toNumber = {
                amount: Number(this.data.amount),
                price: Number(this.data.price)
            }

            const validAmount = !isNaN(toNumber.amount) &&
                toNumber.amount <= 1e7 - 0.001 &&
                toNumber.amount > 0;

            const validPrice = !isNaN(toNumber.price) &&
                toNumber.price <= 1e11 - 0.01 &&
                toNumber.price > 0;

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
            return this.data.mean_id == this.cashMeans[this.data.currency_id];
        },
        sumObject() {
            let retObj = {}
            retObj[this.data.currency_id] = Math.round(this.data.amount * this.data.price * 100) / 100;
            return retObj;
        }
    },
    methods: {
        submit() {
            this.submitted = true;

            let submitObj = {
                data: [this.data]
            }

            if (this.cashMeanUsed) {
                submitObj.cash = [];
                this.cash[this.data.currency_id]
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

            axios
                .post(`/webapi/${this.type}/store`, submitObj)
                .then(() => {
                    window.location.href = `/${this.type}`
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

            return amount >= 0 && Math.floor(amount) == amount && amount < Math.pow(2, 63) &&
                (this.type == "outcome" && (this.usersCash[id] == undefined || this.usersCash[id] >= amount) || this.type == "income");
        }
    },
    mounted() {
        axios
            .get(`/webapi/${this.type}/create`, {})
            .then(response => {
                const data = response.data.data;

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

                this.currencies = data.currencies;
                this.categories = data.categories;
                this.means = data.means;
                this.titles = data.titles;

                this.data.currency_id = data.last.currency;
                this.data.category_id = (this.categories[data.last.currency] != undefined &&
                        this.categories[data.last.currency].find(item => item.id == data.last.category)) ? data.last.category : null,
                this.data.mean_id = (this.means[data.last.currency] != undefined &&
                        this.means[data.last.currency].find(item => item.id == data.last.mean)) ? data.last.mean : null
                this.data.amount = "1";

                this.data.date = (new Date(this.minDate).getTime() > new Date().getTime() ?
                    new Date(this.minDate) : new Date())
                    .toISOString().split("T")[0];

                this.ready = true;
            });
    }
}
</script>
