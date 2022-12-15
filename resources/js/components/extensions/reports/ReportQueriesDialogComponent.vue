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
                                        :rules="[validationArray[i - 1].amount]"
                                        :error-messages="keys.minAmount ? minAmount.error : undefined"
                                        :hint="minAmount.hint"
                                        @input="keys.minAmount++; validateFields()"
                                    >
                                        <template v-slot:append>
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <v-icon v-on="on" class="ml-1">
                                                        mdi-calculator
                                                    </v-icon>
                                                </template>

                                                Allowed operations: <strong>+ - * / ^</strong>
                                            </v-tooltip>
                                        </template>
                                    </v-text-field>
                                </v-col>

                                <v-col cols="12" sm="6" md="3">
                                    <v-text-field
                                        label="Maximal amount"
                                        v-model="value[i - 1].max_amount"
                                        :rules="[validationArray[i - 1].amount]"
                                        :error-messages="keys.maxAmount ? maxAmount.error : undefined"
                                        :hint="maxAmount.hint"
                                        @input="keys.maxAmount++; validateFields()"
                                    >
                                        <template v-slot:append>
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <v-icon v-on="on" class="ml-1">
                                                        mdi-calculator
                                                    </v-icon>
                                                </template>

                                                Allowed operations: <strong>+ - * / ^</strong>
                                            </v-tooltip>
                                        </template>
                                    </v-text-field>
                                </v-col>

                                <v-col cols="12" sm="6" md="3">
                                    <v-text-field
                                        label="Minimal price"
                                        v-model="value[i - 1].min_price"
                                        :rules="[validationArray[i - 1].price]"
                                        :error-messages="keys.minPrice ? minPrice.error : undefined"
                                        :hint="minPrice.hint"
                                        :suffix="value[i - 1].currency_id && currencies.findCurrency(value[i - 1].currency_id).ISO"
                                        @input="keys.minPrice++; validateFields()"
                                    >
                                        <template v-slot:append>
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <v-icon v-on="on" class="ml-1">
                                                        mdi-calculator
                                                    </v-icon>
                                                </template>

                                                Allowed operations: <strong>+ - * / ^</strong>
                                            </v-tooltip>
                                        </template>
                                    </v-text-field>
                                </v-col>

                                <v-col cols="12" sm="6" md="3">
                                    <v-text-field
                                        label="Maximal price"
                                        v-model="value[i - 1].max_price"
                                        :rules="[validationArray[i - 1].price]"
                                        :error-messages="keys.maxPrice ? maxPrice.error : undefined"
                                        :hint="maxPrice.hint"
                                        :suffix="value[i - 1].currency_id && currencies.findCurrency(value[i - 1].currency_id).ISO"
                                        @input="keys.maxPrice++; validateFields()"
                                    >
                                        <template v-slot:append>
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <v-icon v-on="on" class="ml-1">
                                                        mdi-calculator
                                                    </v-icon>
                                                </template>

                                                Allowed operations: <strong>+ - * / ^</strong>
                                            </v-tooltip>
                                        </template>
                                    </v-text-field>
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

import Calculator from "&/classes/Calculator";
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
            validationArrayObject: {
                amount: true,
                price: true
            },
            keys: {
                minAmount: 0,
                maxAmount: 0,
                minPrice: 0,
                maxPrice: 0
            },

            dialog: false,
            canUpdate: true
        }
    },
    computed: {
        minAmount() {
            this.keys.minAmount;
            return new Calculator(this.value[this.page].min_amount, Calculator.FIELDS.amount, true, true).resultObject;
        },
        maxAmount() {
            this.keys.maxAmount;
            return new Calculator(this.value[this.page].max_amount, Calculator.FIELDS.amount, true, true).resultObject;
        },
        minPrice() {
            this.keys.minPrice;
            return new Calculator(this.value[this.page].min_price, Calculator.FIELDS.price, true, true).resultObject;
        },
        maxPrice() {
            this.keys.maxPrice;
            return new Calculator(this.value[this.page].max_price, Calculator.FIELDS.price, true, true).resultObject;
        },
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
            this.validationArray.push(_.cloneDeep(this.validationArrayObject));

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

            min = new Calculator(min).resultValue;
            max = new Calculator(max).resultValue;
            if (isNaN(min) || isNaN(max)) {
                return true;
            }

            return min <= max;
        },
        validateFields() {
            this.value.forEach((item, i) => {
                this.validationArray[i].amount = !this.compareMinMax(item.min_amount, item.max_amount) ?
                    "Minimal amount has to be less than maximal amount" : true;

                this.validationArray[i].price = !this.compareMinMax(item.min_price, item.max_price) ?
                    "Minimal price has to be less than maximal price" : true;
            });
        }
    },
    beforeMount() {
        this.value.forEach(() => {
            this.validationArray.push(_.cloneDeep(this.validationArrayObject));
        });
    }
}
</script>
