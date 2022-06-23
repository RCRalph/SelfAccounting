<template>
    <v-dialog v-model="dialog" max-width="800">
        <template v-slot:activator="{ on, attrs }">
            <v-icon class="mr-2 cursor-pointer" v-bind="attrs" v-on="on">mdi-pencil</v-icon>
        </template>

        <v-card v-if="ready">
            <v-card-title>Edit {{ type }}</v-card-title>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field type="date" label="Date" v-model="data.date" :min="usedMean.first_entry_date" :rules="[validation.date(usedMean.first_entry_date)]"></v-text-field>
                        </v-col>

                        <v-col cols="12" md="8">
                            <v-text-field label="Title" v-model="data.title" counter="64" :rules="[validation.title]"></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field label="Amount" v-model="data.amount" :rules="[validation.amount]"></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                            <v-text-field label="Price" v-model="data.price" :rules="[validation.price]" :suffix="currencies.usedCurrencyObject.ISO"></v-text-field>
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
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-select
                                v-model="data.mean_id"
                                :items="means"
                                item-text="name"
                                item-value="id"
                                label="Mean of payment"
                            ></v-select>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <v-card-actions class="d-flex justify-space-around">
                <v-btn color="error" outlined @click="reset" :disabled="loading">
                    Reset
                </v-btn>

                <v-btn color="success" outlined :disabled="!canSubmit" @click="update" :loading="loading">
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
import ErrorSnackbarComponent from "./ErrorSnackbarComponent.vue";

import { useCurrenciesStore } from "../../../stores/currencies";
import validation from "../../../mixins/validation";
import main from "../../../mixins/main";

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
        type: String,
        id: Number
    },
    data() {
        return {
            dialog: false,
            data: {},
            dataCopy: {},
            means: [],
            categories: [],

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
                .get(`/web-api/${this.type}/${this.id}`)
                .then(response => {
                    const data = response.data;

                    this.data = data.data;
                    this.dataCopy = _.cloneDeep(data.data);
                    this.means = data.means;
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
        usedCategory() {
            return this.categories.find(item => item.id == this.data.category_id);
        },
        usedMean() {
            return this.means.find(item => item.id == this.data.mean_id);
        },
        valueField() {
            return Math.round(this.data.amount * this.data.price * 100) / 100;
        }
    },
    methods: {
        reset() {
            this.data = _.cloneDeep(this.dataCopy);
        },
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
        }
    }
}
</script>
