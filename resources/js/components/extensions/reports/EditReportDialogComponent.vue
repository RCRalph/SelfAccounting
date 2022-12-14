<template>
    <v-dialog v-model="dialog" max-width="800">
        <template v-slot:activator="{ on, attrs }">
            <v-tooltip bottom v-if="icon">
                <template v-slot:activator="{ on: tooltipOn, attrs: tooltipAttrs }">
                    <v-icon class="mx-1 cursor-pointer" v-on="{ ...tooltipOn, ...on }" v-bind="{ ...tooltipAttrs, ...attrs }">mdi-pencil</v-icon>
                </template>

                <span>Edit report</span>
            </v-tooltip>

            <v-btn v-else outlined v-bind="attrs" v-on="on">Edit report</v-btn>
        </template>

        <v-card v-if="ready">
            <v-card-title class="d-flex justify-space-between">
                <div>Edit report</div>

                <ShareReportDialogComponent
                    v-model="data.users"
                ></ShareReportDialogComponent>
            </v-card-title>

            <v-card-text>
                <v-form v-model="canUpdate">
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

                    <div class="d-flex justify-space-around flex-wrap flex-sm-row flex-column mt-2">
                        <ReportQueriesDialogComponent
                            v-model="data.queries"
                            :titles="titles"
                            :categories="categories"
                            :means="means"
                        ></ReportQueriesDialogComponent>

                        <ReportAdditionalEntriesDialogComponent
                            v-model="data.additionalEntries"
                            :titles="titles"
                            :categories="categories"
                            :means="means"
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
                </v-form>
            </v-card-text>

            <v-card-actions class="d-flex justify-space-around px-6">
                <v-btn color="error" outlined @click="reset" :disabled="loading" width="85">
                    Reset
                </v-btn>

                <v-btn color="success" outlined :disabled="!canUpdate || loading" @click="update" :loading="loading" width="85">
                    Update
                </v-btn>
            </v-card-actions>
        </v-card>

        <v-card v-else>
            <v-card-title>Edit report</v-card-title>

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

import validation from "&/mixins/validation";
import main from "&/mixins/main";
import calculator from "&/mixins/calculator";

export default {
    mixins: [main, validation, calculator],
    components: {
        ShareReportDialogComponent,
        ReportQueriesDialogComponent,
        ReportAdditionalEntriesDialogComponent,
        ErrorSnackbarComponent
    },
    props: {
        id: {
            required: true,
            type: Number
        },
        icon: Boolean
    },
    data() {
        return {
            dialog: false,
            ready: false,
            data: {},
            dataCopy: {},
            titles: [],
            categories: {},
            means: {},

            error: false,
            loading: false,
            canUpdate: false
        }
    },
    methods: {
        update() {
            this.loading = true;

            const users = this.data.users.map(item => item.email);
            const data = _.cloneDeep(this.data);
            for (let item of data.queries) {
                item.min_amount = item.min_amount != null ? this.calculate(String(item.min_amount)) : null;
                item.max_amount = item.max_amount != null ? this.calculate(String(item.max_amount)) : null;
                item.min_price = item.min_price != null ? this.calculate(String(item.min_price)) : null;
                item.max_price = item.max_price != null ? this.calculate(String(item.max_price)) : null;
            }

            for (let item of data.additionalEntries) {
                item.amount = this.calculate(String(item.amount));
                item.price = this.calculate(String(item.price));
            }

            axios
                .post(`/web-api/extensions/reports/${this.id}/update`, { ...data, users })
                .then(() => {
                    this.dataCopy = _.cloneDeep(this.data);
                    this.$emit("updated");
                    this.dialog = false;
                    this.loading = false;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.loading = false, 2000);
                })
        },
        reset() {
            this.data = _.cloneDeep(this.dataCopy);
        }
    },
    watch: {
        dialog() {
            if (!this.dialog) return;
            this.ready = false;

            axios
                .get(`/web-api/extensions/reports/${this.id}/edit`)
                .then(response => {
                    const data = response.data;

                    this.titles = data.titles;
                    this.means = data.means;
                    this.categories = data.categories;
                    this.data = data.data;
                    this.dataCopy = _.cloneDeep(data.data);

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
