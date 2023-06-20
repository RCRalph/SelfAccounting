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
import main from "&/mixins/main";

import DoughnutChart from "@/charts/DoughnutChart.vue";

export default {
    mixins: [main],
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
            ready: false,
            keyVal: 0
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
    watch: {
        id() {
            this.getChartData();
        },
        "$vuetify.theme.dark"() {
            this.options = this.setFontColor(this.options);
            this.keyVal++;
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
                    this.options = this.setFontColor(data.options);

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
