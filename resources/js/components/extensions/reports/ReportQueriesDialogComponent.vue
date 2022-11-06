<template>
    <v-dialog v-model="dialog" max-width="750" persistent>
        <template v-slot:activator="{ on, attrs }">
            <v-btn
                outlined
                :block="$vuetify.breakpoint.xs"
                width="205"
                class="my-1"
                v-bind="attrs" v-on="on"
            >
                Queries
            </v-btn>
        </template>

        <v-card>
            <v-card-title>Queries</v-card-title>

            <v-card-text v-if="value.length">
                <v-form v-model="canUpdate">
                    <div v-for="i in value.length" :key="i">
                        <div v-show="page == i - 1">
                            <v-row>
                                <v-col cols="12" sm="6" md="3">
                                    <v-text-field
                                        type="date"
                                        label="Minimal date"
                                        v-model="value[i - 1].min_date"
                                        :max="value[i - 1].max_date"
                                        min="1970-01-01"
                                        :rules="[validation.date(true)]"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12" sm="6" md="3">
                                    <v-text-field
                                        type="date"
                                        label="Maximal date"
                                        v-model="value[i - 1].max_date"
                                        :min="value[i - 1].min_date || '1970-01-01'"
                                        :rules="[validation.date(true)]"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12" md="6">
                                    <v-combobox
                                        label="Title"
                                        :items="titles"
                                        v-model="value[i - 1].title"
                                        counter="64"
                                        :rules="[validation.title(true)]"
                                        ref="title"
                                    ></v-combobox>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="12" sm="6" md="3">
                                    <v-text-field
                                        label="Minimal amount"
                                        v-model="value[i - 1].min_amount"
                                        :rules="[validation.amount(true, true), validationArray[i - 1].minAmount]"
                                        @input="validateFields"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12" sm="6" md="3">
                                    <v-text-field
                                        label="Maximal amount"
                                        v-model="value[i - 1].max_amount"
                                        :rules="[validation.amount(true, true), validationArray[i - 1].maxAmount]"
                                        @input="validateFields"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12" sm="6" md="3">
                                    <v-text-field
                                        label="Minimal price"
                                        v-model="value[i - 1].min_price"
                                        :rules="[validation.price(true, true), validationArray[i - 1].minPrice]"
                                        :suffix="value[i - 1].currency_id && currencies.findCurrency(value[i - 1].currency_id).ISO"
                                        @input="validateFields"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12" sm="6" md="3">
                                    <v-text-field
                                        label="Maximal price"
                                        v-model="value[i - 1].max_price"
                                        :rules="[validation.price(true, true), validationArray[i - 1].maxPrice]"
                                        :suffix="value[i - 1].currency_id && currencies.findCurrency(value[i - 1].currency_id).ISO"
                                        @input="validateFields"
                                    ></v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="12" md="3">
                                    <v-select
                                        v-model="value[i - 1].currency_id"
                                        :items="currenciesForSelect"
                                        item-text="ISO"
                                        item-value="id"
                                        label="Currency"
                                        @input="resetSelects"
                                    ></v-select>
                                </v-col>

                                <v-col cols="12" md="3">
                                    <v-select
                                        v-model="value[i - 1].category_id"
                                        :items="categoriesForSelect"
                                        item-text="name"
                                        item-value="id"
                                        label="Category"
                                        :disabled="!value[i - 1].currency_id || !categoriesForSelect"
                                    ></v-select>
                                </v-col>

                                <v-col cols="12" md="3">
                                    <v-select
                                        v-model="value[i - 1].mean_id"
                                        :items="meansForSelect"
                                        item-text="name"
                                        item-value="id"
                                        label="Mean of payment"
                                        :disabled="!value[i - 1].currency_id || !meansForSelect"
                                    ></v-select>
                                </v-col>

                                <v-col cols="12" md="3">
                                    <v-select
                                        v-model="value[i - 1].query_data"
                                        :items="queryTypes"
                                        label="Query type"
                                    ></v-select>
                                </v-col>
                            </v-row>
                        </div>
                    </div>
                </v-form>
            </v-card-text>

            <v-card-text v-else class="text-center text-h6 pb-0">Press + to add a query</v-card-text>

            <v-card-actions class="d-flex justify-space-between">
                <div :class="$vuetify.breakpoint.xs && 'd-flex flex-wrap flex-column-reverse'">
                    <v-btn outlined rounded fab small class="ma-1" color="error" @click="removeData" :disabled="!value.length">
                        <v-icon>mdi-delete</v-icon>
                    </v-btn>

                    <v-btn outlined rounded fab small class="ma-1" @click="page--" :disabled="page <= 0">
                        <v-icon>mdi-arrow-left</v-icon>
                    </v-btn>
                </div>

                <div>
                    <v-btn color="success" class="mx-1" outlined :disabled="!!value.length && !canUpdate" @click="update">
                        Submit
                    </v-btn>
                </div>

                <div :class="$vuetify.breakpoint.xs && 'd-flex flex-wrap flex-column'">
                    <v-btn outlined rounded fab small class="ma-1" @click="page++" :disabled="page >= value.length - 1">
                        <v-icon>mdi-arrow-right</v-icon>
                    </v-btn>

                    <v-btn outlined rounded fab small class="ma-1" @click="appendData" color="primary">
                        <v-icon>mdi-plus</v-icon>
                    </v-btn>
                </div>
            </v-card-actions>

            <div v-if="value.length" class="text-center text-caption pb-2 text--secondary">
                {{ page + 1 }} / {{ value.length }}
            </div>
        </v-card>
    </v-dialog>
</template>

<script>
import { useCurrenciesStore } from "&/stores/currencies";
import validation from "&/mixins/validation";
import main from "&/mixins/main";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [validation, main],
    props: {
        value: {
            required: true,
            type: Array
        },
        titles: {
            required: true,
            type: Array
        },
        categories: {
            required: true,
            type: Object
        },
        means: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            page: 0,
            startData: {
                query_data: "income",
                min_date: null,
                max_date: null,
                title: null,
                min_amount: null,
                max_amount: null,
                min_price: null,
                max_price: null,
                currency_id: null,
                currency_id: null,
                category_id: null,
                mean_id: null
            },
            queryTypes: [
                { value: "income", text: "Income" },
                { value: "outcome", text: "Outcome" }
            ],
            validationArray: [],

            dialog: false,
            canUpdate: true
        }
    },
    computed: {
        currenciesForSelect() {
            return [{ id: null, ISO: "All currencies" }, ...this.currencies.currencies];
        },
        categoriesForSelect() {
            let nullObj = { id: null, name: "All categories" };

            if (this.categories[this.value[this.page].currency_id]) {
                return [ nullObj, ...this.categories[this.value[this.page].currency_id]];
            }

            return [nullObj];
        },
        meansForSelect() {
            let nullObj = { id: null, name: "All means of payment" };

            if (this.means[this.value[this.page].currency_id]) {
                return [ nullObj, ...this.means[this.value[this.page].currency_id]];
            }

            return [nullObj];
        }
    },
    methods: {
        update() {
            this.$refs.title.forEach(item => item.blur());

            this.$nextTick(() => {
                this.$emit("update", this.value);
                this.dialog = false;
            });
        },
        appendData() {
            this.value.push(_.cloneDeep(this.startData));

            this.validationArray.push({
                minAmount: true,
                maxAmount: true,
                minPrice: true,
                maxPrice: true
            });

            this.page = this.value.length - 1;
        },
        removeData() {
            this.value.splice(this.page, 1);
            this.validationArray.splice(this.page, 1);

            if (this.page == this.value.length) {
                this.page--;
            }
        },
        resetSelects() {
            this.value[this.page].category_id = undefined;
            this.value[this.page].mean_id = undefined;
            this.value[this.page].category_id = null;
            this.value[this.page].mean_id = null;
        },
        compareMinMax(min, max) {
            if (!min || !max) {
                return true;
            }

            min = Number(String(min).replaceAll(",", "."));
            max = Number(String(max).replaceAll(",", "."));

            return min <= max
        },
        validateFields() {
            this.value.forEach((item, i) => {
                if (this.compareMinMax(item.min_amount, item.max_amount)) {
                    this.validationArray[i].minAmount = this.validationArray[i].maxAmount = true;
                }
                else {
                    this.validationArray[i].minAmount = this.validationArray[i].maxAmount = "Minimal amount has to be less than maximal amount";
                }

                if (this.compareMinMax(item.min_price, item.max_price)) {
                    this.validationArray[i].minPrice = this.validationArray[i].maxPrice = true;
                }
                else {
                    this.validationArray[i].minPrice = this.validationArray[i].maxPrice = "Minimal price has to be less than maximal price";
                }
            })
        }
    },
    beforeMount() {
        this.value.forEach(() => {
            this.validationArray.push({
                minAmount: true,
                maxAmount: true,
                minPrice: true,
                maxPrice: true
            });
        });
    }
}
</script>
