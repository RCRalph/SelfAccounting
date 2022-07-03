<template>
    <v-dialog v-model="dialog" max-width="800">
        <template v-slot:activator="{ on, attrs }">
            <v-btn outlined v-bind="attrs" v-on="on" class="me-3">Exchange</v-btn>
        </template>

        <v-card v-if="ready">
            <v-card-title>Exchange</v-card-title>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <v-row>
                        <v-col cols="12" md="6">
                            <div :class="['font-weight-bold', 'text-center', 'text-h5', $vuetify.theme.dark ? 'white--text' : 'black--text']">From</div>

                            <v-text-field type="date" label="Date" v-model="from.date" :min="fromData.mean.first_entry_date" :rules="[validation.date(fromData.mean.first_entry_date)]"></v-text-field>

                            <v-text-field label="Title" v-model="from.title" counter="64" :rules="[validation.title(false)]"></v-text-field>

                            <v-text-field label="Amount" v-model="from.amount" :rules="[validation.amount(false)]"></v-text-field>

                            <v-text-field label="Price" v-model="from.price" :rules="[validation.price(false)]" :suffix="fromData.currency.ISO"></v-text-field>

                            <v-select
                                v-model="from.currency_id"
                                :items="currencySelectData"
                                item-text="ISO"
                                item-value="id"
                                label="Currency"
                                @input="resetSelects('from')"
                            ></v-select>

                            <div class="caption mb-2">Value</div>
                            <h2 style='white-space: nowrap; font-weight: normal' :class="['mb-3', $vuetify.theme.dark ? 'white--text' : 'black--text']">{{ fromData.value | addSpaces }} {{ fromData.currency.ISO }}</h2>

                            <v-select
                                v-model="from.category_id"
                                :items="categories[from.currency_id]"
                                item-text="name"
                                item-value="id"
                                label="Category"
                            ></v-select>

                            <v-select
                                v-model="from.mean_id"
                                :items="means[from.currency_id]"
                                :rules="[validation.differentMeans(to.mean_id)]"
                                item-text="name"
                                item-value="id"
                                label="Mean of payment"
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="6">
                            <div :class="['font-weight-bold', 'text-center', 'text-h5', $vuetify.theme.dark ? 'white--text' : 'black--text']">To</div>

                            <v-text-field type="date" label="Date" v-model="to.date" :min="toData.mean.first_entry_date" :rules="[validation.date(toData.mean.first_entry_date)]"></v-text-field>

                            <v-text-field label="Title" v-model="to.title" counter="64" :rules="[validation.title(false)]"></v-text-field>

                            <v-text-field label="Amount" v-model="to.amount" :rules="[validation.amount(false)]"></v-text-field>

                            <v-text-field label="Price" v-model="to.price" :rules="[validation.price(false)]" :suffix="toData.currency.ISO"></v-text-field>

                            <v-select
                                v-model="to.currency_id"
                                :items="currencySelectData"
                                item-text="ISO"
                                item-value="id"
                                label="Currency"
                                @input="resetSelects('to')"
                            ></v-select>

                            <div class="caption mb-2">Value</div>
                            <h2 style='white-space: nowrap; font-weight: normal' :class="['mb-3', $vuetify.theme.dark ? 'white--text' : 'black--text']">{{ toData.value | addSpaces }} {{ toData.currency.ISO }}</h2>

                            <v-select
                                v-model="to.category_id"
                                :items="categories[to.currency_id]"
                                item-text="name"
                                item-value="id"
                                label="Category"
                            ></v-select>

                            <v-select
                                v-model="to.mean_id"
                                :items="means[to.currency_id]"
                                :rules="[validation.differentMeans(from.mean_id)]"
                                item-text="name"
                                item-value="id"
                                label="Mean of payment"
                            ></v-select>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <v-card-actions class="d-flex justify-space-around">
                <v-btn color="success" outlined :disabled="!canSubmit || loading" @click="update" :loading="loading">
                    Submit
                </v-btn>
            </v-card-actions>
        </v-card>

        <v-card v-else>
            <v-card-title>Exchange</v-card-title>

            <v-card-text class="d-flex justify-center">
                <v-progress-circular
                    indeterminate
                    size="96"
                ></v-progress-circular>
            </v-card-text>
        </v-card>

        <ErrorSnackbarComponent v-model="error"></ErrorSnackbarComponent>
    </v-dialog>
</template>

<script>
import ErrorSnackbarComponent from "@/ErrorSnackbarComponent.vue";

import { useCurrenciesStore } from "&/stores/currencies";
import validation from "&/mixins/validation";
import main from "&/mixins/main";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [validation, main],
    components: {
        ErrorSnackbarComponent
    },
    data() {
        return {
            dialog: false,
            from: {},
            to: {},
            means: {},
            categories: {},
            availableCurrencies: [],

            ready: false,
            error: false,
            loading: false,
            canSubmit: false
        }
    },
    watch: {
        dialog() {
            if (!this.dialog) return;
            this.ready = false;

            axios
                .get(`/web-api/exchange`)
                .then(response => {
                    const data = response.data;

                    this.means = data.means;
                    this.categories = data.categories;
                    this.availableCurrencies = data.availableCurrencies;

                    const dataObject = {
                        date: new Date().toISOString().split("T")[0],
                        title: "",
                        amount: 1,
                        price: "",
                        currency_id: this.currencies.usedCurrency,
                        category_id: null,
                        mean_id: undefined
                    }
                    this.from = _.cloneDeep(dataObject);
                    this.to = _.cloneDeep(dataObject);

                    this.ready = true;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                })
        }
    },
    computed: {
        currencySelectData() {
            return this.currencies.selectCurrencies(this.availableCurrencies);
        },
        fromData() {
            if (!this.ready) return {};

            const categorie = this.categories[this.from.currency_id].find(item => item.id == this.from.category_id),
                mean = this.means[this.from.currency_id].find(item => item.id == this.from.mean_id);

            return {
                currency: this.currencies.selectCurrencies([this.from.currency_id])[0],
                value: Math.round(this.from.amount * this.from.price * 100) / 100,
                categories: categorie !== undefined ? categorie : [],
                mean: mean !== undefined ? mean : []
            }
        },
        toData() {
            if (!this.ready) return {};

            const categorie = this.categories[this.to.currency_id].find(item => item.id == this.to.category_id),
                mean = this.means[this.to.currency_id].find(item => item.id == this.to.mean_id);

            return {
                currency: this.currencies.selectCurrencies([this.to.currency_id])[0],
                value: Math.round(this.to.amount * this.to.price * 100) / 100,
                categories: categorie !== undefined ? categorie : [],
                mean: mean !== undefined ? mean : []
            }
        }
    },
    methods: {
        update() {
            this.loading = true;

            const dataNoComma = _.cloneDeep(this.data);
            if (typeof dataNoComma.amount == "string") {
                dataNoComma.amount.replaceAll(",", ".");
            }
            if (typeof dataNoComma.price == "string") {
                dataNoComma.price.replaceAll(",", ".");
            }

            axios
                .patch(`/web-api/${this.type}/${this.id}`, dataNoComma)
                .then(() => {
                    this.loading = false;
                    this.dataCopy = _.cloneDeep(this.data);
                    this.$emit("updated");
                    this.dialog = false;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.loading = false, 2000);
                })
        },
        resetSelects(key) {
            this[key].category_id = null;
            this[key].mean_id = null;
        }
    }
}
</script>
