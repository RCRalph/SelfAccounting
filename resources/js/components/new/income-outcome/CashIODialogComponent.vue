<template>
    <v-dialog v-model="dialog" max-width="500" persistent>
        <template v-slot:activator="{ on, attrs }">
            <v-btn class="mx-1" width="90" outlined v-on="on" v-bind="attrs" :disabled="!usesCash || disabled">
                Cash
            </v-btn>
        </template>

        <v-card v-if="ready">
            <v-card-title>Set Cash</v-card-title>

            <v-card-text>
                <v-select
                    class="mx-sm-6 mx-0"
                    v-model="selectedValues"
                    :items="cashObject"
                    item-text="value"
                    item-value="id"
                    label="Cash in use"
                    multiple
                >
                    <template v-slot:selection="{ item, index }">
                        <v-chip v-if="index < 2">
                            <span>{{ item.value }}</span>
                        </v-chip>

                        <span
                            v-if="index == 2"
                            class="grey--text text-caption"
                        >(+{{ selectedValues.length - 2 }} other{{ selectedValues.length > 3 ? "s" : "" }})</span>
                    </template>
                </v-select>

                <v-form v-model="canSet">
                    <div v-for="id in sortedSelectedValues" :key="id">
                        <v-row>
                            <v-col cols="12" sm="4" style='display: flex; flex-wrap: wrap; flex-direction: column; overflow-x: hidden'>
                                <div class="caption mb-2">Value</div>
                                <h2 style='white-space: nowrap; font-weight: normal' :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">{{ (cash[id] || 0) | addSpaces }} {{ currencies.usedCurrencyObject.ISO }}</h2>
                            </v-col>

                            <v-col cols="12" sm="4">
                                <v-text-field label="Amount" v-model="value[id]" :rules="[validation.cash(ownedCash[id])]"></v-text-field>
                            </v-col>

                            <v-col cols="12" sm="4" style='display: flex; flex-wrap: wrap; flex-direction: column; overflow-x: hidden'>
                                <div class="caption mb-2">Sum</div>
                                <h2 style='white-space: nowrap; font-weight: normal' :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">{{ (value[id] * cash[id] || 0) | addSpaces }} {{ currencies.usedCurrencyObject.ISO }}</h2>
                            </v-col>
                        </v-row>

                        <v-divider v-if="$vuetify.breakpoint.xs" class="my-4"></v-divider>
                    </div>
                </v-form>

                <v-divider class="mx-sm-6 mx-0 mb-4" v-if="selectedValues.length"></v-divider>

                <v-row v-if="selectedValues.length">
                    <v-col cols="12" sm="4" style='display: flex; flex-wrap: wrap; flex-direction: column; overflow-x: hidden'>
                        <div class="caption mb-2">Sum</div>
                        <h2 style='white-space: nowrap; font-weight: normal' :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">{{ (sum || 0) | addSpaces }} {{ currencies.usedCurrencyObject.ISO }}</h2>
                    </v-col>

                    <v-col cols="12" sm="4" style='display: flex; flex-wrap: wrap; flex-direction: column; overflow-x: hidden'>
                        <div class="caption mb-2">Entered sum</div>
                        <h2 style='white-space: nowrap; font-weight: normal' :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">{{ (sumByMeans[cashMean] || 0) | addSpaces }} {{ currencies.usedCurrencyObject.ISO }}</h2>
                    </v-col>

                    <v-col cols="12" sm="4" style='display: flex; flex-wrap: wrap; flex-direction: column; overflow-x: hidden'>
                        <div class="caption mb-2">Difference</div>
                        <h2 style='white-space: nowrap; font-weight: normal' :class="sumSign == '' ? 'success--text' : 'error--text'">{{ sumSign }}{{ (sum - sumByMeans[cashMean] || 0) | addSpaces }} {{ currencies.usedCurrencyObject.ISO }}</h2>
                    </v-col>
                </v-row>
            </v-card-text>

            <v-card-actions class="d-flex justify-center">
                <v-btn outlined :disabled="!canSet" @click="dialog = false">
                    Close
                </v-btn>
            </v-card-actions>
        </v-card>

        <v-card v-else>
            <v-card-title>Cash</v-card-title>

            <v-card-text class="d-flex justify-center">
                <v-progress-circular
                    indeterminate
                    size="96"
                ></v-progress-circular>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
import ErrorSnackbarComponent from "@/ErrorSnackbarComponent.vue";

import { useCurrenciesStore } from "&/stores/currencies";
import { useBundlesStore } from "&/stores/bundles";
import validation from "&/mixins/validation";
import main from "&/mixins/main";

export default {
    setup() {
        const currencies = useCurrenciesStore();
        const bundles = useBundlesStore();

        return { currencies, bundles };
    },
    mixins: [validation, main],
    components: {
        ErrorSnackbarComponent
    },
    props: {
        meanIDs: {
            type: Array
        },
        value: {
            required: true,
            type: Object
        },
        disabled: {
            required: true,
            type: Boolean
        },
        sumByMeans: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            dialog: false,
            canSet: true,
            cashMean: null,
            cash: {},
            ready: false,
            selectedValues: [],
            ownedCash: {}
        }
    },
    watch: {
        selectedValues(newVal, oldVal) {
            oldVal.forEach(item => {
                if (!newVal.includes(item)) {
                    delete this.value[item];
                }
            });
        }
    },
    computed: {
        usesCash() {
            return this.meanIDs.includes(this.cashMean);
        },
        cashObject() {
            return Object.entries(this.cash).map(item => ({
                id: item[0],
                value: `${this.$options.filters.addSpaces(item[1])} ${this.currencies.usedCurrencyObject.ISO}`
            }))
        },
        sortedSelectedValues() {
            const retVal = _.cloneDeep(this.selectedValues);
            retVal.sort((a, b) => a - b);

            return retVal;
        },
        sum() {
            let sum = 0;

            this.selectedValues.forEach(item => {
                sum += this.cash[item] * (this.value[item] || 0);
            });

            return Math.round(sum * 100) / 100;
        },
        sumSign() {
            if (this.sum > this.sumByMeans[this.cashMean]) {
                return "+";
            }
            else if (this.sum < this.sumByMeans[this.cashMean]) {
                return "-";
            }
            else {
                return "";
            }
        }
    },
    mounted() {
        this.ready = false;

        axios.get(`/web-api/bundles/cash/${this.currencies.usedCurrency}`)
            .then(response => {
                const data = response.data;

                this.cash = data.cash;
                this.cashMean = data.cashMean;
                this.ownedCash = data.ownedCash;

                this.ready = true;
            })
    }
}
</script>
