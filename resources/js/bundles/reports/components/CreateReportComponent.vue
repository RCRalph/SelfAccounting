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
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import ReportDataComponent from "./ReportDataComponent.vue";
import ReportQueriesComponent from "./ReportQueriesComponent.vue";
import ReportAdditionalEntries from "./ReportAdditionalEntriesComponent.vue";
import Loading from "../../../components/Loading.vue";
import EmptyPlaceholder from "../../../components/EmptyPlaceholder.vue";

export default {
    components: {
        Loading,
        EmptyPlaceholder,
        ReportDataComponent,
        ReportQueriesComponent,
        ReportAdditionalEntries
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
    methods: {
        addQuery() {
            this.queries.push({
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

                this.addQuery();
                this.addEntry();

                this.ready = true;
            })
            .catch(err => {
                console.error(err);
            })
    }
}
</script>
