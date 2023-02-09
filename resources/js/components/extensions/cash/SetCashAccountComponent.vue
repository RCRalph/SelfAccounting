<template>
    <v-dialog v-model="dialog" max-width="500">
        <template v-slot:activator="{ on, attrs }">
            <v-btn outlined v-bind="attrs" v-on="on">Set account</v-btn>
        </template>

        <v-card>
            <v-card-title>Set Account Of Payment</v-card-title>

            <v-card-text>
                <v-select
                    v-model="cashAccount"
                    :items="accounts"
                    item-text="name"
                    item-value="id"
                    label="Account"
                ></v-select>
            </v-card-text>

            <v-card-actions class="d-flex justify-space-around">
                <v-btn color="success" outlined :disabled="loading" @click="submit" :loading="loading">
                    Submit
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
        ErrorSnackbarComponent
    },
    props: {
        value: {
            required: false,
            type: Number
        },
        accounts: {
            required: true,
            type: Array
        }
    },
    data() {
        return {
            dialog: false,
            cashAccount: null,
            error: false,
            loading: false
        }
    },
    watch: {
        value() {
            this.cashAccount = this.value;
        }
    },
    methods: {
        submit() {
            this.loading = true;

            axios
                .post(`/web-api/extensions/cash/${this.currencies.usedCurrency}`, { account: this.cashAccount })
                .then(() => {
                    this.$emit("input", this.cashAccount)
                    this.$emit("updated");
                    this.dialog = false;
                    this.loading = false;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.loading = false, 2000);
                })
        }
    },
    mounted() {
        this.cashAccount = this.value;
    }
}
</script>
