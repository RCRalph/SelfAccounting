<template>
    <v-dialog v-model="dialog" max-width="800">
        <template v-slot:activator="{ on, attrs }">
            <v-btn outlined v-bind="attrs" v-on="on">Create report</v-btn>
        </template>

        <v-card v-if="ready">
            <v-card-title class="d-flex justify-space-between">
                <div>Create report</div>

                <ShareReportDialogComponent
                    v-model="data.users"
                ></ShareReportDialogComponent>
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
                                    Add&nbsp;income, subtract&nbsp;expences
                                </template>
                            </v-switch>
                        </v-col>

                        <v-col cols="12" sm="6" class="d-flex justify-sm-center justify-start">
                            <v-switch :color="$vuetify.theme.dark ? 'white' : 'grey'" :false-value="true" :true-value="false" v-model="data.income_addition">
                                <template v-slot:label>
                                    Add&nbsp;expences, subtract&nbsp;income
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

                    <div class="d-flex justify-space-around flex-wrap flex-sm-row flex-column mt-2">
                        <ReportQueriesDialogComponent
                            v-model="data.queries"
                            :titles="titles"
                            :categories="categories"
                            :accounts="accounts"
                        ></ReportQueriesDialogComponent>

                        <ReportAdditionalEntriesDialogComponent
                            v-model="data.additionalEntries"
                            :titles="titles"
                            :categories="categories"
                            :accounts="accounts"
                        ></ReportAdditionalEntriesDialogComponent>
                    </div>

                    <v-divider class="mt-3"></v-divider>

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
                                    <th class="text-center">Account</th>
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
                                            <v-checkbox :color="$vuetify.theme.dark ? 'white' : 'grey'"  v-model="data.columns.account_id"></v-checkbox>
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
import ShareReportDialogComponent from "@/extensions/reports/ShareReportDialogComponent.vue";
import ReportQueriesDialogComponent from "@/extensions/reports/ReportQueriesDialogComponent.vue";
import ReportAdditionalEntriesDialogComponent from "@/extensions/reports/ReportAdditionalEntriesDialogComponent.vue";
import ErrorSnackbarComponent from "@/ErrorSnackbarComponent.vue";

import Calculator from "&/classes/Calculator";
import validation from "&/mixins/validation";
import main from "&/mixins/main";

export default {
    mixins: [main, validation],
    components: {
        ShareReportDialogComponent,
        ReportQueriesDialogComponent,
        ReportAdditionalEntriesDialogComponent,
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
                    account_id: true
                },
                queries: [],
                additionalEntries: [],
                users: []
            },
            data: {},
            titles: [],
            categories: {},
            accounts: {},

            error: false,
            loading: false,
            canSubmit: false
        }
    },
    methods: {
        submit() {
            this.loading = true;

            const users = this.data.users.map(item => item.email);
            const data = _.cloneDeep(this.data);
            for (let item of data.queries) {
                item.min_amount = item.min_amount === null ? null : new Calculator(item.min_amount, Calculator.FIELDS.amount).resultValue;
                item.max_amount = item.max_amount === null ? null : new Calculator(item.max_amount, Calculator.FIELDS.amount).resultValue;
                item.min_price = item.min_price === null ? null : new Calculator(item.min_price, Calculator.FIELDS.price).resultValue;
                item.max_price = item.max_price === null ? null : new Calculator(item.max_price, Calculator.FIELDS.price).resultValue;
            }

            for (let item of data.additionalEntries) {
                item.amount = new Calculator(item.amount, Calculator.FIELDS.amount).resultValue;
                item.price = new Calculator(item.price, Calculator.FIELDS.price).resultValue;
            }

            axios
                .post("/web-api/extensions/reports/create", { ...data, users })
                .then(response => {
                    this.$router.push(`/extensions/reports/${response.data.id}`);
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
                    this.accounts = data.accounts;
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
