<template>
    <v-dialog v-model="dialog" max-width="700" persistent>
        <template v-slot:activator="{ on, attrs }">
            <v-btn
                outlined
                :block="$vuetify.breakpoint.xs"
                width="205"
                class="my-1"
                v-bind="attrs" v-on="on"
            >
                Additional entries
            </v-btn>
        </template>

        <v-card>
            <v-card-title>Additional entries</v-card-title>

            <v-card-text v-if="value.length">
                <v-form v-model="canUpdate">
                    <div v-for="i in value.length" :key="i">
                        <div v-show="page == i - 1">
                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-text-field type="date" label="Date" v-model="value[i - 1].date" min="1970-01-01" :rules="[validation.date(false)]"></v-text-field>
                                </v-col>

                                <v-col cols="12" md="8">
                                    <v-combobox
                                        label="Title"
                                        :items="titles"
                                        v-model="value[i - 1].title"
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
                                        v-model="value[i - 1].amount"
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

                                                Allowed operations: <strong>+ - * / ^</strong>
                                            </v-tooltip>
                                        </template>
                                    </v-text-field>
                                </v-col>

                                <v-col cols="12" md="4">
                                    <v-text-field
                                        label="Price"
                                        v-model="value[i - 1].price"
                                        :error-messages="keys.price ? price.error : undefined"
                                        :hint="price.hint"
                                        @input="keys.price++"
                                        :suffix="currencies.findCurrency(value[i - 1].currency_id).ISO"
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

                                <v-col cols="12" md="4" style='display: flex; flex-wrap: wrap; flex-direction: column; overflow-x: hidden'>
                                    <div class="caption mb-2">Value</div>
                                    <h2 style='white-space: nowrap; font-weight: normal' :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">{{ valueField | addSpaces }} {{ currencies.findCurrency(value[i - 1].currency_id).ISO }}</h2>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-select
                                        v-model="value[i - 1].currency_id"
                                        :items="currencies.currencies"
                                        item-text="ISO"
                                        item-value="id"
                                        label="Currency"
                                        @input="resetSelects"
                                    ></v-select>
                                </v-col>

                                <v-col cols="12" md="4">
                                    <v-select
                                        v-model="value[i - 1].category_id"
                                        :items="categoriesForSelect"
                                        item-text="name"
                                        item-value="id"
                                        label="Category"
                                        :disabled="!categoriesForSelect"
                                    ></v-select>
                                </v-col>

                                <v-col cols="12" md="4">
                                    <v-select
                                        v-model="value[i - 1].account_id"
                                        :items="accountsForSelect"
                                        item-text="name"
                                        item-value="id"
                                        label="Account"
                                        :disabled="!accountsForSelect"
                                    ></v-select>
                                </v-col>
                            </v-row>
                        </div>
                    </div>
                </v-form>
            </v-card-text>

            <v-card-text v-else class="text-center text-h6 pb-0">Press + to add an entry</v-card-text>

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
        accounts: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            page: 0,
            startData: {
                date: new Date().toISOString().split("T")[0],
                title: "",
                amount: 1,
                price: "",
                currency_id: null,
                category_id: null,
                account_id: null
            },
            keys: {
                amount: 0,
                price: 0
            },

            dialog: false,
            canUpdate: true
        }
    },
    watch: {
        page() {
            this.keys.amount = 0;
            this.keys.price = 0;
        }
    },
    computed: {
        categoriesForSelect() {
            let nullObj = { id: null, name: "N/A" };

            if (this.categories[this.value[this.page].currency_id]) {
                return [ nullObj, ...this.categories[this.value[this.page].currency_id]];
            }

            return [nullObj];
        },
        accountsForSelect() {
            let nullObj = { id: null, name: "N/A" };

            if (this.accounts[this.value[this.page].currency_id]) {
                return [ nullObj, ...this.accounts[this.value[this.page].currency_id]];
            }

            return [nullObj];
        },
        amount() {
            this.keys.amount;
            return new Calculator(this.value[this.page].amount, Calculator.FIELDS.amount, false, true).resultObject;
        },
        price() {
            this.keys.price;
            return new Calculator(this.value[this.page].price, Calculator.FIELDS.price, false, true, true).resultObject;
        },
        valueField() {
            return _.round(this.amount.value * this.price.value, 2) || 0;
        },
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

            this.page = this.value.length - 1;
        },
        removeData() {
            this.value.splice(this.page, 1);

            if (this.page == this.value.length) {
                this.page--;
            }
        },
        usedCategory() {
            return this.categoriesForSelect.find(item => item.id == this.value[this.page].category_id);
        },
        usedAccount() {
            return this.accountsForSelect.find(item => item.id == this.value[this.page].account_id);
        },
        resetSelects() {
            this.value[this.page].category_id = undefined;
            this.value[this.page].account_id = undefined;
            this.value[this.page].category_id = null;
            this.value[this.page].account_id = null;
        }
    },
    mounted() {
        this.startData.currency_id = this.currencies.usedCurrency;
    }
}
</script>
