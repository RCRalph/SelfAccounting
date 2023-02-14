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
                                    :accountID="from.account_id"
                                    :disabled="loading"
                                    :entryValue="fromData.value"
                                    type="expences"
                                ></CashExchangeDialogComponent>
                            </div>

                            <v-text-field type="date" label="Date" v-model="from.date" :min="fromData.account.start_date" :rules="[validation.date(false, fromData.account.start_date)]"></v-text-field>

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
                            >
                                <template v-slot:append>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon v-on="on" class="ml-1">
                                                mdi-calculator
                                            </v-icon>
                                        </template>

                                        Supported operations: <strong>+ - * / ^</strong>
                                    </v-tooltip>
                                </template>
                            </v-text-field>

                            <v-text-field
                                label="Price"
                                v-model="from.price"
                                :error-messages="keys.fromPrice ? fromPrice.error : undefined"
                                :hint="fromAmount.hint"
                                @input="keys.fromPrice++"
                                :suffix="fromData.currency.ISO"
                            >
                                <template v-slot:append>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon v-on="on" class="ml-1">
                                                mdi-calculator
                                            </v-icon>
                                        </template>

                                        Supported operations: <strong>+ - * / ^</strong>
                                    </v-tooltip>
                                </template>
                            </v-text-field>

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
                                v-model="from.account_id"
                                :items="fromSelects.accounts"
                                :rules="[validation.differentAccounts(to.account_id)]"
                                item-text="name"
                                item-value="id"
                                label="Account"
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="6">
                            <div class="d-flex justify-sm-space-between justify-center flex-sm-row flex-column">
                                <div class="font-weight-bold text-h5" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">To</div>

                                <CashExchangeDialogComponent
                                    v-if="extensions.hasExtension('cashan')"
                                    v-model="toCash"
                                    :currency="toData.currency"
                                    :accountID="to.account_id"
                                    :disabled="loading"
                                    :entryValue="toData.value"
                                    type="income"
                                ></CashExchangeDialogComponent>
                            </div>

                            <v-text-field type="date" label="Date" v-model="to.date" :min="toData.account.start_date" :rules="[validation.date(false, toData.account.start_date)]"></v-text-field>

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
                            >
                                <template v-slot:append>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon v-on="on" class="ml-1">
                                                mdi-calculator
                                            </v-icon>
                                        </template>

                                        Supported operations: <strong>+ - * / ^</strong>
                                    </v-tooltip>
                                </template>
                            </v-text-field>

                            <v-text-field
                                label="Price"
                                v-model="to.price"
                                :error-messages="keys.toPrice ? toPrice.error : undefined"
                                :hint="toAmount.hint"
                                @input="keys.toPrice++"
                                :suffix="toData.currency.ISO"
                            >
                                <template v-slot:append>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon v-on="on" class="ml-1">
                                                mdi-calculator
                                            </v-icon>
                                        </template>

                                        Supported operations: <strong>+ - * / ^</strong>
                                    </v-tooltip>
                                </template>
                            </v-text-field>

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
                                v-model="to.account_id"
                                :items="toSelects.accounts"
                                :rules="[validation.differentAccounts(from.account_id)]"
                                item-text="name"
                                item-value="id"
                                label="Account"
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
import CashExchangeDialogComponent from "@/income-expences/CashExchangeDialogComponent.vue";

import { useCurrenciesStore } from "&/stores/currencies";
import { useExtensionsStore } from "&/stores/extensions";
import Calculator from "&/classes/Calculator";
import validation from "&/mixins/validation";
import main from "&/mixins/main";

export default {
    setup() {
        const currencies = useCurrenciesStore();
        const extensions = useExtensionsStore();

        return { currencies, extensions };
    },
    mixins: [validation, main],
    components: {
        ErrorSnackbarComponent,
        CashExchangeDialogComponent
    },
    data() {
        return {
            dialog: false,
            from: {},
            to: {},
            accounts: {},
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
                    this.accounts = data.accounts;
                    this.categories = data.categories;
                    this.availableCurrencies = data.availableCurrencies;

                    const dataObject = {
                        date: new Date().toISOString().split("T")[0],
                        title: "",
                        amount: 1,
                        price: "",
                        currency_id: this.currencies.usedCurrency,
                        category_id: null,
                        account_id: undefined
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
            return new Calculator(this.from.amount, Calculator.FIELDS.amount).resultObject;
        },
        fromPrice() {
            this.keys.fromPrice;
            return new Calculator(this.from.price, Calculator.FIELDS.price).resultObject;
        },
        toAmount() {
            this.keys.toAmount;
            return new Calculator(this.to.amount, Calculator.FIELDS.amount).resultObject;
        },
        toPrice() {
            this.keys.toPrice;
            return new Calculator(this.to.price, Calculator.FIELDS.price).resultObject;
        },
        fromData() {
            if (!this.ready) return {};

            const categories = this.categories[this.from.currency_id].find(item => item.id == this.from.category_id),
                account = this.accounts[this.from.currency_id].find(item => item.id == this.from.account_id);

            return {
                currency: this.currencies.findCurrency(this.from.currency_id),
                value: _.round(this.fromAmount.value * this.fromPrice.value, 2) || 0,
                categories: categories !== undefined ? categories : [],
                account: account !== undefined ? account : []
            }
        },
        fromSelects() {
            return {
                categories: this.categories[this.from.currency_id].filter(item => item.used_in_expences),
                accounts: this.accounts[this.from.currency_id].filter(item => item.used_in_expences)
            }
        },
        toData() {
            if (!this.ready) return {};

            const categories = this.categories[this.to.currency_id].find(item => item.id == this.to.category_id),
                account = this.accounts[this.to.currency_id].find(item => item.id == this.to.account_id);

            return {
                currency: this.currencies.findCurrency(this.to.currency_id),
                value: _.round(this.toAmount.value * this.toPrice.value, 2) || 0,
                categories: categories !== undefined ? categories : [],
                account: account !== undefined ? account : []
            }
        },
        toSelects() {
            return {
                categories: this.categories[this.to.currency_id].filter(item => item.used_in_income),
                accounts: this.accounts[this.to.currency_id].filter(item => item.used_in_income)
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
            this[key].account_id = null;
        }
    }
}
</script>
