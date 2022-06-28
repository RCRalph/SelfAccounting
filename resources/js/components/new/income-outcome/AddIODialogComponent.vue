<template>
    <v-dialog v-model="dialog" max-width="800">
        <template v-slot:activator="{ on, attrs }">
            <v-btn outlined v-bind="attrs" v-on="on">Add {{ type }}</v-btn>
        </template>

        <v-card v-if="ready">
            <v-card-title class="d-flex justify-space-between">
                <div>Add {{ type }}</div>

                <v-btn outlined color="primary">Set common values</v-btn>
            </v-card-title>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field type="date" label="Date" v-model="data[page].date" :min="usedMean.first_entry_date" :rules="[validation.date(usedMean.first_entry_date)]"></v-text-field>
                        </v-col>

                        <v-col cols="12" md="8">
                            <v-text-field label="Title" v-model="data[page].title" counter="64" :rules="[validation.title]"></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field label="Amount" v-model="data[page].amount" :rules="[validation.amount]"></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                            <v-text-field label="Price" v-model="data[page].price" :rules="[validation.price]" :suffix="currencies.usedCurrencyObject.ISO"></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" style='display: flex; flex-wrap: wrap; flex-direction: column; overflow-x: hidden'>
                            <div class="caption mb-2">Value</div>
                            <h2 style='white-space: nowrap; font-weight: normal' :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">{{ valueField | addSpaces }} {{ currencies.usedCurrencyObject.ISO }}</h2>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" md="6">
                            <v-select
                                v-model="data[page].category_id"
                                :items="categories"
                                item-text="name"
                                item-value="id"
                                label="Category"
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-select
                                v-model="data[page].mean_id"
                                :items="means"
                                item-text="name"
                                item-value="id"
                                label="Mean of payment"
                            ></v-select>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <v-card-actions class="d-flex justify-space-between">
                <div>
                    <v-btn outlined rounded fab small @click="page = 0" :disabled="page == 0">
                        <v-icon>mdi-arrow-collapse-left</v-icon>
                    </v-btn>

                    <v-btn outlined rounded fab small class="ms-2" @click="page--" :disabled="page == 0">
                        <v-icon>mdi-arrow-left</v-icon>
                    </v-btn>
                </div>

                <div>
                    <v-btn outlined color="error" class="mx-1" width="90" :disabled="data.length == 1">
                        Delete
                    </v-btn>

                    <v-btn color="success" class="mx-1" outlined :disabled="!canSubmit" @click="submit" :loading="loading" width="90">
                        Submit
                    </v-btn>
                </div>

                <div>
                    <v-btn outlined rounded fab small class="me-2" @click="nextPage">
                        <v-icon>mdi-arrow-right</v-icon>
                    </v-btn>

                    <v-btn outlined rounded fab small @click="page = data.length - 1" :disabled="page == data.length - 1">
                        <v-icon>mdi-arrow-collapse-right</v-icon>
                    </v-btn>
                </div>
            </v-card-actions>

            <div class="text-center text-caption pb-2 text--secondary">
                {{ page + 1 }} / {{ this.data.length }}
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
    props: {
        type: String
    },
    data() {
        return {
            dialog: false,
            data: [],
            means: [],
            categories: [],
            page: 0,

            ready: false,
            error: false,
            loading: false,
            canSubmit: false
        }
    },
    watch: {
        dialog() {
            this.ready = false;

            axios
                .get(`/web-api/${this.type}/data/${this.currencies.usedCurrency}`)
                .then(response => {
                    const data = response.data;

                    this.means = data.means;
                    this.categories = data.categories;
                    this.appendData();

                    this.ready = true;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                })
        }
    },
    computed: {
        usedCategory() {
            return this.categories.find(item => item.id == this.data[this.page].category_id);
        },
        usedMean() {
            return this.means.find(item => item.id == this.data[this.page].mean_id);
        },
        valueField() {
            return Math.round(this.data[this.page].amount * this.data[this.page].price * 100) / 100;
        }
    },
    methods: {
        submit() {
            this.loading = true;

            const dataNoComma = _.cloneDeep(this.data);
            if (typeof dataNoComma.amount == "string") {
                dataNoComma.amount.replaceAll(",", ".");
            }
            if (typeof dataNoComma.price == "string") {
                dataNoComma.price.replaceAll(",", ".");
            }

            axios
                .post(`/web-api/${this.type}`, dataNoComma)
                .then(() => {
                    this.loading = false;
                    this.dataCopy = _.cloneDeep(this.data);
                    this.$emit("added");
                    this.dialog = false;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.loading = false, 2000);
                })
        },
        appendData() {
            this.data.push({
                date: "",
                title: "",
                amount: "",
                price: "",
                currency_id: this.currencies.usedCurrency,
                category_id: null,
                mean_id: null
            })
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
        }
    }
}
</script>
