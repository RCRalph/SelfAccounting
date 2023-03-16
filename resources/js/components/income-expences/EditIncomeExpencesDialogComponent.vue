<template>
    <v-dialog v-model="dialog" max-width="800">
        <template v-slot:activator="{ on: dialogOn, attrs: dialogAttrs }">
            <v-tooltip bottom >
                <template v-slot:activator="{ on: tooltipOn, attrs: tooltipAttrs }">
                    <v-icon class="mx-1 cursor-pointer" v-bind="{ ...dialogAttrs, ...tooltipAttrs }" v-on="{ ...dialogOn, ...tooltipOn }">mdi-pencil</v-icon>
                </template>

                <span>Edit {{ type == 'expences' ? 'expence' : 'income' }}</span>
            </v-tooltip>
        </template>

        <v-card v-if="ready">
            <v-card-title class="d-flex" :class="$vuetify.breakpoint.xs ? 'flex-wrap flex-column justify-center' : 'justify-space-between'">
                <div>Edit {{ type == 'expences' ? 'expence' : 'income' }}</div>

                <ConvertTransactionDialogComponent
                    :type="type"
                    :id="id"
                    @converted="converted"
                ></ConvertTransactionDialogComponent>
            </v-card-title>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field type="date" label="Date" v-model="data.date" :min="usedAccount.start_date" :rules="[validation.date(false, usedAccount.start_date)]"></v-text-field>
                        </v-col>

                        <v-col cols="12" md="8">
                            <v-combobox
                                label="Title"
                                :items="titles"
                                v-model="data.title"
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
                                v-model="data.amount"
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
                                v-model="data.price"
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
                                v-model="data.category_id"
                                :items="categories"
                                item-text="name"
                                item-value="id"
                                label="Category"
                            >
                                <template v-slot:item="{ item }">
                                    <v-list-item-icon v-if="item.icon">
                                        <v-icon>{{ item.icon }}</v-icon>
                                    </v-list-item-icon>

                                    <v-list-item-content>
                                        <v-list-item-title>{{ item.name }}</v-list-item-title>
                                    </v-list-item-content>
                                </template>
                            </v-select>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-select
                                v-model="data.account_id"
                                :items="accounts"
                                item-text="name"
                                item-value="id"
                                label="Account"
                            >
                                <template v-slot:item="{ item }">
                                    <v-list-item-icon v-if="item.icon">
                                        <v-icon>{{ item.icon }}</v-icon>
                                    </v-list-item-icon>

                                    <v-list-item-content>
                                        <v-list-item-title>{{ item.name }}</v-list-item-title>
                                    </v-list-item-content>
                                </template>
                            </v-select>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <v-card-actions class="d-flex justify-space-around">
                <v-btn color="error" outlined @click="reset" :disabled="loading" class="mx-1" width="85">
                    Reset
                </v-btn>

                <v-btn color="success" outlined :disabled="!canSubmit || loading" @click="update" :loading="loading" class="mx-1" width="85">
                    Update
                </v-btn>
            </v-card-actions>
        </v-card>

        <v-card v-else>
            <v-card-title>Edit {{ type }}</v-card-title>

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
import ConvertTransactionDialogComponent from "@/income-expences/ConvertTransactionDialogComponent.vue";
import ErrorSnackbarComponent from "@/ErrorSnackbarComponent.vue";

import { useCurrenciesStore } from "&/stores/currencies";
import Calculator from "&/classes/Calculator";
import validation from "&/mixins/validation";
import main from "&/mixins/main";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [validation, main],
    components: {
        ConvertTransactionDialogComponent,
        ErrorSnackbarComponent
    },
    props: {
        type: String,
        id: Number
    },
    data() {
        return {
            dialog: false,
            data: {},
            dataCopy: {},
            accounts: [],
            categories: [],
            titles: [],
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
                .get(`/web-api/${this.type}/${this.id}`)
                .then(response => {
                    const data = response.data;

                    this.titles = data.titles;
                    this.data = data.data;
                    this.dataCopy = _.cloneDeep(data.data);
                    this.accounts = data.accounts;
                    this.categories = data.categories;

                    this.ready = true;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                })
        }
    },
    computed: {
        amount() {
            this.keys.amount;
            return new Calculator(this.data.amount, Calculator.FIELDS.amount).resultObject;
        },
        price() {
            this.keys.price;
            return new Calculator(this.data.price, Calculator.FIELDS.price).resultObject;
        },
        usedCategory() {
            return this.categories.find(item => item.id == this.data.category_id);
        },
        usedAccount() {
            return this.accounts.find(item => item.id == this.data.account_id);
        },
        valueField() {
            return _.round(this.amount.value * this.price.value, 2) || 0;
        },
    },
    methods: {
        reset() {
            this.data = _.cloneDeep(this.dataCopy);
        },
        update() {
            this.loading = true;
            this.$refs.title.blur();

            this.$nextTick(() => {
                axios
                    .patch(`/web-api/${this.type}/${this.id}`, {
                        ...this.data,
                        amount: this.amount.value,
                        price: this.price.value
                    })
                    .then(() => {
                        this.dataCopy = _.cloneDeep(this.data);
                        this.$emit("updated");
                        this.dialog = false;
                        this.loading = false;
                    })
                    .catch(err => {
                        console.error(err);
                        setTimeout(() => this.error = true, 1000);
                        setTimeout(() => this.loading = false, 2000);
                    })
            })
        },
        converted() {
            this.dialog = false;
            this.$emit("converted");
        }
    }
}
</script>
