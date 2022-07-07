<template>
    <v-dialog v-model="dialog" max-width="800">
        <template v-slot:activator="{ on, attrs }">
            <v-btn outlined v-bind="attrs" v-on="on">Add {{ type }}</v-btn>
        </template>

        <v-card v-if="ready">
            <v-card-title class="d-flex justify-space-between">
                <div>Add {{ type }}</div>

                <SetCommonValuesComponent
                    :value="clonedCommonValues"
                    :categories="categories"
                    :means="means"
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
                                    <v-text-field type="date" label="Date" v-model="data[i - 1].date" :min="usedMean.first_entry_date" :rules="[validation.date(usedMean.first_entry_date)]"></v-text-field>
                                </v-col>

                                <v-col cols="12" md="8">
                                    <v-combobox
                                        label="Title"
                                        :items="titles"
                                        v-model="data[i - 1].title"
                                        counter="64"
                                        :rules="[validation.title(false)]"
                                    ></v-combobox>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-text-field label="Amount" v-model="data[i - 1].amount" :rules="[validation.amount(false)]"></v-text-field>
                                </v-col>

                                <v-col cols="12" md="4">
                                    <v-text-field label="Price" v-model="data[i - 1].price" :rules="[validation.price(false)]" :suffix="currencies.usedCurrencyObject.ISO"></v-text-field>
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
                                        v-model="data[i - 1].mean_id"
                                        :items="means"
                                        item-text="name"
                                        item-value="id"
                                        label="Mean of payment"
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
                <div>
                    <v-btn outlined rounded fab small @click="page = 0" :disabled="page == 0">
                        <v-icon>mdi-arrow-collapse-left</v-icon>
                    </v-btn>

                    <v-btn outlined rounded fab small class="ms-2" @click="page--" :disabled="page == 0">
                        <v-icon>mdi-arrow-left</v-icon>
                    </v-btn>
                </div>

                <div>
                    <v-btn color="error" class="mx-1" width="90" outlined :disabled="data.length == 1 || loading" @click="removeData">
                        Delete
                    </v-btn>

                    <v-btn color="success" class="mx-1" width="90" outlined :disabled="!canSubmit || loading" @click="submit" :loading="loading">
                        Submit
                    </v-btn>
                </div>

                <div>
                    <v-btn outlined rounded fab small class="me-2" @click="nextPage" :disabled="this.page == this.data.length - 1">
                        <v-icon>mdi-arrow-right</v-icon>
                    </v-btn>

                    <v-btn outlined rounded fab small @click="lastPage" :color="this.page == this.data.length - 1 ? 'primary' : undefined">
                        <v-icon v-if="this.page != this.data.length - 1">mdi-arrow-collapse-right</v-icon>
                        <v-icon v-else>mdi-plus</v-icon>
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
import SetCommonValuesComponent from "@/income-outcome/SetCommonValuesComponent.vue";

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
        ErrorSnackbarComponent,
        SetCommonValuesComponent
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
            commonValues: {
                date: new Date().toISOString().split("T")[0],
                title: "",
                amount: 1,
                price: "",
                currency_id: this.currencies.usedCurrency,
                category_id: null,
                mean_id: null
            },
            titles: [],

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
                    this.means = data.means;
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
            const amount = typeof this.data[this.page].amount == "string" ? this.data[this.page].amount.replaceAll(",", ".") : this.data[this.page].amount,
                price = typeof this.data[this.page].price == "string" ? this.data[this.page].price.replaceAll(",", ".") : this.data[this.page].price;

            return Math.round(amount * price * 100) / 100;
        },
        sum() {
            let sum = 0;
            this.data.forEach(item => sum += item.amount * item.price);
            return sum;
        },
        clonedCommonValues() {
            return _.cloneDeep(this.commonValues);
        }
    },
    methods: {
        submit() {
            this.loading = true;

            const dataNoComma = _.cloneDeep(this.data);
            dataNoComma.forEach((item, i) => {
                if (typeof item.amount == "string") {
                    dataNoComma[i].amount.replaceAll(",", ".");
                }
                if (typeof item.price == "string") {
                    dataNoComma[i].price.replaceAll(",", ".");
                }
            })

            axios
                .post(`/web-api/${this.type}`, {data: dataNoComma})
                .then(() => {
                    this.$emit("added");
                    this.dialog = false;
                    this.loading = false;
                    this.data = [];
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.loading = false, 2000);
                })
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
            this.data.forEach((item, i) => {
                Object.keys(newValues).forEach(item1 => {
                    if (this.commonValues[item1] != newValues[item1] && newValues[item1] !== "") {
                        this.data[i][item1] = newValues[item1];
                    }
                })
            })

            this.commonValues = newValues;
        }
    }
}
</script>
