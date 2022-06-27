<template>
    <div v-if="ready">
        <DoughnutChart
            v-if="chartData.labels.length"
            :options="options"
            :chartData="chartData"
            :style="'height: 400px'"
        ></DoughnutChart>
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
import DoughnutChart from "@/charts/DoughnutChart.vue";

export default {
    props: {
        id: Number
    },
    components: {
        DoughnutChart
    },
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    data() {
        return {
            chartData: {},
            options: {},
            ready: false
        }
    },
    methods: {
        getChartData() {
            this.ready = false;

            axios
                .get(`/web-api/dashboard/${this.currencies.usedCurrency}/charts/${this.id}`)
                .then(response => {
                    const data = response.data;
                    this.chartData = data.data;
                    this.options = data.options;

                    this.ready = true;
                })
        }
    },
    mounted() {
        this.getChartData();
        this.currencies.$subscribe(() => this.getChartData());
    },
    watch: {
        id() {
            this.getChartData();
        }
    }
}
</script>
