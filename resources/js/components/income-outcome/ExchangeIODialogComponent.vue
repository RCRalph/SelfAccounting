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
                            <div class="d-flex justify-sm-space-between justify-center flex-sm-row flex-column">
                                <div class="font-weight-bold text-h5" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">From</div>

                                <CashExchangeDialogComponent
                                    v-if="extensions.hasExtension('cashan')"
                                    v-model="fromCash"
                                    :currency="fromData.currency"
                                    :meanID="from.mean_id"
                                    :disabled="loading"
                                    :entryValue="fromData.value"
                                    type="outcome"
                                ></CashExchangeDialogComponent>
                            </div>

                            <v-text-field type="date" label="Date" v-model="from.date" :min="fromData.mean.first_entry_date" :rules="[validation.date(false, fromData.mean.first_entry_date)]"></v-text-field>

                            <v-combobox
                                label="Title"
                                :items="titles"
                                v-model="from.title"
                                counter="64"
                                :rules="[validation.title()]"
                                ref="title1"
                            ></v-combobox>

                            <v-text-field
                                label="Amount"
                                v-model="from.amount"
                                :error-messages="keys.fromAmount ? fromAmount.error : undefined"
                                :hint="fromAmount.hint"
                                @input="keys.fromAmount++"
                            ></v-text-field>

                            <v-text-field
                                label="Price"
                                v-model="from.price"
                                :error-messages="keys.fromPrice ? fromPrice.error : undefined"
                                :hint="fromAmount.hint"
                                @input="keys.fromPrice++"
                                :suffix="fromData.currency.ISO"
                            ></v-text-field>

                            <v-select
                                v-model="from.currency_id"
                                :items="currencySelectData"
                                item-text="ISO"
                                item-value="id"
                                label="Currency"
                                @input="resetSelects('from')"
                            ></v-select>

                            <div class="caption mb-2">Value</div>
                            <h2 style="white-space: nowrap" class="font-weight-regular mb-3" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">{{ fromData.value | addSpaces }} {{ fromData.currency.ISO }}</h2>

                            <v-select
                                v-model="from.category_id"
                                :items="fromSelects.categories"
                                item-text="name"
                                item-value="id"
                                label="Category"
                            ></v-select>

                            <v-select
                                v-model="from.mean_id"
                                :items="fromSelects.means"
                                :rules="[validation.differentMeans(to.mean_id)]"
                                item-text="name"
                                item-value="id"
                                label="Mean of payment"
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="6">
                            <div class="d-flex justify-sm-space-between justify-center flex-sm-row flex-column">
                                <div class="font-weight-bold text-h5" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">To</div>

                                <CashExchangeDialogComponent
                                    v-if="extensions.hasExtension('cashan')"
                                    v-model="toCash"
                                    :currency="toData.currency"
                                    :meanID="to.mean_id"
                                    :disabled="loading"
                                    :entryValue="toData.value"
                                    type="income"
                                ></CashExchangeDialogComponent>
                            </div>

                            <v-text-field type="date" label="Date" v-model="to.date" :min="toData.mean.first_entry_date" :rules="[validation.date(false, toData.mean.first_entry_date)]"></v-text-field>

                            <v-combobox
                                label="Title"
                                :items="titles"
                                v-model="to.title"
                                counter="64"
                                :rules="[validation.title()]"
                                ref="title2"
                            ></v-combobox>

                            <v-text-field
                                label="Amount"
                                v-model="to.amount"
                                :error-messages="keys.toAmount ? toAmount.error : undefined"
                                :hint="toAmount.hint"
                                @input="keys.toAmount++"
                            ></v-text-field>

                            <v-text-field
                                label="Price"
                                v-model="to.price"
                                :error-messages="keys.toPrice ? toPrice.error : undefined"
                                :hint="toAmount.hint"
                                @input="keys.toPrice++"
                                :suffix="toData.currency.ISO"
                            ></v-text-field>

                            <v-select
                                v-model="to.currency_id"
                                :items="currencySelectData"
                                item-text="ISO"
                                item-value="id"
                                label="Currency"
                                @input="resetSelects('to')"
                            ></v-select>

                            <div class="caption mb-2">Value</div>
                            <h2 style="white-space: nowrap" class="font-weight-regular mb-3" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">{{ toData.value | addSpaces }} {{ toData.currency.ISO }}</h2>

                            <v-select
                                v-model="to.category_id"
                                :items="toSelects.categories"
                                item-text="name"
                                item-value="id"
                                label="Category"
                            ></v-select>

                            <v-select
                                v-model="to.mean_id"
                                :items="toSelects.means"
                                :rules="[validation.differentMeans(from.mean_id)]"
                                item-text="name"
                                item-value="id"
                                label="Mean of payment"
                            ></v-select>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <v-card-actions class="d-flex justify-center">
                <v-btn color="success" outlined :disabled="!canSubmit || loading" @click="submit" :loading="loading" width="80">
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
import CashExchangeDialogComponent from "@/income-outcome/CashExchangeDialogComponent.vue";

import { useCurrenciesStore } from "&/stores/currencies";
import { useExtensionsStore } from "&/stores/extensions";
import calculator from "&/mixins/calculator";
import validation from "&/mixins/validation";
import main from "&/mixins/main";

export default {
    setup() {
        const currencies = useCurrenciesStore();
        const extensions = useExtensionsStore();

        return { currencies, extensions };
    },
    mixins: [validation, main, calculator],
    components: {
        ErrorSnackbarComponent,
        CashExchangeDialogComponent
    },
    data() {
        return {
            dialog: false,
            from: {},
            to: {},
            means: {},
            categories: {},
            availableCurrencies: [],
            titles: [],
            fromCash: {},
            toCash: {},
            keys: {
                fromAmount: 0,
                fromPrice: 0,
                toAmount: 0,
                toPrice: 0
            },

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

                    this.titles = data.titles;
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
        fromAmount() {
            this.keys.fromAmount;
            return this.getCalculationResult(this.from.amount, this.CALCULATOR.FIELDS.amount);
        },
        fromPrice() {
            this.keys.fromPrice;
            return this.getCalculationResult(this.from.price, this.CALCULATOR.FIELDS.price);
        },
        toAmount() {
            this.keys.toAmount;
            return this.getCalculationResult(this.to.amount, this.CALCULATOR.FIELDS.amount);
        },
        toPrice() {
            this.keys.toPrice;
            return this.getCalculationResult(this.to.price, this.CALCULATOR.FIELDS.price);
        },
        fromData() {
            if (!this.ready) return {};

            const categories = this.categories[this.from.currency_id].find(item => item.id == this.from.category_id),
                mean = this.means[this.from.currency_id].find(item => item.id == this.from.mean_id);

            return {
                currency: this.currencies.findCurrency(this.from.currency_id),
                value: _.round(this.fromAmount.value * this.fromPrice.value, 2) || 0,
                categories: categories !== undefined ? categories : [],
                mean: mean !== undefined ? mean : []
            }
        },
        fromSelects() {
            return {
                categories: this.categories[this.from.currency_id].filter(item => item.outcome_category),
                means: this.means[this.from.currency_id].filter(item => item.outcome_mean)
            }
        },
        toData() {
            if (!this.ready) return {};

            const categories = this.categories[this.to.currency_id].find(item => item.id == this.to.category_id),
                mean = this.means[this.to.currency_id].find(item => item.id == this.to.mean_id);

            return {
                currency: this.currencies.findCurrency(this.to.currency_id),
                value: _.round(this.toAmount.value * this.toPrice.value, 2) || 0,
                categories: categories !== undefined ? categories : [],
                mean: mean !== undefined ? mean : []
            }
        },
        toSelects() {
            return {
                categories: this.categories[this.to.currency_id].filter(item => item.income_category),
                means: this.means[this.to.currency_id].filter(item => item.income_mean)
            }
        }
    },
    methods: {
        submit() {
            console.log(this.$refs);
            this.loading = true;
            this.$refs.title1.blur();
            this.$refs.title2.blur();

            this.$nextTick(() => {
                const fromCashArray = [];
                Object.keys(this.fromCash).forEach(item => {
                    fromCashArray.push({
                        id: item,
                        amount: this.fromCash[item]
                    });
                })

                const toCashArray = [];
                Object.keys(this.toCash).forEach(item => {
                    toCashArray.push({
                        id: item,
                        amount: this.toCash[item]
                    });
                })

                axios
                    .post(`/web-api/exchange`, {
                        from: {
                            ...this.from,
                            amount: this.fromAmount.value,
                            price: this.fromPrice.value
                        },
                        to: {
                            ...this.to,
                            amount: this.toAmount.value,
                            price: this.toPrice.value
                        },
                        fromCash: fromCashArray,
                        toCash: toCashArray
                    })
                    .then(() => {
                        this.$emit("exchanged");
                        this.dialog = false;
                        this.loading = false;
                    })
                    .catch(err => {
                        console.error(err);
                        setTimeout(() => this.error = true, 1000);
                        setTimeout(() => this.loading = false, 2000);
                    })
            });
        },
        resetSelects(key) {
            this[key].category_id = null;
            this[key].mean_id = null;
        }
    }
}
</script>
