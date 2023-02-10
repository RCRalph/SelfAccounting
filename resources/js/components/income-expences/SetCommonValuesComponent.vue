<template>
    <v-dialog v-model="dialog" max-width="700">
        <template v-slot:activator="{ on, attrs }">
            <v-btn
                outlined
                color="primary"
                :class="$vuetify.breakpoint.xs && 'mt-2'"
                v-bind="attrs" v-on="on"
            >
                Set common values
            </v-btn>
        </template>

        <v-card>
            <v-card-title>Set common values</v-card-title>

            <v-card-text>
                <v-form v-model="canUpdate">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field
                                type="date"
                                label="Date"
                                v-model="value.date"
                                :min="usedAccount.start_date"
                                :rules="[validation.date(true, usedAccount.start_date, true)]"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="8">
                            <v-combobox
                                label="Title"
                                :items="titles"
                                v-model="value.title"
                                counter="64"
                                :rules="[validation.title(true)]"
                                ref="title"
                            ></v-combobox>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" md="6">
                            <v-text-field
                                label="Amount"
                                v-model="value.amount"
                                :error-messages="amount.error"
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

                        <v-col cols="12" md="6">
                            <v-text-field
                                label="Price"
                                v-model="value.price"
                                :error-messages="price.error"
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

                                        Allowed operations: <strong>+ - * / ^</strong>
                                    </v-tooltip>
                                </template>
                            </v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" md="6">
                            <v-select
                                v-model="value.category_id"
                                :items="categories"
                                item-text="name"
                                item-value="id"
                                label="Category"
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-select
                                v-model="value.account_id"
                                :items="accounts"
                                item-text="name"
                                item-value="id"
                                label="Account"
                            ></v-select>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <v-card-actions class="d-flex justify-center">
                <v-btn color="success" outlined :disabled="!canUpdate || disableUpdate" @click="update">
                    Update
                </v-btn>
            </v-card-actions>
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
        accounts: {
            required: true,
            type: Array
        },
        categories: {
            required: true,
            type: Array
        },
        value: {
            required: true,
            type: Object
        },
        disableUpdate: {
            required: true,
            type: Boolean
        },
        titles: {
            required: true,
            type: Array
        }
    },
    data() {
        return {
            dialog: false,
            canUpdate: true,
            keys: {
                amount: 0,
                price: 0
            }
        }
    },
    computed: {
        amount() {
            this.keys.amount;
            return new Calculator(this.value.amount, Calculator.FIELDS.amount, true).resultObject;
        },
        price() {
            this.keys.price;
            return new Calculator(this.value.price, Calculator.FIELDS.price, true).resultObject;
        },
        usedCategory() {
            return this.categories.find(item => item.id == this.value.category_id);
        },
        usedAccount() {
            return this.accounts.find(item => item.id == this.value.account_id);
        }
    },
    methods: {
        update() {
            this.$refs.title.blur();

            this.$nextTick(() => {
                this.$emit("update", this.value);
                this.dialog = false;
            });
        }
    }
}
</script>
