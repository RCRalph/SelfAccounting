<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-newspaper"></i>
                Edit report
            </div>

            <div class="d-flex" v-if="ready">
                <button class="big-button-primary" @click="showReport" :disabled="submitted || destroyed || showReportClicked || !canSubmit">
                    <div v-if="!showReportClicked">
                        Show report
                    </div>

                    <span
                        v-else
                        class="spinner-border spinner-border-sm"
                        role="status"
                        aria-hidden="true"
                    ></span>
                </button>
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <ReportDataComponent
                    v-model="data"
                ></ReportDataComponent>

                <hr class="hr">

                <ReportColumnsComponent
                    v-model="columns"
                ></ReportColumnsComponent>

                <hr class="hr">

                <ReportQueriesComponent
                    v-model="queries"
                    :queryTypes="queryTypes"
                    :titles="titles"
                    :currencies="currencies"
                    :categories="categories"
                    :means="means"
                    @add="addQuery"
                    @remove="removeQuery"
                ></ReportQueriesComponent>

                <hr class="hr">

                <ReportAdditionalEntries
                    :titles="titles"
                    :currencies="currencies"
                    :categories="categories"
                    :means="means"
                    v-model="additionalEntries"
                    @add="addEntry"
                    @remove="removeEntry"
                ></ReportAdditionalEntries>

                <hr class="hr">

                <ReportUsersSharedComponent
                    v-model="users"
                ></ReportUsersSharedComponent>

                <hr class="hr">

                <DeleteSaveResetChanges
                    :disableAll="destroyed || submitted"
                    :disableSave="!canSubmit"
                    :spinnerDelete="destroyed"
                    :spinnerSave="submitted"
                    deleteText="report"
                    @delete="destroy"
                    @save="submit"
                    @reset="reset"
                ></DeleteSaveResetChanges>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import ReportDataComponent from "./ReportDataComponent.vue";
import ReportQueriesComponent from "./ReportQueriesComponent.vue";
import ReportAdditionalEntries from "./ReportAdditionalEntriesComponent.vue";
import ReportUsersSharedComponent from "./ReportUsersSharedComponent.vue";
import ReportColumnsComponent from "./ReportColumnsComponent.vue";
import Loading from "../../../components/Loading.vue";
import EmptyPlaceholder from "../../../components/EmptyPlaceholder.vue";
import DeleteSaveResetChanges from "../../../components/DeleteSaveResetChanges.vue";

export default {
    props: ["id"],
    components: {
        Loading,
        EmptyPlaceholder,
        DeleteSaveResetChanges,
        ReportDataComponent,
        ReportQueriesComponent,
        ReportAdditionalEntries,
        ReportUsersSharedComponent,
        ReportColumnsComponent
    },
    data() {
        return {
            ready: false,
            currencies: {},
            categories: {},
            means: {},
            titles: [],
            queryTypes: [],
            columns: {},
            lastCurrency: 1,
            submitted: false,
            destroyed: false,
            showReportClicked: false,
            copy: {},

            queries: [],
            additionalEntries: [],
            users: [],
            data: {
                title: "",
                income_addition: true,
                calculate_sum: false,
                sort_dates_desc: false
            }
        }
    },
    computed: {
        validReportData() {
            return this.data.title && this.data.title.length <= 64;
        },
        validQueries() {
            const validArray = this.queries.map(item =>
                [
                    this.isNullLike(item.min_date) ||
                    !isNaN(Date.parse(item.min_date)) &&
                    (
                        !item.max_date ||
                        new Date(item.min_date).getTime() <= new Date(item.max_date).getTime()
                    ),

                    this.isNullLike(item.max_date) ||
                    !isNaN(Date.parse(item.max_date)) &&
                    (
                        !item.min_date ||
                        new Date(item.max_date).getTime() >= new Date(item.min_date).getTime()
                    ),

                    this.isNullLike(item.min_amount) ||
                    !isNaN(Number(item.min_amount)) &&
                    (
                        !item.max_amount ||
                        item.min_price <= item.max_price
                    ) && item.min_amount <= 1e7 - 0.001 && item.min_amount >= 0,

                    this.isNullLike(item.max_amount) ||
                    !isNaN(Number(item.max_amount)) &&
                    (
                        !item.min_amount ||
                        item.min_price <= item.max_price
                    ) && item.max_amount <= 1e7 - 0.001 && item.max_amount >= 0,

                    this.isNullLike(item.min_price) ||
                    !isNaN(Number(item.min_price)) &&
                    (
                        !item.max_price ||
                        item.min_price <= item.max_price
                    ) && item.min_price <= 1e11 - 0.01 && item.min_price >= 0,

                    this.isNullLike(item.max_price) ||
                    !isNaN(Number(item.max_price)) &&
                    (
                        !item.min_price ||
                        item.min_price <= item.max_price
                    ) && item.max_price <= 1e11 - 0.01 && item.max_price >= 0,
                ].reduce((item1, item2) => item1 && item2)
            );

            return validArray.length ? validArray.reduce((item1, item2) => item1 && item2) : true;
        },
        validAdditionalEntries() {
            const validArray = this.additionalEntries.map(item =>
                [
                    item.date !== "" &&
                    !isNaN(Date.parse(item.date)),

                    item.title &&
                    item.title.length <= 64,

                    !isNaN(Number(item.amount)) &&
                    item.amount <= 1e7 - 0.001 &&
                    item.amount > 0,

                    !isNaN(Number(item.price)) &&
                    Math.abs(item.price) <= 1e11 - 0.01 &&
                    item.price != 0
                ].reduce((item1, item2) => item1 && item2)
            );

            return validArray.length ? validArray.reduce((item1, item2) => item1 && item2) : true;
        },
        validUsers() {
            const emails = this.users.map(item => item.email);

            emails.forEach((item, i) => {
                if (emails.indexOf(item) != i) {
                    return false;
                }
            });

            return true;
        },
        canSubmit() {
            return this.validReportData &&
                this.validQueries &&
                this.validAdditionalEntries &&
                this.validUsers &&
                (this.queries.length || this.additionalEntries.length);
        }
    },
    methods: {
        addQuery() {
            this.queries.push({
                id: null,
                query_data: this.queryTypes[0],
                min_date: null,
                max_date: null,
                title: null,
                min_amount: null,
                max_amount: null,
                min_price: null,
                max_price: null,
                currency_id: null,
                category_id: null,
                mean_id: null
            })
        },
        removeQuery(i) {
            this.queries.splice(i, 1);
        },
        addEntry() {
            this.additionalEntries.push({
                id: null,
                date: null,
                title: null,
                amount: null,
                price: null,
                currency_id: this.lastCurrency,
                category_id: null,
                mean_id: null
            });
        },
        removeEntry(i) {
            this.additionalEntries.splice(i, 1);
        },
        submit() {
            this.submitted = true;

            return axios
                .patch(`/webapi/bundles/reports/${this.id}/update`, {
                    data: this.data,
                    columns: this.columns,
                    queries: this.queries,
                    additionalEntries: this.additionalEntries,
                    users: this.users.map(item => item.email)
                })
                .then(response => {
                    this.data = response.data.data;
                    this.queries = response.data.queries;
                    this.additionalEntries = response.data.additionalEntries;
                    this.columns = response.data.columns;

                    this.copy.data = _.cloneDeep(response.data.data);
                    this.copy.queries = _.cloneDeep(response.data.queries);
                    this.copy.additionalEntries = _.cloneDeep(response.data.additionalEntries);
                    this.copy.columns = _.cloneDeep(response.data.columns);
                    this.copy.users = _.cloneDeep(this.users);
                })
                .catch(err => {
                    console.error(err);
                    this.showReportClicked = false;
                })
                .finally(() => this.submitted = false)
        },
        reset() {
            this.data = _.cloneDeep(this.copy.data);
            this.queries = _.cloneDeep(this.copy.queries);
            this.additionalEntries = _.cloneDeep(this.copy.additionalEntries);
            this.users = _.cloneDeep(this.copy.users);
            this.columns = _.cloneDeep(this.copy.columns);
        },
        destroy() {
            this.destroyed = true;

            axios
                .delete(`/webapi/bundles/reports/${this.id}/delete`, {})
                .then(() => window.location = "/bundles/reports")
                .catch(err => {
                    console.error(err);
                    this.destroyed = false;
                });
        },
        isNullLike(value) {
            return value === null || value === "";
        },
        showReport() {
            this.showReportClicked = true;
            this.submit()
                .then(() => {
                    if (this.showReportClicked) {
                        window.location = `/bundles/reports/${this.id}`;
                    }
                });
        }
    },
    mounted() {
        axios
            .get(`/webapi/bundles/reports/${this.id}/edit`, {})
            .then(response => {
                this.data = response.data.data;
                this.queries = response.data.queries;
                this.additionalEntries = response.data.additionalEntries;
                this.users = response.data.users;
                this.columns = response.data.columns;

                this.copy.data = _.cloneDeep(response.data.data);
                this.copy.queries = _.cloneDeep(response.data.queries);
                this.copy.additionalEntries = _.cloneDeep(response.data.additionalEntries);
                this.copy.users = _.cloneDeep(response.data.users);
                this.copy.columns = _.cloneDeep(response.data.columns);

                this.currencies = response.data.currencies;
                this.categories = response.data.categories;
                this.means = response.data.means;

                this.titles = response.data.titles;
                this.queryTypes = response.data.queryTypes;
                this.lastCurrency = response.data.lastCurrency;

                this.ready = true;
            })
            .catch(err => {
                console.error(err);
            })
    }
}
</script>
