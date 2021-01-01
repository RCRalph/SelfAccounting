<template>
    <div :class="darkmode ? 'dark-card' : 'card'">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-cog"></i>
                Settings
            </div>

            <div class="d-flex" v-if="ready">
                <div class="h4 my-auto mr-3">Currency:</div>
                <select class="form-control" v-model="currentCurrency">
                    <option
                        v-for="currency in currencies"
                        :key="currency.id"
                        :value="currency.id"
                    >
                        {{ currency.ISO }}
                    </option>
                </select>
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <CategoriesComponent
                    :darkmode="darkmode"
                    :categories="categories"
                    :currency="currentCurrency"
                ></CategoriesComponent>

                <MeansOfPaymentComponent
                    :darkmode="darkmode"
                    :means="means"
                    :currency="currentCurrency"
                    class="mt-3"
                ></MeansOfPaymentComponent>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import Loading from "../Loading.vue"
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
            darkmode: false,
            ready: false,
            currentCurrency: 1,

            currencies: [],
            categories: {},
            means: {}
        }
    },
    beforeMount() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    },
    mounted() {
        axios
            .get("/webapi/settings", {})
            .then(response => {
                const data = response.data;

                this.currencies = data.currencies;
                this.categories = data.categories;
                this.means = data.means;
                this.currentCurrency = data.lastCurrency;

                this.ready = true;
            })
    },
    beforeUpdate() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    }
}
</script>

Loading
