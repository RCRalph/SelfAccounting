<template>
    <v-dialog v-model="dialog" max-width="700">
        <template v-slot:activator="{ on, attrs }">
            <v-btn outlined v-bind="attrs" v-on="on">Add mean of payment</v-btn>
        </template>

        <v-card>
            <v-card-title>Add mean of payment</v-card-title>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <v-row>
                        <v-col cols="12" sm="8" offset-sm="2">
                            <v-text-field
                                label="Name"
                                v-model="data.name"
                                counter="32"
                                :rules="[validation.name(false)]"
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="6" sm="4">
                            <v-switch :color="$vuetify.theme.dark ? 'white' : 'grey'" v-model="data.income_mean">
                                <template v-slot:label>
                                    Show in income
                                </template>
                            </v-switch>
                        </v-col>

                        <v-col cols="6" sm="4">
                            <v-switch :color="$vuetify.theme.dark ? 'white' : 'grey'" v-model="data.outcome_mean">
                                <template v-slot:label>
                                    Show in outcome
                                </template>
                            </v-switch>
                        </v-col>

                        <v-col cols="6" sm="4">
                            <v-switch :color="$vuetify.theme.dark ? 'white' : 'grey'" v-model="data.show_on_charts">
                                <template v-slot:label>
                                    Show on charts
                                </template>
                            </v-switch>
                        </v-col>

                        <v-col cols="6" sm="4">
                            <v-switch :color="$vuetify.theme.dark ? 'white' : 'grey'" v-model="data.count_to_summary">
                                <template v-slot:label>
                                    Count to summary
                                </template>
                            </v-switch>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <v-text-field type="date" label="First entry date" v-model="data.first_entry_date" min="1970-01-01" :rules="[validation.date(false)]"></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <v-text-field label="Start balance" v-model="data.first_entry_amount" :rules="[validation.startBalance(false)]"></v-text-field>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <v-card-actions class="d-flex justify-space-around">
                <v-btn color="success" outlined :disabled="!canSubmit || loading" @click="submit" :loading="loading">
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
import validation from "&/mixins/validation";
import main from "&/mixins/main";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [validation, main],
    components: {
        ErrorSnackbarComponent
    },
    data() {
        return {
            dialog: false,
            startData: {
                currency_id: this.currencies.usedCurrency,
                name: "",
                income_mean: true,
                outcome_mean: true,
                show_on_charts: true,
                count_to_summary: true,
                first_entry_date: new Date().toISOString().split("T")[0],
                first_entry_amount: 0
            },
            data: {},

            error: false,
            loading: false,
            canSubmit: false
        }
    },
    methods: {
        submit() {
            this.loading = true;

            axios
                .post(`/web-api/settings/means`, this.data)
                .then(() => {
                    this.data = _.cloneDeep(this.startData);
                    this.$emit("added");
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
        this.data = _.cloneDeep(this.startData);
    }
}
</script>
