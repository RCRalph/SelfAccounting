<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-newspaper"></i>
                New report
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <ReportDataComponent
                    v-model="data"
                ></ReportDataComponent>

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

                <div class="row">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <button
                            type="button"
                            class="big-button-success"
                            @click="submit"
                            :disabled="submitted || !canSubmit"
                        >
                            <div v-if="!submitted">Submit</div>

                            <span
                                v-else
                                class="spinner-border spinner-border-sm"
                                role="status"
                                aria-hidden="true"
                            ></span>
                        </button>
                    </div>
                </div>
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
import Loading from "../../../components/Loading.vue";
import EmptyPlaceholder from "../../../components/EmptyPlaceholder.vue";

export default {
    components: {
        Loading,
        EmptyPlaceholder,
        ReportDataComponent,
        ReportQueriesComponent,
        ReportAdditionalEntries,
        ReportUsersSharedComponent
    },
    data() {
        return {
            ready: false,
            currencies: {},
            categories: {},
            means: {},
            titles: [],
            queryTypes: [],
            lastCurrency: 1,
            submitted: false,

            queries: [],
            additionalEntries: [],
            users: [],
            data: {
                title: "",
                income_addition: true,
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
                    item.min_date === "" ||
                    !isNaN(Date.parse(item.min_date)) &&
                    (
                        !item.max_date ||
                        new Date(item.min_date).getTime() <= new Date(item.max_date).getTime()
                    ),

                    item.max_date === "" ||
                    !isNaN(Date.parse(item.max_date)) &&
                    (
                        !item.min_date ||
                        new Date(item.max_date).getTime() >= new Date(item.min_date).getTime()
                    ),

                    item.min_amount === "" ||
                    !isNaN(Number(item.min_amount)) &&
                    (
                        !item.max_amount ||
                        item.min_price <= item.max_price
                    ) && item.min_amount < 1e6 && item.min_amount >= 0,

                    item.max_amount === "" ||
                    !isNaN(Number(item.max_amount)) &&
                    (
                        !item.min_amount ||
                        item.min_price <= item.max_price
                    ) && item.max_amount < 1e6 && item.max_amount >= 0,

                    item.min_price === "" ||
                    !isNaN(Number(item.min_price)) &&
                    (
                        !item.max_price ||
                        item.min_price <= item.max_price
                    ) && item.min_price < 1e11 && item.min_price >= 0,

                    item.max_price === "" ||
                    !isNaN(Number(item.max_price)) &&
                    (
                        !item.min_price ||
                        item.min_price <= item.max_price
                    ) && item.max_price < 1e11 && item.max_price >= 0,
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
                    item.amount < 1e6 &&
                    item.amount > 0,

                    !isNaN(Number(item.price)) &&
                    item.price < 1e11 &&
                    item.price > 0
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
                query_data: this.queryTypes[0],
                min_date: "",
                max_date: "",
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
                date: null,
                title: null,
                amount: null,
                price: null,
                currency_id: this.lastCurrency,
                category_id: 0,
                mean_id: 0
            });
        },
        removeEntry(i) {
            this.additionalEntries.splice(i, 1);
        },
        submit() {
            this.submitted = true;

            axios
                .post("/webapi/bundles/reports/store", {
                    data: this.data,
                    queries: this.queries,
                    additionalEntries: this.additionalEntries,
                    users: this.users.map(item => item.email)
                })
                .then(response => {
                    //window.location = `/bundles/reports/${response.data.id}`;
                })
                .catch(err => {
                    console.error(err);
                    this.submitted = false;
                })
        }
    },
    mounted() {
        axios
            .get("/webapi/bundles/reports/create", {})
            .then(response => {
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
