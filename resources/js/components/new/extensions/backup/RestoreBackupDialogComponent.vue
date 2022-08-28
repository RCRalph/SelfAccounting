<template>
    <v-dialog v-model="dialog" max-width="900">
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
                    <v-col cols="12" md="10" offset-md="1" lg="8" offset-lg="2">
                        <v-file-input
                            v-model="file"
                            accept=".selfacc2"
                            show-size
                            placeholder="Choose backup file"
                        ></v-file-input>
                    </v-col>
                </v-row>

            </v-card-text>

            <v-card-actions v-if="file">

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
            file: null,
            data: null,
            mappedValues: {
                categories: {},
                means: {}
            },
            notOwnedExtensionNames: [],

            dialog: false,
            error: false,
        }
    },
    methods: {
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
                    item1.currency ? (this.mappedValues.categories[item1.category_id].currency == item1.currency) : !item1.category_id,
                    item1.currency ? (this.mappedValues.means[item1.mean_id].currency == item1.currency) : !item1.mean_id
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
            this.notOwnedExtensionNames = [];

            if (!this.file) {
                return null;
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
                                this.notOwnedExtensionNames.push(this.extensions.extensions.find(item1 => item1.code == item).title)
                            }
                        });
                    }

                    this.data = data;
                })
                .catch(err => {
                    console.error(err);
                    this.error = true;
                });
        }
    }
}
</script>
