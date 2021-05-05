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
                Add multiple {{ type }}
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <div class="h1 font-weight-bold text-center mb-3">Common values</div>

                <InputGroup
                    name="date"
                    type="date"
                    v-model="common.date"
                    :min="minDate"
                    :invalid="!validDate"
                    @input="updateData('date')"
                ></InputGroup>

                <InputGroup
                    name="title"
                    v-model="common.title"
                    datalistName="titles"
                    :datalist="titles"
                    maxlength="64"
                    placeholder="Your title here..."
                    :invalid="!validTitle"
                    @input="updateData('title')"
                ></InputGroup>

                <InputGroup
                    name="amount"
                    type="number"
                    v-model="common.amount"
                    step="0.001"
                    placeholder="Your amount here..."
                    :invalid="!validAmount"
                    @input="updateData('amount')"
                ></InputGroup>

                <!-- Price and currency -->
                <div class="form-group row">
                    <div class="col-md-4 d-flex justify-content-md-end justify-content-start align-items-center">
                        <div class="h5 font-weight-bold m-md-0">Price</div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <input
                            type="number"
                            step="0.01"
                            :class="[
                                'form-control',
                                !validPrice && 'is-invalid'
                            ]"
                            placeholder="Your price here..."
                            v-model="common.price"
                            @input="updateData('price')"
                        >

                        <span class="invalid-feedback" role="alert" v-if="!validPrice">
                            <strong>Price is invalid</strong>
                        </span>
                    </div>

                    <div class="col-md-3 col-sm-12 mt-2 mt-md-0">
                        <select
                            class="form-control"
                            @input="updateData('currency_id')"
                            v-model="common.currency_id"
                        >
                            <option
                                v-for="(currency, i) in currenciesWithNA"
                                :key="i"
                                :value="currency.id"
                            >
                                {{ currency.ISO }}
                            </option>
                        </select>
                    </div>
                </div>

                <InputGroup
                    type="select"
                    name="category"
                    :selectOptions="categories[common.currency_id]"
                    optionValueKey="id"
                    optionTextKey="name"
                    v-model="common.category_id"
                    @input="updateData('category_id')"
                ></InputGroup>

				<InputGroup
					type="select"
					name="mean of payment"
					:selectOptions="means[common.currency_id]"
					optionValueKey="id"
					optionTextKey="name"
					v-model="common.mean_id"
                    @input="updateData('mean_id')"
				></InputGroup>

                <hr class="hr">

                <div v-for="(item, i) in data" :key="i">
                    <div class="h4 font-weight-bold ml-3 mb-3">Entry #{{ i + 1 }}</div>

                    <CreateForm
                        v-model="data[i]"
                        :currencies="currencies"
                        :categories="categories"
                        :means="means"
                        :titles="titles"
                        :common="commonObject"
                    ></CreateForm>

                    <div class="row">
                        <div class="col-12 col-sm-4 offset-sm-4">
                            <button class="big-button-danger" @click="deleteEntry(i)">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>

                    <hr v-if="i + 1 < data.length" class="hr-dashed">
                </div>

                <div v-if="cashMeanUsed.length">
                    <hr class="hr">

                    <IncomeOutcomeCashComponent
                        :currencies="currencies"
                        :used="cashMeanUsed"
                        :cash="cash"
                        :sums="sumObject"
                        :type="type"
                        :usersCash="usersCash"
                        v-model="cashUsed"
                    ></IncomeOutcomeCashComponent>
                </div>

                <hr v-if="data.length" class="hr">

                <div class="row">
                    <div class="col-sm-6 my-2 my-sm-0">
                        <button
                            type="button"
                            class="big-button-primary"
                            @click="newEntry"
                            :disabled="submitted"
                        >
                            New {{ type }}
                        </button>
                    </div>

                    <div class="col-sm-6 my-2 my-sm-0">
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
import InputGroup from "../InputGroup.vue";
import CreateForm from "./CreateFormComponent.vue";
import IncomeOutcomeCashComponent from "../../bundles/cash/components/IncomeOutcomeCashComponent.vue";

export default {
    props: ["type"],
    components: {
        Loading,
        InputGroup,
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

            common: {
                date: "",
                title: "",
                amount: "",
                price: "",
                currency_id: 0,
                category_id: 0,
                mean_id: 0
            },
            cashUsed: {},
            data: []
        }
    },
    computed: {
        minDate() {
            const meansForCurrency = this.means[this.data.currency_id];
            if (meansForCurrency == undefined) {
                return "1970-01-01";
            }
			const currentMean = meansForCurrency.filter(item => item.id == this.data.mean_id);
			return currentMean.length ? currentMean[0].first_entry_date : "1970-01-01";
        },
        validDate() {
            const date = this.common.date;
            return date === "" ||
                !isNaN(Date.parse(date)) &&
                new Date(date) >= new Date(this.minDate).getTime()
        },
        validTitle() {
            const title = this.common.title;
            return title.length <= 64;
        },
        validAmount() {
            const amount = Number(this.common.amount);
            return this.common.amount === "" ||
                !isNaN(amount) &&
                amount < 1e6 &&
                amount > 0;
        },
        validPrice() {
            const price = Number(this.common.price);
            return this.common.price === "" ||
                !isNaN(price) &&
                price < 1e11 &&
                price > 0;
        },
        commonObject() {
            let retObj = {};

            for (let i in this.common) {
                if (this.common[i]) {
                    retObj[i] = this.common[i];
                }
            }

            return retObj;
        },
        currenciesWithNA() {
            return [
                {
                    id: 0,
                    ISO: "N / A"
                },
                ...this.currencies
            ]
        },
        canSubmit() {
            if (!(this.validDate && this.validTitle && this.validAmount && this.validPrice)) {
                return false;
            }

            const validData = this.data.map(item => {
                const meansForCurrency = this.means[this.data.currency_id];
                let minDate = "1970-01-01";
                if (meansForCurrency != undefined) {
                    const currentMean = meansForCurrency.filter(item => item.id == this.data.mean_id);
                    minDate = currentMean.length ? currentMean[0].first_entry_date : "1970-01-01";
                }

                const validDate = item.date !== "" &&
                    !isNaN(Date.parse(item.date)) &&
                    new Date(item.date) >= new Date(minDate).getTime();

                const validTitle = item.title.length &&
                    item.title.length <= 64;

                const toNumber = {
                    amount: Number(item.amount),
                    price: Number(item.price)
                }

                const validAmount = !isNaN(toNumber.amount) &&
                    toNumber.amount <= 1e6 &&
                    toNumber.amount > 0;

                const validPrice = !isNaN(toNumber.price) &&
                    toNumber.price <= 1e11 &&
                    toNumber.price > 0;

                if (this.cashMeanUsed) {
                    for (let i in this.cashUsed) {
                        if (!this.isValidCashAmount(this.cashUsed[i], i)) {
                            return false;
                        }
                    }
                }

                return validDate && validTitle && validAmount && validPrice;
            })

            if (validData.length == 0) {
                return false;
            }

            return validData.reduce((item1, item2) => item1 && item2);
        },
        cashMeanUsed() {
            let retArr = [];
            this.data.forEach(item => {
                if (item.mean_id != null &&
                    item.mean_id == this.cashMeans[item.currency_id] &&
                    !retArr.includes(item.currency_id)
                ) {
                    retArr.push(item.currency_id);
                }
            });

            return retArr;
        },
        sumObject() {
            let sums = {};
            this.data.forEach(item => {
                if (item.mean_id != null && item.mean_id == this.cashMeans[item.currency_id]) {
                    if (sums[item.currency_id] == undefined) {
                        sums[item.currency_id] = Math.round(item.amount * item.price * 100) / 100;
                    }
                    else {
                        sums[item.currency_id] += Math.round(item.amount * item.price * 100) / 100;
                    }
                }
            });

            return sums;
        }
    },
    methods: {
        newEntry() {
            this.data.push({...this.common});
        },
        deleteEntry(index) {
            this.data.splice(index, 1);
        },
        updateData(key) {
            setTimeout(() => {
                if (key == "currency_id") {
                    this.common.category_id = 0;
                    this.common.mean_id = 0;

                    this.data.forEach((item, i) => {
                        this.data[i].category_id = 0;
                        this.data[i].mean_id = 0;
                        this.data[i].currency_id = this.common.currency_id || 1;
                    });
                }
                else {
                    this.data.forEach((item, i) => {
                        this.data[i][key] = this.common[key];
                    });
                }
            }, key == "currency_id" && 10);
        },
        submit() {
            this.submitted = true;

            let submitObj = {
                data: this.data
            }

            if (this.cashMeanUsed) {
                submitObj.cash = [];
                this.cashMeanUsed.map(item => {
                    this.cash[item]
                        .map(item1 => item1.id)
                        .forEach(item1 => {
                            if (this.cashUsed[item1] > 0) {
                                submitObj.cash.push({
                                    id: item1,
                                    amount: this.cashUsed[item1]
                                })
                            }
                        })
                })
            }

            axios
                .post(`/webapi/${this.type}/store`, submitObj)
                .then(() => {
                    window.location.href = `/${this.type}`;
                })
                .catch(err => {
                    console.log(err);
                    this.submitted = false;
                });
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

                this.titles = data.titles;
                this.currencies = data.currencies;
                this.common.currency_id = data.last.currency;

                this.categories = data.categories;
                this.categories[0] = [{
                    id: 0,
                    name: "N / A"
                }];

                this.means = data.means;
                this.means[0] = [{
                    id: 0,
                    name: "N / A",
                    first_entry_date: null
                }];

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

                this.data.push({
                    date: "",
                    title: "",
                    amount: "",
                    price: "",
                    currency_id: data.last.currency,
                    category_id: data.last.category || 0,
                    mean_id: data.last.mean || 0
                });

                this.ready = true;
            });
    }
}
</script>
