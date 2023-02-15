<template>
    <v-dialog v-model="dialog" max-width="800">
        <template v-slot:activator="{ on, attrs }">
            <v-btn outlined v-bind="attrs" v-on="on">Add {{ type }}</v-btn>
        </template>

        <v-card v-if="ready">
            <v-card-title class="d-flex" :class="$vuetify.breakpoint.xs ? 'flex-wrap flex-column justify-center' : 'justify-space-between'">
                <div>Add {{ type }}</div>

                <SetCommonValuesComponent
                    :value="clonedCommonValues"
                    :categories="categories"
                    :accounts="accounts"
                    :disableUpdate="loading"
                    :titles="titles"

                    @update="updateCommonValues"
                ></SetCommonValuesComponent>
            </v-card-title>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <div v-for="i in data.length" :key="i">
                        <div v-show="page == i - 1">
                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        type="date"
                                        label="Date"
                                        v-model="data[i - 1].date"
                                        :min="usedAccount.start_date"
                                        :rules="[validation.date(false, usedAccount.start_date)]"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12" md="8">
                                    <v-combobox
                                        label="Title"
                                        :items="titles"
                                        v-model="data[i - 1].title"
                                        counter="64"
                                        :rules="[validation.title()]"
                                        ref="title"
                                    ></v-combobox>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        label="Amount"
                                        v-model="data[i - 1].amount"
                                        :error-messages="keys.amount ? amount.error : undefined"
                                        :hint="amount.hint"
                                        @input="keys.amount++"
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
                                </v-col>

                                <v-col cols="12" md="4">
                                    <v-text-field
                                        label="Price"
                                        v-model="data[i - 1].price"
                                        :error-messages="keys.price ? price.error : undefined"
                                        :hint="price.hint"
                                        @input="keys.price++"
                                        :suffix="currencies.usedCurrencyObject.ISO"
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
                                </v-col>

                                <v-col cols="12" md="4" style='display: flex; flex-wrap: wrap; flex-direction: column; overflow-x: hidden'>
                                    <div class="caption mb-2">Value</div>
                                    <h2 style='white-space: nowrap; font-weight: normal' :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">{{ valueField | addSpaces }} {{ currencies.usedCurrencyObject.ISO }}</h2>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-select
                                        v-model="data[i - 1].category_id"
                                        :items="categories"
                                        item-text="name"
                                        item-value="id"
                                        label="Category"
                                    ></v-select>
                                </v-col>

                                <v-col cols="12" md="6">
                                    <v-select
                                        v-model="data[i - 1].account_id"
                                        :items="accounts"
                                        item-text="name"
                                        item-value="id"
                                        label="Account"
                                    ></v-select>
                                </v-col>
                            </v-row>
                        </div>
                    </div>
                </v-form>

                <div class="text-center text-h5" v-if="data.length > 1">
                    Sum: {{ sum | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}
                </div>
            </v-card-text>

            <v-card-actions class="d-flex justify-space-between">
                <div :class="$vuetify.breakpoint.xs && 'd-flex flex-wrap flex-column-reverse'">
                    <v-btn outlined rounded fab small class="ma-1" @click="page = 0" :disabled="page == 0">
                        <v-icon>mdi-arrow-collapse-left</v-icon>
                    </v-btn>

                    <v-btn outlined rounded fab small class="ma-1" @click="page--" :disabled="page == 0">
                        <v-icon>mdi-arrow-left</v-icon>
                    </v-btn>
                </div>

                <div class="add-income-buttons">
                    <CashIncomeExpencesDialogComponent
                        v-if="extensions.hasExtension('cashan')"
                        v-model="cash"
                        :accountIDs="accountIDs"
                        :disabled="loading"
                        :sumByAccounts="sumByAccounts"
                        :type="type"
                    ></CashIncomeExpencesDialogComponent>

                    <v-btn color="error" class="mx-1" width="90" outlined :disabled="data.length == 1 || loading" @click="removeData">
                        Delete
                    </v-btn>

                    <v-btn color="success" class="mx-1" width="90" outlined :disabled="!canSubmit || loading" @click="submit" :loading="loading">
                        Submit
                    </v-btn>
                </div>

                <div :class="$vuetify.breakpoint.xs && 'd-flex flex-wrap flex-column'">
                    <v-btn outlined rounded fab small class="ma-1" @click="nextPage" :disabled="page == data.length - 1">
                        <v-icon>mdi-arrow-right</v-icon>
                    </v-btn>

                    <v-btn outlined rounded fab small class="ma-1" @click="lastPage" :color="page == data.length - 1 ? 'primary' : undefined">
                        <v-icon v-if="page != data.length - 1">mdi-arrow-collapse-right</v-icon>
                        <v-icon v-else>mdi-plus</v-icon>
                    </v-btn>
                </div>
            </v-card-actions>

            <div class="text-center text-caption pb-2 text--secondary">
                {{ page + 1 }} / {{ data.length }}
            </div>
        </v-card>

        <v-card v-else>
            <v-card-title>Add {{ type }}</v-card-title>

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
import SetCommonValuesComponent from "@/income-expences/SetCommonValuesComponent.vue";
import CashIncomeExpencesDialogComponent from "@/income-expences/CashIncomeExpencesDialogComponent.vue";

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
        SetCommonValuesComponent,
        CashIncomeExpencesDialogComponent
    },
    props: {
        type: String
    },
    data() {
        return {
            dialog: false,
            data: [],
            accounts: [],
            categories: [],
            page: 0,
            commonValues: {
                date: new Date().toISOString().split("T")[0],
                title: "",
                amount: 1,
                price: "",
                category_id: null,
                account_id: null
            },
            titles: [],
            cash: {},
            keys: {
                amount: 0,
                price: 0
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
                .get(`/web-api/${this.type}/currency/${this.currencies.usedCurrency}/data`)
                .then(response => {
                    const data = response.data;

                    this.titles = data.titles;
                    this.accounts = data.accounts;
                    this.categories = data.categories;
                    if (!this.data.length) {
                        this.appendData();
                    }

                    this.ready = true;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                })
        },
        page() {
            this.keys.amount = 0;
            this.keys.price = 0;
        }
    },
    computed: {
        amount() {
            this.keys.amount;
            return new Calculator(this.data[this.page].amount, Calculator.FIELDS.amount).resultObject;
        },
        price() {
            this.keys.price;
            return new Calculator(this.data[this.page].price, Calculator.FIELDS.price).resultObject;
        },
        usedCategory() {
            return this.categories.find(item => item.id == this.data[this.page].category_id);
        },
        usedAccount() {
            return this.accounts.find(item => item.id == this.data[this.page].account_id);
        },
        valueField() {
            return _.round(this.amount.value * this.price.value, 2) || 0;
        },
        sum() {
            this.keys.amount; this.keys.price;

            const sums = this.data.map(item =>
                new Calculator(item.amount, Calculator.FIELDS.amount).resultValue
                * new Calculator(item.price, Calculator.FIELDS.price).resultValue
            );

            if (sums.length) {
                return _.round(sums.reduce((item1, item2) => item1 + item2), 2) || 0;
            }

            return 0;
        },
        clonedCommonValues() {
            return _.cloneDeep(this.commonValues);
        },
        accountIDs() {
            return this.data.map(item => item.account_id);
        },
        sumByAccounts() {
            let sumObj = {};

            this.data.forEach(item => {
                if (item.account_id != null) {
                    let value = new Calculator(item.amount, Calculator.FIELDS.amount).resultValue
                        * new Calculator(item.price, Calculator.FIELDS.price).resultValue;

                    value = _.round(value, 2);
                    if (sumObj[item.account_id] === undefined) {
                        sumObj[item.account_id] = value;
                    } else {
                        sumObj[item.account_id] += value;
                    }
                }
            });

            for (let i in sumObj) {
                sumObj[i] = _.round(sumObj[i], 2);
            }

            return sumObj;
        }
    },
    methods: {
        submit() {
            this.loading = true;
            this.$refs.title.forEach(item => item.blur());

            this.$nextTick(() => {
                const data = _.cloneDeep(this.data);
                for (let item of data) {
                    item.amount = new Calculator(item.amount, Calculator.FIELDS.amount).resultValue;
                    item.price = new Calculator(item.price, Calculator.FIELDS.price).resultValue;
                }

                const cashArray = [];
                Object.keys(this.cash).forEach(item => {
                    cashArray.push({
                        id: item,
                        amount: this.cash[item]
                    });
                });

                axios
                    .post(`/web-api/${this.type}/currency/${this.currencies.usedCurrency}`, { data, cash: cashArray })
                    .then(() => {
                        this.$emit("added");
                        this.dialog = false;
                        this.loading = false;
                        this.data = [];
                        this.page = 0;
                    })
                    .catch(err => {
                        console.error(err);
                        setTimeout(() => this.error = true, 1000);
                        setTimeout(() => this.loading = false, 2000);
                    })
            });
        },
        appendData() {
            this.data.push(_.cloneDeep(this.commonValues))
        },
        removeData() {
            this.data.splice(this.page, 1);
            if (this.page == this.data.length) {
                this.page--;
            }
        },
        nextPage() {
            if (this.data.length - 1 == this.page) {
                this.appendData();
            }

            this.page++;
        },
        lastPage() {
            if (this.data.length - 1 == this.page) {
                this.appendData();
            }

            this.page = this.data.length - 1;
        },
        updateCommonValues(newValues) {
            for (let item of this.data) {
                Object.keys(newValues).forEach(item1 => {
                    if (this.commonValues[item1] != newValues[item1] && newValues[item1] !== "") {
                        item[item1] = newValues[item1];
                    }
                });
            }

            this.commonValues = newValues;
        }
    }
}
</script>
