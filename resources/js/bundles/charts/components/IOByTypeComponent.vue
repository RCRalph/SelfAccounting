<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-chart-bar"></i>
                {{ title }}
            </div>

            <div class="d-flex" v-if="ready">
                <div class="currency-text">Currency:</div>
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
                <Chart
					v-if="data[currentCurrency]"
                    :chart-data="data[currentCurrency]"
                    :options="options"
                ></Chart>

				<EmptyPlaceholder v-else></EmptyPlaceholder>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import Loading from "../../../components/Loading.vue";
import EmptyPlaceholder from "../../../components/EmptyPlaceholder.vue";
import Chart from "./DoughnutChart.vue";

export default {
    props: ["title"],
    components: {
        Loading,
		EmptyPlaceholder,
        Chart
    },
    data() {
        return {
            ready: false,

            currencies: [],
            currentCurrency: 1,

            data: {},
            options: {}
        }
    },
    mounted() {
        axios
            .get(`/webapi/bundles/charts/${this.title.split(" ").join("-").toLowerCase()}`, {})
            .then(response => {
                const data = response.data;

                this.currencies = data.currencies;
                this.currentCurrency = data.lastCurrency;

                this.data = data.data,
                this.options = data.options;

                this.ready = true;
            })
    }
}
</script>
