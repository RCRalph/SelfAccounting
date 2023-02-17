<template>
    <div v-if="ready">
        <div class="text-h5 text-center text-capitalize" :class="$vuetify.theme.dark ? 'white--text' : 'black--text'">{{ chart.name }}</div>

        <DoughnutChart
            v-if="chartData.labels.length"
            :options="options"
            :chartData="chartData"
            class="chart-size"
        ></DoughnutChart>

        <div v-else class="text-h6 text-center">No data</div>
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
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    components: {
        DoughnutChart
    },
    props: {
        chart: {
            required: true,
            type: Object
        },
        start: {
            required: false,
            type: String
        },
        end: {
            required: false,
            type: String
        }
    },
    data() {
        return {
            chartData: {},
            options: {},
            ready: false
        }
    },
    watch: {
        start() {
            this.getData();
        },
        end() {
            this.getData();
        }
    },
    methods: {
        getData() {
            this.ready = false;

            axios
                .get(`/web-api/charts/${this.chart.id}/currency/${this.currencies.usedCurrency}`, {
                    params: {
                        start: this.start,
                        end: this.end
                    }
                })
                .then(response => {
                    const data = response.data;

                    this.chartData = data.data;
                    this.options = data.options;

                    this.ready = true;
                })
        }
    },
    mounted() {
        this.getData();
        this.currencies.$subscribe(() => this.getData());
    }
}
</script>
