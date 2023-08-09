<template>
    <div v-if="ready">
        <LineChart
            :options="options"
            :chartData="chartData"
            :theme="theme"
            :style="'height: 400px'"
        ></LineChart>
    </div>

    <v-overlay v-else :value="true" opacity="1" absolute>
        <v-progress-circular
            indeterminate
            size="96"
        ></v-progress-circular>
    </v-overlay>
</template>

<script>
import { useCurrenciesStore } from "&/stores/currencies";

import LineChart from "@/charts/LineChart.vue";

export default {
    components: {
        LineChart
    },
    props: {
        id: Number
    },
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    data() {
        return {
            chartData: {},
            options: {},
            theme: {},
            ready: false,
        }
    },
    computed: {
        params() {
            const retObj = {};

            retObj.end = new Date().toISOString().split("T")[0];

            retObj.start = new Date();
            retObj.start.setDate(retObj.start.getDate() - 31);
            retObj.start = retObj.start.toISOString().split("T")[0];

            return retObj;
        }
    },
    methods: {
        getChartData() {
            this.ready = false;

            axios
                .get(`/web-api/charts/${this.id}/currency/${this.currencies.usedCurrency}`, { params: this.params })
                .then(response => {
                    const data = response.data;

                    this.chartData = data.data;
                    this.options = data.options;
                    this.theme = data.theme;

                    this.ready = true;
                })
        }
    },
    mounted() {
        this.getChartData();
        this.currencies.$subscribe(() => this.getChartData());
    }
}
</script>
