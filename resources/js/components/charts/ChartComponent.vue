<template>
    <v-card v-if="ready" class="chart-card">
        <v-card-title class="justify-center text-h5">{{ chartInfo.name }}</v-card-title>

        <v-card-text class="d-flex justify-center flex-nowrap align-end">
            <div class="me-2 ms-0" style="width: 145px">
                <v-text-field type="date" label="Start" v-model="start" :max="end" min="1970-01-01" hide-details></v-text-field>
            </div>

            <div>
                <v-icon>mdi-arrow-right-bold</v-icon>
            </div>

            <div class="ms-2 me-0" style="width: 145px">
                <v-text-field type="date" label="End" v-model="end" :min="start || '1970-01-01'" hide-details></v-text-field>
            </div>
        </v-card-text>

        <!-- Chart selection -->

        <LineChart
            v-if="chartInfo.type == 'line'"
            :options="chartData.options"
            :chartData="chartData.data"
            class="chart-block"
        ></LineChart>

        <DoughnutChart
            v-if="chartInfo.type == 'doughnut'"
            :options="chartData.options"
            :chartData="chartData.data"
            class="chart-block"
        ></DoughnutChart>
    </v-card>

    <v-card v-else>
        <v-card-text class="d-flex justify-center">
            <v-progress-circular
                indeterminate
                size="96"
            ></v-progress-circular>
        </v-card-text>
    </v-card>
</template>

<script>
import { useCurrenciesStore } from "&/stores/currencies";
import main from "&/mixins/main";

import LineChart from "@/charts/LineChart.vue";
import DoughnutChart from "@/charts/DoughnutChart.vue";

export default {
    mixins: [main],
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    components: {
        LineChart,
        DoughnutChart
    },
    data() {
        return {
            start: "",
            end: "",
            lastChange: new Date(),
            chartData: {},
            chartInfo: {},

            ready: false
        }
    },
    watch: {
        start: "updateWithOffset",
        end: "updateWithOffset",
        "$route.params.id": "getData",
        "currencies.usedCurrency": "getData"
    },
    methods: {
        getData() {
            this.ready = false;

            axios
                .get(`/web-api/charts/${this.$route.params.id}/currency/${this.currencies.usedCurrency}`, {
                    params: {
                        start: this.start,
                        end: this.end
                    }
                })
                .then(response => {
                    const data = response.data;

                    this.chartInfo = data.info;
                    this.chartData.options = data.options;
                    this.chartData.data = data.data;

                    this.ready = true;
                })
        },
        updateWithOffset() {
            const timeOffset = 500;
            this.lastChange = new Date();

            setTimeout(() => {
                if (new Date() - this.lastChange >= timeOffset) {
                    this.getData();
                }
            }, timeOffset + 1);
        }
    },
    mounted() {
        this.getData();
    }
}
</script>
