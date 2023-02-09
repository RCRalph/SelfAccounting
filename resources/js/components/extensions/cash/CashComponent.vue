<template>
    <v-row>
        <v-col xl="8" offset-xl="2" lg="12" offset-lg="0" md="10" offset-md="1" cols="12">
            <v-card v-if="ready">
                <v-card-title class="d-flex" :class="$vuetify.breakpoint.xs ? 'flex-wrap flex-column justify-center' : 'justify-space-between'">
                    <div class="text-h5">Cash handling</div>

                    <SetCashAccountComponent
                        v-model="cashAccount"
                        :accounts="accounts"
                        @updated="updatedCashAccount"
                    ></SetCashAccountComponent>
                </v-card-title>

                <v-card-text>
                    <v-form v-model="canSet">
                        <v-row>
                            <v-col v-for="(item, i) in cashObject" :key="i" lg="6" cols="12">
                                <v-row>
                                    <v-col cols="12" sm="4" class="d-flex flex-wrap flex-column align-center" style="overflow-x: hidden">
                                        <div class="caption mb-2">Value</div>
                                        <h2 style='white-space: nowrap; font-weight: normal' :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">{{ item.value }}</h2>
                                    </v-col>

                                    <v-col cols="12" sm="4">
                                        <v-text-field label="Amount" v-model="ownedCash[item.id]" :rules="[validation.cash(ownedCash[item.id], 'income', true, true)]"></v-text-field>
                                    </v-col>

                                    <v-col cols="12" sm="4" class="d-flex flex-wrap flex-column align-center" style="overflow-x: hidden">
                                        <div class="caption mb-2">Sum</div>
                                        <h2 style='white-space: nowrap; font-weight: normal' :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">{{ (ownedCash[item.id] * cash[item.id] || 0) | addSpaces }} {{ currencies.usedCurrencyObject.ISO }}</h2>
                                    </v-col>

                                    <v-divider v-if="$vuetify.breakpoint.xs" class="my-4"></v-divider>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-form>

                    <v-divider v-if="!$vuetify.breakpoint.xs" class="mx-sm-6 mx-0 my-4 mt-sm-0"></v-divider>

                    <v-row>
                        <v-col cols="12" sm="4" class="d-flex flex-wrap flex-column align-center" style="overflow-x: hidden">
                            <div class="caption mb-2">Sum</div>
                            <h2 style='white-space: nowrap; font-weight: normal' :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">{{ (sum || 0) | addSpaces }} {{ currencies.usedCurrencyObject.ISO }}</h2>
                        </v-col>

                        <v-col cols="12" sm="4" class="d-flex flex-wrap flex-column align-center" style="overflow-x: hidden">
                            <div class="caption mb-2">Current balance</div>
                            <h2 style='white-space: nowrap; font-weight: normal' :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">{{ currentBalance | addSpaces }} {{ currencies.usedCurrencyObject.ISO }}</h2>
                        </v-col>

                        <v-col cols="12" sm="4" class="d-flex flex-wrap flex-column align-center" style="overflow-x: hidden">
                            <div class="caption mb-2">Difference</div>
                            <h2 style='white-space: nowrap; font-weight: normal' :class="sumSign == '' ? 'success--text' : 'error--text'">{{ sumSign }}{{ Math.abs(sum - currentBalance || 0) | addSpaces }} {{ currencies.usedCurrencyObject.ISO }}</h2>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions class="d-flex justify-center">
                    <v-btn color="error" outlined @click="reset" :disabled="loading" class="mx-1" width="85">
                        Reset
                    </v-btn>

                    <v-btn color="success" outlined :disabled="!canSet || loading" @click="update" :loading="loading" class="mx-1" width="85">
                        Update
                    </v-btn>
                </v-card-actions>
            </v-card>

            <v-card v-else>
                <v-card-title>Cash handling</v-card-title>

                <v-card-text class="d-flex justify-center">
                    <v-progress-circular
                        indeterminate
                        size="96"
                    ></v-progress-circular>
                </v-card-text>
            </v-card>
        </v-col>

        <SuccessSnackbarComponent v-model="success" :thing="thing"></SuccessSnackbarComponent>
        <ErrorSnackbarComponent v-model="error"></ErrorSnackbarComponent>
    </v-row>
</template>

<script>
import SuccessSnackbarComponent from "@/SuccessSnackbarComponent.vue";
import SetCashAccountComponent from "@/extensions/cash/SetCashAccountComponent.vue";
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
        SetCashAccountComponent,
        SuccessSnackbarComponent,
        ErrorSnackbarComponent
    },
    data() {
        return {
            cash: {},
            ownedCash: {},
            ownedCashCopy: {},
            accounts: [],
            cashAccount: null,

            ready: false,
            canSet: true,
            success: false,
            thing: "",
            error: false,
            loading: false
        }
    },
    computed: {
        cashObject() {
            return Object.entries(this.cash).map(item => ({
                id: item[0],
                value: `${this.$options.filters.addSpaces(item[1])} ${this.currencies.usedCurrencyObject.ISO}`
            }));
        },
        sum() {
            let sum = 0;

            this.cashObject.forEach(item => {
                sum += this.cash[item.id] * (this.ownedCash[item.id] || 0);
            });

            return Math.round(sum * 100) / 100;
        },
        sumSign() {
            if (this.sum - this.currentBalance > 0) {
                return "+";
            }
            else if (this.sum - this.currentBalance < 0) {
                return "-";
            }
            else {
                return "";
            }
        },
        currentBalance() {
            return this.accounts.find(item => item.id == this.cashAccount).balance;
        }
    },
    methods: {
        getData() {
            this.ready = false;

            axios.get(`/web-api/extensions/cash/${this.currencies.usedCurrency}/list`)
                .then(response => {
                    const data = response.data;

                    this.cash = data.cash;
                    this.cashAccount = data.cashAccount;
                    this.ownedCash = data.ownedCash;
                    this.ownedCashCopy = _.cloneDeep(data.ownedCash);
                    this.accounts = data.accounts;

                    this.ready = true;
                })
        },
        updatedCashAccount() {
            this.thing = "updated account";
            this.success = true;
        },
        reset() {
            this.ownedCash = _.cloneDeep(this.ownedCashCopy)
        },
        update() {
            this.loading = true;

            const cash = Object.keys(this.ownedCash).map(item => ({ id: item, amount: this.ownedCash[item] }));

            axios.post(`/web-api/extensions/cash/${this.currencies.usedCurrency}`, { cash })
                .then(() => {
                    this.ownedCashCopy = _.cloneDeep(this.ownedCash);
                    this.loading = false;
                    this.thing = "updated cash"
                    this.success = true;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.loading = false, 2000);
                })
        }
    },
    mounted() {
        this.getData()
        this.currencies.$subscribe(() => this.getData());
    }
}
</script>
