<template>
    <v-dialog v-model="dialog" max-width="1300" :persistent="loading">
        <template v-slot:activator="{ on: dialogOn, attrs: dialogAttrs }">
            <v-tooltip bottom :disabled="!tooltip">
                <template v-slot:activator="{ on: tooltipOn, attrs: tooltipAttrs }">
                    <v-btn
                        outlined large width="185"
                        v-bind="{ ...dialogAttrs, ...tooltipAttrs }"
                        v-on="{ ...dialogOn, ...tooltipOn }"
                    >
                        Restore backup
                    </v-btn>
                </template>

                <span>{{ tooltip }}</span>
            </v-tooltip>
        </template>

        <v-card>
            <v-card-title class="justify-center text-capitalize pb-lg-0 text-h5">Restore backup</v-card-title>

            <v-card-text>
                <v-row>
                    <v-col cols="12" md="8" offset-md="2" lg="6" offset-lg="3" offset-xl="4" xl="4">
                        <v-file-input
                            v-model="file"
                            accept=".selfacc2"
                            show-size
                            placeholder="Choose backup file"
                            :disabled="loading"
                        ></v-file-input>
                    </v-col>
                </v-row>

                <div v-if="data">
                    <div class="text-center text-capitalize pb-lg-0 text-h6" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">Categories</div>
                    <v-data-table
                        :headers="headers.categories"
                        :items="data.categories"
                        :mobile-breakpoint="0"
                        :items-per-page="5"
                        disable-sort
                    >
                        <template v-slot:[`item.title`]="{ item }">
                            <span style="white-space: nowrap">{{ item.title }}</span>
                        </template>

                        <template v-slot:[`item.income_category`]="{ item }">
                            <div class="checkbox-centered">
                                <v-checkbox v-model="item.income_category" disabled></v-checkbox>
                            </div>
                        </template>

                        <template v-slot:[`item.outcome_category`]="{ item }">
                            <div class="checkbox-centered">
                                <v-checkbox v-model="item.outcome_category" disabled></v-checkbox>
                            </div>
                        </template>

                        <template v-slot:[`item.show_on_charts`]="{ item }">
                            <div class="checkbox-centered">
                                <v-checkbox v-model="item.show_on_charts" disabled></v-checkbox>
                            </div>
                        </template>

                        <template v-slot:[`item.count_to_summary`]="{ item }">
                            <div class="checkbox-centered">
                                <v-checkbox v-model="item.count_to_summary" disabled></v-checkbox>
                            </div>
                        </template>

                        <template v-slot:[`item.start_date`]="{ item }">
                            {{ item.start_date || "N/A" }}
                        </template>

                        <template v-slot:[`item.end_date`]="{ item }">
                            {{ item.end_date || "N/A" }}
                        </template>
                    </v-data-table>

                    <div class="text-center text-capitalize pb-lg-0 text-h6" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">Means of payment</div>
                    <v-data-table
                        :headers="headers.means"
                        :items="data.means"
                        :mobile-breakpoint="0"
                        :items-per-page="5"
                        disable-sort
                    >
                        <template v-slot:[`item.title`]="{ item }">
                            <span style="white-space: nowrap">{{ item.title }}</span>
                        </template>

                        <template v-slot:[`item.income_mean`]="{ item }">
                            <div class="checkbox-centered">
                                <v-checkbox v-model="item.income_mean" disabled></v-checkbox>
                            </div>
                        </template>

                        <template v-slot:[`item.outcome_mean`]="{ item }">
                            <div class="checkbox-centered">
                                <v-checkbox v-model="item.outcome_mean" disabled></v-checkbox>
                            </div>
                        </template>

                        <template v-slot:[`item.show_on_charts`]="{ item }">
                            <div class="checkbox-centered">
                                <v-checkbox v-model="item.show_on_charts" disabled></v-checkbox>
                            </div>
                        </template>

                        <template v-slot:[`item.count_to_summary`]="{ item }">
                            <div class="checkbox-centered">
                                <v-checkbox v-model="item.count_to_summary" disabled></v-checkbox>
                            </div>
                        </template>

                        <template v-slot:[`item.first_entry_amount`]="{ item }">
                            {{ item.first_entry_amount }}&nbsp;{{ item.currency }}
                        </template>
                    </v-data-table>

                    <div class="text-center text-capitalize pb-lg-0 text-h6" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">Income</div>
                    <v-data-table
                        :headers="headers.IO"
                        :items="data.income"
                        :mobile-breakpoint="0"
                        :items-per-page="5"
                        disable-sort
                    >
                        <template v-slot:[`item.title`]="{ item }">
                            <span style="white-space: nowrap">{{ item.title }}</span>
                        </template>

                        <template v-slot:[`item.price`]="{ item }">
                            {{ item.price }}&nbsp;{{ item.currency }}
                        </template>

                        <template v-slot:[`item.category_id`]="{ item }">
                            {{ item.category_id ? mappedValues.categories[item.category_id].name : "N/A" }}
                        </template>

                        <template v-slot:[`item.mean_id`]="{ item }">
                            {{ item.mean_id ? mappedValues.means[item.mean_id].name : "N/A" }}
                        </template>
                    </v-data-table>

                    <div class="text-center text-capitalize pb-lg-0 text-h6" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">Outcome</div>
                    <v-data-table
                        :headers="headers.IO"
                        :items="data.outcome"
                        :mobile-breakpoint="0"
                        :items-per-page="5"
                        disable-sort
                    >
                        <template v-slot:[`item.title`]="{ item }">
                            <span style="white-space: nowrap">{{ item.title }}</span>
                        </template>

                        <template v-slot:[`item.price`]="{ item }">
                            {{ item.price }}&nbsp;{{ item.currency }}
                        </template>

                        <template v-slot:[`item.category_id`]="{ item }">
                            {{ item.category_id ? mappedValues.categories[item.category_id].name : "N/A" }}
                        </template>

                        <template v-slot:[`item.mean_id`]="{ item }">
                            {{ item.mean_id ? mappedValues.means[item.mean_id].name : "N/A" }}
                        </template>
                    </v-data-table>

                    <div v-if="data.extensions.cashan && !disabledExtensions.includes('cashan')">
                        <v-row>
                            <v-col cols="12" md="6">
                                <div class="text-center text-capitalize pb-lg-0 text-h6" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">Cash</div>

                                <v-data-table
                                    :headers="headers.cashan.cash"
                                    :items="data.extensions.cashan.cash"
                                    :mobile-breakpoint="0"
                                    :items-per-page="5"
                                    disable-sort
                                >
                                    <template v-slot:[`item.value`]="{ item }">
                                        {{ item.value }}&nbsp;{{ item.currency }}
                                    </template>
                                </v-data-table>
                            </v-col>

                            <v-col cols="12" md="6">
                                <div class="text-center text-capitalize pb-lg-0 text-h6" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">Means of payment</div>

                                <v-data-table
                                    :headers="headers.cashan.means"
                                    :items="data.extensions.cashan.means"
                                    :mobile-breakpoint="0"
                                    :items-per-page="5"
                                    disable-sort
                                >
                                    <template v-slot:[`item.mean_id`]="{ item }">
                                        {{ mappedValues.means[item.mean_id].name }}
                                    </template>
                                </v-data-table>
                            </v-col>
                        </v-row>
                    </div>

                    <div v-if="data.extensions.report && !disabledExtensions.includes('report')">
                        <div class="text-center text-capitalize pb-lg-0 text-h6" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">Reports</div>

                        <v-data-table
                            :headers="headers.report.reports"
                            :items="data.extensions.report.reports"
                            :mobile-breakpoint="0"
                            :items-per-page="5"
                            disable-sort
                            show-expand
                            single-expand
                        >
                            <template v-slot:[`item.income_addition`]="{ item }">
                                <div class="checkbox-centered">
                                    <v-checkbox v-model="item.income_addition" disabled></v-checkbox>
                                </div>
                            </template>

                            <template v-slot:[`item.sort_dates_desc`]="{ item }">
                                <div class="checkbox-centered">
                                    <v-checkbox v-model="item.sort_dates_desc" disabled></v-checkbox>
                                </div>
                            </template>

                            <template v-slot:[`item.calculate_sum`]="{ item }">
                                <div class="checkbox-centered">
                                    <v-checkbox v-model="item.calculate_sum" disabled></v-checkbox>
                                </div>
                            </template>

                            <template v-slot:expanded-item="{ headers: head, item }">
                                <td :colspan="head.length" class="py-2">
                                    <div class="text-center text-capitalize pb-lg-0 text-h6" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">Queries</div>
                                    <v-data-table
                                        :headers="headers.report.queries"
                                        :items="item.queries"
                                        :mobile-breakpoint="0"
                                        :items-per-page="5"
                                        disable-sort
                                    >
                                        <template v-slot:[`item.query_data`]="{ item }">
                                            <span class="text-capitalize">{{ item.query_data }}</span>
                                        </template>

                                        <template v-slot:[`item.min_date`]="{ item }">
                                            {{ item.min_date || "N/A" }}
                                        </template>

                                        <template v-slot:[`item.max_date`]="{ item }">
                                            {{ item.max_date || "N/A" }}
                                        </template>

                                        <template v-slot:[`item.title`]="{ item }">
                                            <span style="white-space: nowrap">{{ item.title || "All titles" }}</span>
                                        </template>

                                        <template v-slot:[`item.min_amount`]="{ item }">
                                            {{ item.min_amount || "N/A" }}
                                        </template>

                                        <template v-slot:[`item.max_amount`]="{ item }">
                                            {{ item.max_amount || "N/A" }}
                                        </template>

                                        <template v-slot:[`item.min_price`]="{ item }">
                                            {{ item.min_price || "N/A" }}&nbsp;{{ item.min_price && item.currency || "" }}
                                        </template>

                                        <template v-slot:[`item.max_price`]="{ item }">
                                            {{ item.max_price || "N/A" }}&nbsp;{{ item.max_price && item.currency || "" }}
                                        </template>

                                        <template v-slot:[`item.currency`]="{ item }">
                                            {{ item.currency || "All currencies" }}
                                        </template>

                                        <template v-slot:[`item.category_id`]="{ item }">
                                            {{ item.category_id ? mappedValues.categories[item.category_id].name : "All categories" }}
                                        </template>

                                        <template v-slot:[`item.mean_id`]="{ item }">
                                            {{ item.mean_id ? mappedValues.means[item.mean_id].name : "All means of payment" }}
                                        </template>
                                    </v-data-table>

                                    <div class="text-center text-capitalize pb-lg-0 text-h6" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">Additional Entries</div>
                                    <v-data-table
                                        :headers="headers.report.additionalEntries"
                                        :items="item.additionalEntries"
                                        :mobile-breakpoint="0"
                                        :items-per-page="5"
                                        disable-sort
                                    >
                                        <template v-slot:[`item.title`]="{ item }">
                                            <span style="white-space: nowrap">{{ item.title }}</span>
                                        </template>

                                        <template v-slot:[`item.price`]="{ item }">
                                            {{ item.price }}&nbsp;{{ item.currency }}
                                        </template>

                                        <template v-slot:[`item.category_id`]="{ item }">
                                            {{ item.category_id ? mappedValues.categories[item.category_id].name : "N/A" }}
                                        </template>

                                        <template v-slot:[`item.mean_id`]="{ item }">
                                            {{ item.mean_id ? mappedValues.means[item.mean_id].name : "N/A" }}
                                        </template>
                                    </v-data-table>

                                    <div class="text-center text-capitalize pb-1 text-h6" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">Users</div>

                                    <div class="d-flex justify-center flex-wrap">
                                        <v-chip
                                            v-for="(item1, i) in item.users"
                                            :key="i" pill outlined
                                        >
                                            {{ item1 }}
                                        </v-chip>
                                    </div>
                                </td>
                            </template>
                        </v-data-table>
                    </div>
                </div>

                <div class="text-center error--text" v-if="disabledExtensionNames.length">
                    Your file includes data for extensions that aren't currently enabled, which means that their data won't be restored.<br>
                    Please enable the following extensions: <strong>{{ disabledExtensionNames }}</strong>
                </div>
            </v-card-text>

            <v-card-actions v-if="data" class="d-flex justify-center">
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
import { useExtensionsStore } from "&/stores/extensions";
import validation from "&/mixins/validation";
import main from "&/mixins/main";

export default {
    setup() {
        const currencies = useCurrenciesStore();
        const extensions = useExtensionsStore();

        return { currencies, extensions };
    },
    mixins: [validation, main],
    components: {
        ErrorSnackbarComponent
    },
    props: {
        tooltip: {
            required: true,
            type: [Boolean, String]
        }
    },
    data() {
        return {
            headers: {
                categories: [
                    { text: "Currency", align: "center", value: "currency" },
                    { text: "Name", align: "center", value: "name" },
                    { text: "Show in income", align: "center", value: "income_category" },
                    { text: "Show in outcome", align: "center", value: "outcome_category" },
                    { text: "Show on charts", align: "center", value: "show_on_charts" },
                    { text: "Count to summary", align: "center", value: "count_to_summary" },
                    { text: "Start date", align: "center", value: "start_date" },
                    { text: "End date", align: "center", value: "end_date" }
                ],
                means: [
                    { text: "Currency", align: "center", value: "currency" },
                    { text: "Name", align: "center", value: "name" },
                    { text: "Show in income", align: "center", value: "income_mean" },
                    { text: "Show in outcome", align: "center", value: "outcome_mean" },
                    { text: "Show on charts", align: "center", value: "show_on_charts" },
                    { text: "Count to summary", align: "center", value: "count_to_summary" },
                    { text: "First entry date", align: "center", value: "first_entry_date" },
                    { text: "Start balance", align: "center", value: "first_entry_amount" }
                ],
                IO: [
                    { text: "Date", align: "center", value: "date" },
                    { text: "Title", align: "center", value: "title" },
                    { text: "Amount", align: "center", value: "amount" },
                    { text: "Price", align: "center", value: "price" },
                    { text: "Category", align: "center", value: "category_id" },
                    { text: "Mean of payment", align: "center", value: "mean_id" }
                ],
                cashan: {
                    cash: [
                        { text: "Value", align: "center", value: "value" },
                        { text: "Amount", align: "center", value: "amount" },
                    ],
                    means: [
                        { text: "Currency", align: "center", value: "currency" },
                        { text: "Mean of payment", align: "center", value: "mean_id" }
                    ]
                },
                report: {
                    reports: [
                        { text: "Title", align: "center", value: "title" },
                        { text: "Show sum", align: "center", value: "calculate_sum" },
                        { text: "Add income", align: "center", value: "income_addition" },
                        { text: "Sort dates desc", align: "center", value: "sort_dates_desc" },
                        { text: "Show columns", align: "center", value: "show_columns" },
                        { text: "", align: "center", value: "data-table-expand" }
                    ],
                    queries: [
                        { text: "Min date", align: "center", value: "min_date" },
                        { text: "Max date", align: "center", value: "max_date" },
                        { text: "Title", align: "center", value: "title" },
                        { text: "Min amount", align: "center", value: "min_amount" },
                        { text: "Max amount", align: "center", value: "max_amount" },
                        { text: "Min price", align: "center", value: "min_price" },
                        { text: "Max price", align: "center", value: "max_price" },
                        { text: "Currency", align: "center", value: "currency" },
                        { text: "Category", align: "center", value: "category_id" },
                        { text: "Mean of payment", align: "center", value: "mean_id" },
                        { text: "Query type", align: "center", value: "query_data" }
                    ],
                    additionalEntries: [
                        { text: "Date", align: "center", value: "date" },
                        { text: "Title", align: "center", value: "title" },
                        { text: "Amount", align: "center", value: "amount" },
                        { text: "Price", align: "center", value: "price" },
                        { text: "Category", align: "center", value: "category_id" },
                        { text: "Mean of payment", align: "center", value: "mean_id" }
                    ]
                }
            },

            file: null,
            data: null,
            mappedValues: {
                categories: {},
                means: {}
            },
            disabledExtensions: [],

            dialog: false,
            error: false,
            loading: false
        }
    },
    computed: {
        disabledExtensionNames() {
            return this.extensions.extensions
                .filter(item => this.disabledExtensions.includes(item.code))
                .map(item => item.title).join(", ");
        }
    },
    methods: {
        submit() {
            this.loading = true;

            axios
                .post(`/web-api/extensions/backup/restore`, this.data)
                .then(() => {
                    this.$emit("restored");
                    this.dialog = false;
                    this.data = null;
                    this.file = null;
                    this.mappedValues = {
                        categories: {},
                        means: {}
                    };
                    this.disabledExtensions = [];
                })
                .catch(err => {
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.loading = false, 2000);
                });
        },
        validateCategories(categories) {
            if (!Array.isArray(categories)) {
                return false;
            }

            let validationArray = categories.map(item => [
                this.currencies.findByISO(item.currency) !== undefined,
                this.validation.name()(item.name) === true,
                typeof item.income_category == "boolean",
                typeof item.outcome_category == "boolean",
                typeof item.count_to_summary == "boolean",
                typeof item.show_on_charts == "boolean",
                this.validation.date(true)(item.start_date) === true,
                this.validation.date(true, item.start_date)(item.end_date) === true
            ].reduce((item1, item2) => item1 && item2));

            if (validationArray.length && !validationArray.reduce((item1, item2) => item1 && item2)) {
                return false;
            }

            categories.forEach((item, index) => {
                this.mappedValues.categories[index + 1] = {
                    currency: item.currency,
                    name: item.name
                };
            });

            return true;
        },
        validateMeans(means) {
            if (!Array.isArray(means)) {
                return false;
            }

            let validationArray = means.map(item => [
                this.currencies.findByISO(item.currency) !== undefined,
                this.validation.name()(item.name) === true,
                typeof item.income_mean == "boolean",
                typeof item.outcome_mean == "boolean",
                typeof item.count_to_summary == "boolean",
                typeof item.show_on_charts == "boolean",
                this.validation.date(false)(item.first_entry_date) === true,
                this.validation.price(false, true)(item.first_entry_amount) === true
            ].reduce((item1, item2) => item1 && item2));

            if (validationArray.length && !validationArray.reduce((item1, item2) => item1 && item2)) {
                return false;
            }

            means.forEach((item, index) => {
                this.mappedValues.means[index + 1] = {
                    currency: item.currency,
                    name: item.name,
                    firstEntry: item.first_entry_date
                };
            });

            return true;
        },
        validateIO(data) {
            if (!Array.isArray(data)) {
                return false;
            }

            let validationArray = data.map(item => [
                item.category ? (this.mappedValues.categories[item.category].currency == item.currency) : true,
                item.mean ? (this.mappedValues.means[item.mean].currency == item.currency) : true,
                this.currencies.findByISO(item.currency) !== undefined,
                this.validation.date(false, item.mean ? this.mappedValues.means[item.mean].firstEntry : null)(item.date),
                this.validation.title()(item.title) === true,
                this.validation.amount()(item.amount) === true,
                this.validation.price()(item.price) === true,
                item.category_id ? (this.mappedValues.categories[item.category_id].currency == item.currency) : true,
                item.mean_id ? (this.mappedValues.means[item.mean_id].currency == item.currency) : true
            ].reduce((item1, item2) => item1 && item2));

            return !validationArray.length || validationArray.reduce((item1, item2) => item1 && item2);
        },
        validateCash(cash) {
            if (!Array.isArray(cash.cash) || !Array.isArray(cash.means)) {
                return false;
            }

            let validationArray = cash.cash.map(item => [
                this.currencies.findByISO(item.currency) !== undefined,
                this.validation.cash()(item.amount) === true,
                item.amount > 0,
                !isNaN(Number(item.value))
            ].reduce((item1, item2) => item1 && item2));

            if (
                validationArray.length && !validationArray.reduce((item1, item2) => item1 && item2) ||
                !this.arrayHasUniqueEntries(cash.cash, ["currency", "value"])
            ) { return false }

            validationArray = cash.means.map(item => [
                this.currencies.findByISO(item.currency) !== undefined,
                item.mean_id && this.mappedValues.means[item.mean_id].currency == item.currency
            ].reduce((item1, item2) => item1 && item2));

            return (!validationArray.length || validationArray.reduce((item1, item2) => item1 && item2)) &&
                this.arrayHasUniqueEntries(cash.means, ["currency"]);
        },
        validateReports(reports) {
            if (!Array.isArray(reports.reports)) {
                return false;
            }

            let validationArray = reports.reports.map(item => [
                this.validation.title()(item.title),
                typeof item.income_addition == "boolean",
                typeof item.sort_dates_desc == "boolean",
                typeof item.calculate_sum == "boolean",
                Number.isInteger(item.show_columns) && item.show_columns >= 0 && item.show_columns <= 127,

                Array.isArray(item.queries) && item.queries.map(item1 => [
                    ["income", "outcome"].includes(item1.query_data),
                    this.validation.date(true)(item1.min_date) === true,
                    this.validation.date(true, item1.min_date)(item1.max_date) === true,
                    this.validation.title(true)(item1.title) === true,
                    this.validation.amount(true, true)(item1.min_amount) === true,
                    this.validation.amount(true, true)(item1.max_amount) === true,
                    (isNaN(Number(item1.min_amount)) || isNaN(Number(item1.max_amount))) ? true :
                        Number(item1.min_amount) <= Number(item1.max_amount),
                    this.validation.price(true, true)(item1.min_price) === true,
                    this.validation.price(true, true)(item1.max_price) === true,
                    (isNaN(Number(item1.min_price)) || isNaN(Number(item1.max_price))) ? true :
                        Number(item1.min_price) <= Number(item1.max_price),
                    item1.currency == null || this.currencies.findByISO(item1.currency) !== undefined,
                    item1.currency ? (!item1.category_id || this.mappedValues.categories[item1.category_id].currency == item1.currency) : !item1.category_id,
                    item1.currency ? (!item1.mean_id || this.mappedValues.means[item1.mean_id].currency == item1.currency) : !item1.mean_id
                ].reduce((item1, item2) => item1 && item2)),

                Array.isArray(item.additionalEntries) && item.additionalEntries.map(item1 => [
                    this.currencies.findByISO(item1.currency) !== undefined,
                    this.validation.date()(item1.date) === true,
                    this.validation.title()(item1.title) === true,
                    this.validation.amount()(item1.amount) === true,
                    this.validation.price()(item1.price) === true,
                    item1.category_id ? (this.mappedValues.categories[item1.category_id].currency == item1.currency) : true,
                    item1.mean_id ? (this.mappedValues.means[item1.mean_id].currency == item1.currency) : true
                ].reduce((item2, item3) => item2 && item3)),

                Array.isArray(item.users) &&
                    item.users.map(item1 => this.validation.email()(item1)) &&
                    _.uniq(item.users).length == item.users.length
            ]).map(item => {
                item.forEach((item1, i) => {
                    if (Array.isArray(item1)) {
                        item[i] = item1.length ? item1.reduce((item2, item3) => item2 && item3) : true;
                    }
                })

                return item;
            });

            return !validationArray.length || validationArray.reduce((item1, item2) => item1 && item2);
        }
    },
    watch: {
        file() {
            this.data = null;
            this.mappedValues = {
                categories: {},
                means: {}
            };
            this.disabledExtensions = [];

            if (!this.file) {
                return;
            }

            this.file.text()
                .then(data => JSON.parse(data))
                .then(data => {
                    if (!_.isPlainObject(data)) {
                        throw new Error("Invalid data wrapper (wrapper not an object)");
                    }

                    if (!this.validateCategories(data.categories)) {
                        throw new Error("Invalid categories");
                    }

                    if (!this.validateMeans(data.means)) {
                        throw new Error("Invalid means");
                    }

                    if (!this.validateIO(data.income)) {
                        throw new Error("Invalid income");
                    }

                    if (!this.validateIO(data.outcome)) {
                        throw new Error("Invalid outcome");
                    }

                    if (_.isPlainObject(data.extensions)) {
                        Object.keys(data.extensions).forEach(item => {
                            if (this.extensions.ownedExtensions.includes(item)) {
                                if (item == "cashan" && !this.validateCash(data.extensions.cashan)) {
                                    throw new Error("Invalid cash");
                                }

                                if (item == "report" && !this.validateReports(data.extensions.report)) {
                                    throw new Error("Invalid reports");
                                }
                            }
                            else if (this.extensions.extensions.map(item1 => item1.code).includes(item)) {
                                this.disabledExtensions.push(item);
                            }
                        });
                    }

                    this.data = data;
                })
                .catch(err => {
                    console.error(err);
                    this.file = null;
                    this.error = true;
                });
        }
    }
}
</script>
