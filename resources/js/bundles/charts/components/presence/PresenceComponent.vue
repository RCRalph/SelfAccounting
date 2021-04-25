<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-chart-bar"></i>
                Chart presence
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <CategoriesComponent
                    :content="categories"
                    :currencies="currencies"
                    :lastcurrency="currency"
                ></CategoriesComponent>

                <MeansOfPaymentComponent
                    :content="means"
                    :currencies="currencies"
                    :currency="currency"
                    class="mt-3"
                ></MeansOfPaymentComponent>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import Loading from "../../../../components/Loading.vue";
import CategoriesComponent from "./CategoriesComponent.vue";
import MeansOfPaymentComponent from "./MeansOfPaymentComponent.vue";

export default {
    components: {
        Loading,
        CategoriesComponent,
        MeansOfPaymentComponent
    },
    data() {
        return {
            ready: false,

            currencies: [],
            categories: {},
            means: {},

            currency: 1
        }
    },
    mounted() {
        axios
            .get("/webapi/bundles/charts/presence", {})
            .then(response => {
                const data = response.data;

                this.currencies = data.currencies;
                this.categories = data.categories;
                this.means = data.means;
                this.currency = data.lastCurrency;

                this.ready = true;
            })
    },
}
</script>
