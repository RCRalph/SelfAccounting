<template>
    <v-dialog v-model="dialog" max-width="1300" :persistent="loading">
        <template v-slot:activator="{ on: dialogOn, attrs: dialogAttrs }">
            <v-tooltip bottom>
                <template v-slot:activator="{ on: tooltipOn, attrs: tooltipAttrs }">
                    <div v-on="tooltipOn" v-bind="tooltipAttrs">
                        <v-btn
                            outlined large width="185" class="ma-1"
                            :block="$vuetify.breakpoint.xs"
                            :disabled="!!tooltip"
                            v-bind="dialogAttrs"
                            v-on="dialogOn"
                        >
                            Restore backup
                        </v-btn>
                    </div>
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

                        <template v-slot:[`item.used_in_income`]="{ item }">
                            <div class="checkbox-centered">
                                <v-checkbox v-model="item.used_in_income" disabled></v-checkbox>
                            </div>
                        </template>

                        <template v-slot:[`item.used_in_expences`]="{ item }">
                            <div class="checkbox-centered">
                                <v-checkbox v-model="item.used_in_expences" disabled></v-checkbox>
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

                    <div class="text-center text-capitalize pb-lg-0 text-h6" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">Accounts</div>
                    <v-data-table
                        :headers="headers.accounts"
                        :items="data.accounts"
                        :mobile-breakpoint="0"
                        :items-per-page="5"
                        disable-sort
                    >
                        <template v-slot:[`item.title`]="{ item }">
                            <span style="white-space: nowrap">{{ item.title }}</span>
                        </template>

                        <template v-slot:[`item.used_in_income`]="{ item }">
                            <div class="checkbox-centered">
                                <v-checkbox v-model="item.used_in_income" disabled></v-checkbox>
                            </div>
                        </template>

                        <template v-slot:[`item.used_in_expences`]="{ item }">
                            <div class="checkbox-centered">
                                <v-checkbox v-model="item.used_in_expences" disabled></v-checkbox>
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

                        <template v-slot:[`item.start_balance`]="{ item }">
                            {{ item.start_balance }}&nbsp;{{ item.currency }}
                        </template>
                    </v-data-table>

                    <div class="text-center text-capitalize pb-lg-0 text-h6" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">Income</div>
                    <v-data-table
                        :headers="headers.incomeExpences"
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

                        <template v-slot:[`item.account_id`]="{ item }">
                            {{ item.account_id ? mappedValues.accounts[item.account_id].name : "N/A" }}
                        </template>
                    </v-data-table>

                    <div class="text-center text-capitalize pb-lg-0 text-h6" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">Expences</div>
                    <v-data-table
                        :headers="headers.incomeExpences"
                        :items="data.expences"
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

                        <template v-slot:[`item.account_id`]="{ item }">
                            {{ item.account_id ? mappedValues.accounts[item.account_id].name : "N/A" }}
                        </template>
                    </v-data-table>

                    <div class="text-center text-capitalize pb-lg-0 text-h6" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">Transfers</div>
                    <v-data-table
                        :headers="headers.transfers"
                        :items="data.transfers"
                        :mobile-breakpoint="0"
                        :items-per-page="5"
                        disable-sort
                    >
                        <template v-slot:[`item.source_value`]="{ item }">
                            {{ item.source_value }}&nbsp;{{ mappedValues.accounts[item.source_account_id].currency }}
                        </template>

                        <template v-slot:[`item.source_account_id`]="{ item }">
                            {{ mappedValues.accounts[item.source_account_id].name }}
                        </template>

                        <template v-slot:[`item.target_value`]="{ item }">
                            {{ item.target_value }}&nbsp;{{ mappedValues.accounts[item.target_account_id].currency }}
                        </template>

                        <template v-slot:[`item.target_account_id`]="{ item }">
                            {{ mappedValues.accounts[item.target_account_id].name }}
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
                                <div class="text-center text-capitalize pb-lg-0 text-h6" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">Accounts</div>

                                <v-data-table
                                    :headers="headers.cashan.accounts"
                                    :items="data.extensions.cashan.accounts"
                                    :mobile-breakpoint="0"
                                    :items-per-page="5"
                                    disable-sort
                                >
                                    <template v-slot:[`item.account_id`]="{ item }">
                                        {{ mappedValues.accounts[item.account_id].name }}
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

                                        <template v-slot:[`item.account_id`]="{ item }">
                                            {{ item.account_id ? mappedValues.accounts[item.account_id].name : "All accounts" }}
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

                                        <template v-slot:[`item.account_id`]="{ item }">
                                            {{ item.account_id ? mappedValues.accounts[item.account_id].name : "N/A" }}
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
                    Your file includes data for extensions that aren't currently enabled, which accounts that their data won't be restored.<br>
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
                    { text: "Show in income", align: "center", value: "used_in_income" },
                    { text: "Show in expences", align: "center", value: "used_in_expences" },
                    { text: "Show on charts", align: "center", value: "show_on_charts" },
                    { text: "Count to summary", align: "center", value: "count_to_summary" },
                    { text: "Start date", align: "center", value: "start_date" },
                    { text: "End date", align: "center", value: "end_date" }
                ],
                accounts: [
                    { text: "Currency", align: "center", value: "currency" },
                    { text: "Name", align: "center", value: "name" },
                    { text: "Show in income", align: "center", value: "used_in_income" },
                    { text: "Show in expences", align: "center", value: "used_in_expences" },
                    { text: "Show on charts", align: "center", value: "show_on_charts" },
                    { text: "Count to summary", align: "center", value: "count_to_summary" },
                    { text: "Start date", align: "center", value: "start_date" },
                    { text: "Start balance", align: "center", value: "start_balance" }
                ],
                incomeExpences: [
                    { text: "Date", align: "center", value: "date" },
                    { text: "Title", align: "center", value: "title" },
                    { text: "Amount", align: "center", value: "amount" },
                    { text: "Price", align: "center", value: "price" },
                    { text: "Category", align: "center", value: "category_id" },
                    { text: "Account", align: "center", value: "account_id" }
                ],
                transfers: [
                    { text: "Date", align: "center", value: "date" },
                    { text: "Source value", align: "center", value: "source_value" },
                    { text: "Source account", align: "center", value: "source_account_id" },
                    { text: "Target value", align: "center", value: "target_value" },
                    { text: "Target account", align: "center", value: "target_account_id" },
                ],
                cashan: {
                    cash: [
                        { text: "Value", align: "center", value: "value" },
                        { text: "Amount", align: "center", value: "amount" },
                    ],
                    accounts: [
                        { text: "Currency", align: "center", value: "currency" },
                        { text: "Account", align: "center", value: "account_id" }
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
                        { text: "Account", align: "center", value: "account_id" },
                        { text: "Query type", align: "center", value: "query_data" }
                    ],
                    additionalEntries: [
                        { text: "Date", align: "center", value: "date" },
                        { text: "Title", align: "center", value: "title" },
                        { text: "Amount", align: "center", value: "amount" },
                        { text: "Price", align: "center", value: "price" },
                        { text: "Category", align: "center", value: "category_id" },
                        { text: "Account", align: "center", value: "account_id" }
                    ]
                }
            },

            file: null,
            data: null,
            mappedValues: {
                categories: {},
                accounts: {}
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
                        accounts: {}
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
                typeof item.used_in_income == "boolean",
                typeof item.used_in_expences == "boolean",
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
        validateAccounts(accounts) {
            if (!Array.isArray(accounts)) {
                return false;
            }

            let validationArray = accounts.map(item => [
                this.currencies.findByISO(item.currency) !== undefined,
                this.validation.name()(item.name) === true,
                typeof item.used_in_income == "boolean",
                typeof item.used_in_expences == "boolean",
                typeof item.count_to_summary == "boolean",
                typeof item.show_on_charts == "boolean",
                this.validation.date(false)(item.start_date) === true,
                this.validation.price(false, true)(item.start_balance) === true
            ].reduce((item1, item2) => item1 && item2));

            if (validationArray.length && !validationArray.reduce((item1, item2) => item1 && item2)) {
                return false;
            }

            accounts.forEach((item, index) => {
                this.mappedValues.accounts[index + 1] = {
                    currency: item.currency,
                    name: item.name,
                    startDate: item.start_date
                };
            });

            return true;
        },
        validateIncomeExpences(data) {
            if (!Array.isArray(data)) {
                return false;
            }

            let validationArray = data.map(item => [
                item.category ? (this.mappedValues.categories[item.category].currency == item.currency) : true,
                item.account ? (this.mappedValues.accounts[item.account].currency == item.currency) : true,
                this.currencies.findByISO(item.currency) !== undefined,
                this.validation.date(false, item.account ? this.mappedValues.accounts[item.account].startDate : null)(item.date),
                this.validation.title()(item.title) === true,
                this.validation.amount()(item.amount) === true,
                this.validation.price()(item.price) === true,
                item.category_id ? (this.mappedValues.categories[item.category_id].currency == item.currency) : true,
                item.account_id ? (this.mappedValues.accounts[item.account_id].currency == item.currency) : true
            ].reduce((item1, item2) => item1 && item2));

            return !validationArray.length || validationArray.reduce((item1, item2) => item1 && item2);
        },
        validateTransfers(data) {
            if (!Array.isArray(data)) {
                return false;
            }

            let validationArray = data.map(item => [
                this.mappedValues.accounts.hasOwnProperty(item.source_account_id),
                this.mappedValues.accounts.hasOwnProperty(item.target_account_id),
                item.source_account_id != item.target_account_id,
                this.validation.date(false,
                    new Date(item.source_account_id).getTime() > new Date(item.target_account_id).getTime() ?
                    this.mappedValues.accounts[item.source_account_id].startDate :
                    this.mappedValues.accounts[item.target_account_id].startDate
                )(item.date),
                this.validation.price()(item.source_value) === true,
                this.validation.price()(item.target_value) === true
            ]).reduce((item1, item2) => item1 && item2);

            return !validationArray.length || validationArray.reduce((item1, item2) => item1 && item2);
        },
        validateCash(cash) {
            if (!Array.isArray(cash.cash) || !Array.isArray(cash.accounts)) {
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

            validationArray = cash.accounts.map(item => [
                this.currencies.findByISO(item.currency) !== undefined,
                item.account_id && this.mappedValues.accounts[item.account_id].currency == item.currency
            ].reduce((item1, item2) => item1 && item2));

            return (!validationArray.length || validationArray.reduce((item1, item2) => item1 && item2)) &&
                this.arrayHasUniqueEntries(cash.accounts, ["currency"]);
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
                    ["income", "expences"].includes(item1.query_data),
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
                    item1.currency ? (!item1.account_id || this.mappedValues.accounts[item1.account_id].currency == item1.currency) : !item1.account_id
                ].reduce((item1, item2) => item1 && item2)),

                Array.isArray(item.additionalEntries) && item.additionalEntries.map(item1 => [
                    this.currencies.findByISO(item1.currency) !== undefined,
                    this.validation.date()(item1.date) === true,
                    this.validation.title()(item1.title) === true,
                    this.validation.amount()(item1.amount) === true,
                    this.validation.price()(item1.price) === true,
                    item1.category_id ? (this.mappedValues.categories[item1.category_id].currency == item1.currency) : true,
                    item1.account_id ? (this.mappedValues.accounts[item1.account_id].currency == item1.currency) : true
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
                accounts: {}
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

                    if (!this.validateAccounts(data.accounts)) {
                        throw new Error("Invalid accounts");
                    }

                    if (!this.validateIncomeExpences(data.income)) {
                        throw new Error("Invalid income");
                    }

                    if (!this.validateIncomeExpences(data.expences)) {
                        throw new Error("Invalid expences");
                    }

                    if (!this.validateTransfers(data.transfers)) {
                        throw new Error("Invalid transfers");
                    }

                    if (_.isPlainObject(data.extensions)) {
                        Object.keys(data.extensions).forEach(item => {
                            if (this.extensions.ownedExtensions.includes(item)) {
                                if (item == "cashan" && !this.validateCash(data.extensions.cashan)) {
                                    throw new Error("Invalid cash");
                                }

                                if (item == "report") {
                                    if (!this.validateReports(data.extensions.report)) {
                                        throw new Error("Invalid reports");
                                    }

                                    data.extensions.report.reports.forEach((item, i) => {
                                        data.extensions.report.reports[i].id = i;
                                    })
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
