<template>
    <div v-if="ready">
        <LineChart
            :options="options"
            :chartData="chartData"
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
    }
}
</script>
