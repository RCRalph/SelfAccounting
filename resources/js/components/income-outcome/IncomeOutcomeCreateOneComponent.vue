<template>
    <div :class="darkmode ? 'dark-card' : 'card'">
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
                <div class="form-group row">
                    <label for="date" class="col-md-3 col-form-label text-md-right">Date</label>

                    <div class="col-md-7">
                        <input
                            type="date"
                            :class="[
                                'form-control',
                                invalidDate && 'is-invalid'
                            ]"
                            v-model="attributes.date"
                            :min="minDate"
                        >

                        <span class="invalid-feedback" role="alert" v-if="invalidDate">
                            <strong>This date is invalid</strong>
                        </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="title" class="col-md-3 col-form-label text-md-right">Title</label>

                    <div class="col-md-7">
                        <input
                            type="text"
                            :class="[
                                'form-control',
                                invalidTitle && titleChanged && 'is-invalid'
                            ]"
                            v-model="attributes.title"
                            autocomplete="off"
                            placeholder="Your title here"
                            list="titleList"
                            @input="titleChanged = true"
                        >
                        <datalist id="titleList">
                            <option v-for="(title, i) in titles" :key="i">{{ title }}</option>
                        </datalist>

                        <span class="invalid-feedback" role="alert" v-if="invalidTitle && titleChanged">
                            <strong>This title is invalid</strong>
                        </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="amount" class="col-md-3 col-form-label text-md-right">Amount</label>

                    <div class="col-md-7">
                        <input
                            type="number"
                            :class="[
                                'form-control',
                                invalidAmount && 'is-invalid'
                            ]"
                            step="0.001"
                            v-model="attributes.amount"
                            placeholder="Your amount here"
                        >

                        <span class="invalid-feedback" role="alert" v-if="invalidAmount">
                            <strong>This amount is invalid</strong>
                        </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="price" class="col-md-3 col-form-label text-md-right">Price</label>

                    <div class="col-md-4 col-sm-12">
                        <input
                            type="number"
                            step="0.01"
                            :class="[
                                'form-control',
                                invalidPrice && priceChanged && 'is-invalid'
                            ]"
                            placeholder="Your price here"
                            v-model="attributes.price"
                            @input="priceChanged = true"
                        >

                        <span class="invalid-feedback" role="alert" v-if="invalidPrice && priceChanged">
                            <strong>This price is invalid</strong>
                        </span>
                    </div>

                    <div class="col-md-3 col-sm-12 mt-2 mt-md-0">
                        <select class="form-control" v-model="attributes.currency_id" @change="currencyChange">
                            <option
                                v-for="(currency, i) in currencies"
                                :key="i"
                                :value="currency['id']"
                            >{{ currency["ISO"] }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="value" class="col-md-3 col-form-label text-md-right">Value</label>

                    <div class="col-md-7">
                        <input type="text" class="form-control" disabled :value="Math.round(attributes.price * attributes.amount * 100) / 100">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-md-right">Category</label>

                    <div class="col-md-7">
                        <select class="form-control" v-model="attributes.category_id">
                            <option
                                v-for="(category, i) in categories[attributes.currency_id]"
                                :key="i"
                                :value="category['id']"
                            >{{ category['name'] }}</option>
                            <option value="null">N / A</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-md-right">Mean of payment</label>

                    <div class="col-md-7">
                        <select class="form-control" v-model="attributes.mean_id">
                            <option
                                v-for="(mean, i) in means[attributes.currency_id]"
                                :key="i"
                                :value="mean['id']"
                            >{{ mean['name'] }}</option>
                            <option value="null">N / A</option>
                        </select>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-sm-4 col-12 offset-sm-4">
                        <button class="big-button-primary" @click="saveChanges" v-html="saveButton" :disabled="buttonsDisabled || invalidData"></button>
                    </div>
                </div>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import Loading from "../Loading.vue";

export default {
    props: {
        type: String
    },
    components: {
        Loading
    },
    data() {
        return {
            darkmode: false,
            ready: false,
            attributes: {},
            currencies: [],
            categories: {},
            means: {},
            titles: [],
            saveButton: 'Submit',
            buttonsDisabled: false,
            titleChanged: false,
            priceChanged: false
        }
    },
    computed: {
        invalidDate() {
            if (!this.attributes.mean_id || this.attributes.mean_id == "null") {
                return !this.attributes.date;
            }

            const currentDate = new Date(this.attributes.date).getTime()
            const minDate = this.means[this.attributes.currency_id].filter(item => {
                    return item.id == this.attributes.mean_id
                })[0].first_entry_date;

            return !this.attributes.date || new Date(minDate).getTime() > currentDate;
        },
        invalidTitle() {
            return !this.attributes.title || this.attributes.title.length > 32;
        },
        invalidAmount() {
            return parseFloat(this.attributes.amount) != this.attributes.amount;
        },
        invalidPrice() {
            return parseFloat(this.attributes.price) != this.attributes.price;
        },
        minDate() {
            if (!this.attributes.mean_id || this.attributes.mean_id == "null") {
                return false;
            }

            return this.means[this.attributes.currency_id].filter(item => {
                    return item.id == this.attributes.mean_id
                })[0].first_entry_date;
        },
        invalidData() {
            return this.invalidDate || this.invalidTitle || this.invalidAmount || this.invalidPrice;
        }
    },
    methods: {
        saveChanges() {
            this.saveButton = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            this.buttonsDisabled = true;

            axios
                .post(`/webapi/${this.type}/store/one`, this.attributes)
                .then(response => {
                    if (response.data.status == "success") {
                        window.location.href = "/" + this.type;
                    }
                    else {
                        this.saveButton = 'Save';
                        this.buttonsDisabled = false;
                    }
                })
                .catch(() => {
                    this.saveButton = 'Save';
                    this.buttonsDisabled = false;
                })
        },
        currencyChange() {
            this.attributes.mean_id = null;
            this.attributes.category_id = null;
        }
    },
    beforeMount() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    },
    mounted() {
        axios
            .get(`/webapi/${this.type}/create/get`)
            .then(response => {
                this.categories = response.data.categories;
                this.currencies = response.data.currencies;
                this.means = response.data.means;
                this.titles = response.data.titles;

                let attrs = {}

                attrs.currency_id = response.data.lastCurrency;
                attrs.category_id = response.data.lastCategory;
                attrs.mean_id = response.data.lastMean;
                attrs.amount = 1;
                attrs.price = "";

                // Set correct date
                let minDate = false;

                if (attrs.mean_id) {
                    minDate = this.means[attrs.currency_id].filter(item => {
                        return item.id == attrs.mean_id
                    })[0].first_entry_date
                }

                const dateToSet = new Date(minDate).getTime() > new Date().getTime() ?
                    new Date(minDate) : new Date();

                attrs.date = dateToSet.toLocaleDateString('en-ZA').split("/").join("-");

                this.attributes = attrs;
                this.ready = true;
            });
    },
    beforeUpdate() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    },
    updated() {
        this.$nextTick(() => {
            $('[data-toggle="tooltip"]').tooltip()
        });
    }
}
</script>
