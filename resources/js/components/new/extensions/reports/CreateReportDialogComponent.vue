<template>
    <v-dialog v-model="dialog" max-width="700">
        <template v-slot:activator="{ on, attrs }">
            <v-btn outlined v-bind="attrs" v-on="on">Create report</v-btn>
        </template>

        <v-card>
            <v-card-title>Create report</v-card-title>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <v-row no-gutters>
                        <v-col cols="12" sm="7">
                            <v-text-field
                                label="Title"
                                v-model="data.title"
                                counter="64"
                                :rules="[validation.title()]"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="5" class="d-flex justify-sm-center justify-start">
                            <v-switch :color="$vuetify.theme.dark ? 'white' : 'grey'" v-model="data.calculate_sum">
                                <template v-slot:label>
                                    Show sum
                                </template>
                            </v-switch>
                        </v-col>
                    </v-row>

                    <v-row no-gutters>
                        <v-col cols="12" sm="6" class="d-flex justify-sm-center justify-start">
                            <v-switch :color="$vuetify.theme.dark ? 'white' : 'grey'" v-model="data.income_addition">
                                <template v-slot:label>
                                    Add&nbsp;income, subtract&nbsp;outcome
                                </template>
                            </v-switch>
                        </v-col>

                        <v-col cols="12" sm="6" class="d-flex justify-sm-center justify-start">
                            <v-switch :color="$vuetify.theme.dark ? 'white' : 'grey'" :false-value="true" :true-value="false" v-model="data.income_addition">
                                <template v-slot:label>
                                    Add&nbsp;outcome, subtract&nbsp;income
                                </template>
                            </v-switch>
                        </v-col>

                        <v-col cols="12" sm="6" class="d-flex justify-sm-center justify-start">
                            <v-switch :color="$vuetify.theme.dark ? 'white' : 'grey'" :false-value="true" :true-value="false" v-model="data.sort_dates_desc">
                                <template v-slot:label>
                                    Sort dates descending
                                </template>
                            </v-switch>
                        </v-col>

                        <v-col cols="12" sm="6" class="d-flex justify-sm-center justify-start">
                            <v-switch :color="$vuetify.theme.dark ? 'white' : 'grey'" v-model="data.sort_dates_desc">
                                <template v-slot:label>
                                    Sort dates ascending
                                </template>
                            </v-switch>
                        </v-col>
                    </v-row>

                    <v-divider></v-divider>

                    <v-simple-table>
                        <template v-slot:default>
                            <thead>
                                <tr>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Title</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Value</th>
                                    <th class="text-center">Category</th>
                                    <th class="text-center">Mean of payment</th>
                                </tr>
                            </thead>

                            <tbody class="disable-hover">
                                <tr>
                                    <td>
                                        <div class="checkbox-centered">
                                            <v-checkbox v-model="data.columns.date"></v-checkbox>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="checkbox-centered">
                                            <v-checkbox v-model="data.columns.title"></v-checkbox>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="checkbox-centered">
                                            <v-checkbox v-model="data.columns.amount"></v-checkbox>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="checkbox-centered">
                                            <v-checkbox v-model="data.columns.price"></v-checkbox>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="checkbox-centered">
                                            <v-checkbox v-model="data.columns.value"></v-checkbox>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="checkbox-centered">
                                            <v-checkbox v-model="data.columns.category_id"></v-checkbox>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="checkbox-centered">
                                            <v-checkbox v-model="data.columns.mean_id"></v-checkbox>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
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
                title: "",
                calculate_sum: true,
                income_addition: true,
                sort_dates_desc: false,
                columns: {
                    date: true,
                    title: true,
                    amount: true,
                    price: true,
                    value: true,
                    category_id: true,
                    mean_id: true
                },
                queries: [],
                additionalEntries: [],
                users: []
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
                .post(`/web-api/settings/categories`, this.data)
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
