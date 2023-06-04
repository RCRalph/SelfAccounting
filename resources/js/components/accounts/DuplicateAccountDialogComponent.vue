<template>
    <v-dialog v-model="dialog" max-width="300">
        <template v-slot:activator="{ on: dialogOn, attrs: dialogAttrs }">
            <v-tooltip bottom>
                <template v-slot:activator="{ on: tooltipOn, attrs: tooltipAttrs }">
                    <v-icon class="mx-1 cursor-pointer" v-bind="{ ...dialogAttrs, ...tooltipAttrs }" v-on="{ ...dialogOn, ...tooltipOn }">mdi-content-duplicate</v-icon>
                </template>

                <span>Duplicate account</span>
            </v-tooltip>
        </template>

        <v-card>
            <v-card-title>Duplicate account</v-card-title>

            <v-card-text>
                <v-select
                    v-model="currency"
                    :items="currencies.currencies"
                    label="Currency"
                    item-text="ISO"
                    item-value="id"
                ></v-select>
            </v-card-text>

            <v-card-actions class="d-flex justify-space-around">
                <v-btn color="success" outlined :disabled="loading" @click="duplicate" :loading="loading" class="mx-1">
                    Duplicate
                </v-btn>
            </v-card-actions>
        </v-card>

        <ErrorSnackbarComponent v-model="error"></ErrorSnackbarComponent>
    </v-dialog>
</template>

<script>
import ErrorSnackbarComponent from "@/ErrorSnackbarComponent.vue";
import { useCurrenciesStore } from "&/stores/currencies";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    components: {
        ErrorSnackbarComponent,
    },
    props: {
        id: Number
    },
    data() {
        return {
            dialog: false,
            currency: this.currencies.usedCurrency,

            error: false,
            loading: false,
            canSubmit: false
        }
    },
    methods: {
        duplicate() {
            this.loading = true;

            axios
                .post(`/web-api/accounts/account/${this.id}/duplicate`, {
                    currency: this.currency
                })
                .then(() => {
                    this.dataCopy = _.cloneDeep(this.data);
                    this.$emit("duplicated");
                    this.dialog = false;
                    this.loading = false;
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
