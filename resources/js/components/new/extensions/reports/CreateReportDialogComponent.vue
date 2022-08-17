<template>
    <v-dialog v-model="dialog" max-width="800">
        <template v-slot:activator="{ on, attrs }">
            <v-btn outlined v-bind="attrs" v-on="on">Create report</v-btn>
        </template>

        <v-card v-if="ready">
            <v-card-title class="d-flex justify-space-between">
                <div>Create report</div>

                <v-btn outlined>Share</v-btn>
            </v-card-title>

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
                                            <v-checkbox :color="$vuetify.theme.dark ? 'white' : 'grey'"  v-model="data.columns.date"></v-checkbox>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="checkbox-centered">
                                            <v-checkbox :color="$vuetify.theme.dark ? 'white' : 'grey'"  v-model="data.columns.title"></v-checkbox>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="checkbox-centered">
                                            <v-checkbox :color="$vuetify.theme.dark ? 'white' : 'grey'"  v-model="data.columns.amount"></v-checkbox>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="checkbox-centered">
                                            <v-checkbox :color="$vuetify.theme.dark ? 'white' : 'grey'"  v-model="data.columns.price"></v-checkbox>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="checkbox-centered">
                                            <v-checkbox :color="$vuetify.theme.dark ? 'white' : 'grey'"  v-model="data.columns.value"></v-checkbox>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="checkbox-centered">
                                            <v-checkbox :color="$vuetify.theme.dark ? 'white' : 'grey'"  v-model="data.columns.category_id"></v-checkbox>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="checkbox-centered">
                                            <v-checkbox :color="$vuetify.theme.dark ? 'white' : 'grey'"  v-model="data.columns.mean_id"></v-checkbox>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>

                    <div class="d-flex justify-space-around flex-wrap flex-sm-row flex-column mt-2">
                        <ReportQueriesDialogComponent
                            v-model="data.queries"
                            :titles="titles"
                            :categories="categories"
                            :means="means"
                            :disableUpdate="!canSubmit || loading"
                        ></ReportQueriesDialogComponent>

                        <v-btn outlined :block="$vuetify.breakpoint.xs" width="205" class="my-1">Additional entries</v-btn>
                    </div>
                </v-form>
            </v-card-text>

            <v-card-actions class="d-flex justify-space-around">
                <v-btn color="success" outlined :disabled="!canSubmit || loading" @click="submit" :loading="loading">
                    Submit
                </v-btn>
            </v-card-actions>
        </v-card>

        <v-card v-else>
            <v-card-title>Create report</v-card-title>

            <v-card-text class="d-flex justify-center">
                <v-progress-circular
                    indeterminate
                    size="96"
                ></v-progress-circular>
            </v-card-text>
        </v-card>

        <ErrorSnackbarComponent v-model="error"></ErrorSnackbarComponent>
    </v-dialog>
</template>

<script>
import ReportQueriesDialogComponent from "@/extensions/reports/ReportQueriesDialogComponent.vue";
import ErrorSnackbarComponent from "@/ErrorSnackbarComponent.vue";

import validation from "&/mixins/validation";

export default {
    mixins: [validation],
    components: {
        ReportQueriesDialogComponent,
        ErrorSnackbarComponent
    },
    data() {
        return {
            dialog: false,
            ready: false,
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
            titles: [],
            categories: {},
            means: {},

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
                    this.$emit("created");
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
    watch: {
        dialog() {
            if (!this.dialog) return;
            this.ready = false;

            axios
                .get("/web-api/extensions/reports/create")
                .then(response => {
                    const data = response.data;

                    this.titles = data.titles;
                    this.means = data.means;
                    this.categories = data.categories;
                    this.data = _.cloneDeep(this.startData);

                    this.ready = true;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                })
        }
    }
}
</script>
