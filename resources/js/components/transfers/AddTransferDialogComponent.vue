<template>
    <v-dialog v-model="dialog" max-width="800">
        <template v-slot:activator="{ on, attrs }">
            <v-btn outlined v-bind="attrs" v-on="on" class="me-3">Add transfer</v-btn>
        </template>

        <v-card v-if="ready">
            <v-card-title>Add Transfer</v-card-title>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <v-row>
                        <v-col cols="12" md="4" offset-md="4" class="pb-0">
                            <v-text-field
                                type="date" label="Date"
                                v-model="data.date"
                                :min="minDate"
                                :rules="[validation.date(false, minDate)]"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6">
                            <div class="d-flex justify-space-between align-center">
                                <div class="text-h5-5" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">Source</div>

                                <CashExchangeDialogComponent
                                    v-if="extensions.hasExtension('cashan')"
                                    v-model="sourceCash"
                                    :currency="sourceData.currency"
                                    :accountID="data.source.account_id"
                                    :disabled="loading"
                                    :entryValue="sourceValue.value"
                                    type="expences"
                                ></CashExchangeDialogComponent>
                            </div>

                            <v-text-field
                                label="Value"
                                v-model="data.source.value"
                                :error-messages="keys.source ? sourceValue.error : undefined"
                                :hint="sourceValue.hint"
                                @input="keys.source++"
                                :suffix="sourceData.currency.ISO"
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
                                v-model="data.source.account_id"
                                :items="sourceAccounts"
                                :rules="[validation.differentAccounts(data.target.account_id)]"
                                item-text="name"
                                item-value="id"
                                label="Account"
                            ></v-select>

                            <v-select
                                v-model="data.source.currency_id"
                                :items="currencySelectData"
                                item-text="ISO"
                                item-value="id"
                                label="Currency"
                                @input="resetSelects('source')"
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="6">
                            <div class="d-flex justify-space-between align-center">
                                <div class="text-h5-5" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">Target</div>

                                <CashExchangeDialogComponent
                                    v-if="extensions.hasExtension('cashan')"
                                    v-model="targetCash"
                                    :currency="targetData.currency"
                                    :accountID="data.target.account_id"
                                    :disabled="loading"
                                    :entryValue="targetValue.value"
                                    type="income"
                                ></CashExchangeDialogComponent>
                            </div>

                            <v-text-field
                                label="Value"
                                v-model="data.target.value"
                                :error-messages="keys.targetVa ? targetValue.error : undefined"
                                :hint="targetValue.hint"
                                @input="keys.target++"
                                :suffix="targetData.currency.ISO"
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
                                v-model="data.target.account_id"
                                :items="targetAccounts"
                                :rules="[validation.differentAccounts(data.source.account_id)]"
                                item-text="name"
                                item-value="id"
                                label="Account"
                            ></v-select>

                            <v-select
                                v-model="data.target.currency_id"
                                :items="currencySelectData"
                                item-text="ISO"
                                item-value="id"
                                label="Currency"
                                @input="resetSelects('target')"
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
            startData: {
                date: "",
                source: {
                    value: "",
                    account_id: undefined,
                    currency_id: undefined
                },
                target: {
                    value: "",
                    account_id: undefined,
                    currency_id: undefined
                }
            },
            data: {},
            accounts: {},
            availableCurrencies: [],
            sourceCash: {},
            targetCash: {},
            keys: {
                source: 0,
                target: 0
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
                .get(`/web-api/transfers`)
                .then(response => {
                    const data = response.data;

                    this.accounts = data.accounts;
                    this.availableCurrencies = data.availableCurrencies;

                    this.data = _.cloneDeep(this.startData)
                    this.data.date = new Date().toISOString().split("T")[0];
                    this.data.source.currency_id = this.data.target.currency_id = this.currencies.usedCurrency;

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
        sourceValue() {
            this.keys.source;
            return new Calculator(this.data.source.value, Calculator.FIELDS.value).resultObject;
        },
        targetValue() {
            this.keys.target;
            return new Calculator(this.data.target.value, Calculator.FIELDS.value).resultObject;
        },
        sourceData() {
            if (!this.ready) return {};

            const account = this.accounts[this.data.source.currency_id].find(item => item.id == this.data.source.account_id);

            return {
                currency: this.currencies.findCurrency(this.data.source.currency_id),
                account: account !== undefined ? account : {}
            }
        },
        targetData() {
            if (!this.ready) return {};

            const account = this.accounts[this.data.target.currency_id].find(item => item.id == this.data.target.account_id);

            return {
                currency: this.currencies.findCurrency(this.data.target.currency_id),
                account: account !== undefined ? account : {}
            }
        },
        sourceAccounts() {
            return this.accounts[this.data.source.currency_id];
        },
        targetAccounts() {
            return this.accounts[this.data.target.currency_id];
        },
        minDate() {
            let result = new Date("1970-01-01"), date = new Date();

            if (!_.isEmpty(this.sourceData.account)) {
                date = new Date(this.sourceData.account.start_date);
                if (result.getTime() < date.getTime()) {
                    result = date;
                }
            }

            if (!_.isEmpty(this.targetData.account)) {
                date = new Date(this.targetData.account.start_date);
                if (result.getTime() < date.getTime()) {
                    result = date;
                }
            }

            return result;
        },
    },
    methods: {
        submit() {
            this.loading = true;

            let data = _.cloneDeep(this.data);
            data.source.value = this.sourceValue.value;
            data.target.value = this.targetValue.value;
            delete data.source.currency_id;
            delete data.target.currency_id;

            if (!_.isEmpty(this.sourceCash)) {
                data.source.cash = [];
                Object.keys(this.sourceCash).forEach(item => {
                    data.source.cash.push({
                        id: item,
                        amount: this.sourceCash[item]
                    });
                });
            }

            if (!_.isEmpty(this.targetCash)) {
                data.target.cash = [];
                Object.keys(this.targetCash).forEach(item => {
                    data.target.cash.push({
                        id: item,
                        amount: this.targetCash[item]
                    });
                });
            }

            axios.post(`/web-api/transfers`, data)
                .then(() => {
                    this.$emit("added");
                    this.dialog = false;
                    this.loading = false;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.loading = false, 2000);
                })
        },
        resetSelects(key) {
            this.data[key].account_id = null;
        }
    }
}
</script>
