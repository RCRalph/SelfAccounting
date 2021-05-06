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
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import ReportDataComponent from "./ReportDataComponent.vue";
import Loading from "../../../components/Loading.vue";
import EmptyPlaceholder from "../../../components/EmptyPlaceholder.vue";

export default {
    components: {
        Loading,
        EmptyPlaceholder,
        ReportDataComponent
    },
    data() {
        return {
            ready: false,
            currencies: {},
            categories: {},
            means: {},
            titles: [],

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
    mounted() {
        axios
            .get("/webapi/bundles/reports/create", {})
            .then(response => {
                this.currencies = response.data.currencies;
                this.categories = response.data.categories;
                this.means = response.data.means;
                this.titles = response.data.titles;

                this.ready = true;
            })
            .catch(err => {
                console.error(err);
            })
    }
}
</script>
