<template>
    <v-card class="sticky-panel loading-height">
        <div v-if="ready">
            <v-card-title class="font-weight-bold justify-center text-h5">Overview</v-card-title>

            <v-card-text>
                <v-row>
                    <v-col cols="12" class="d-flex justify-center flex-nowrap align-center">
                        <div class="me-2 ms-0" style="width: 145px">
                            <v-text-field type="date" label="Start" v-model="start" :max="end" min="1970-01-01" hide-details></v-text-field>
                        </div>

                        <div>
                            <v-icon>mdi-arrow-right-bold</v-icon>
                        </div>

                        <div class="ms-2 me-0" style="width: 145px">
                            <v-text-field type="date" label="End" v-model="end" :min="start || '1970-01-01'" hide-details></v-text-field>
                        </div>
                    </v-col>

                    <v-col xl="12" lg="6" cols="12" v-if="chartData.categories.data.labels.length">
                        <div class="text-h5 text-center text-capitalize">{{ type }} by categories</div>

                        <DoughnutChart
                            :options="chartData.categories.options"
                            :chartData="chartData.categories.data"
                            :style="'height: 330px'"
                        ></DoughnutChart>
                    </v-col>

                    <v-col xl="12" lg="6" cols="12" v-if="chartData.means.data.labels.length">
                        <div class="text-h5 text-center text-capitalize">{{ type }} by means</div>

                        <DoughnutChart
                            :options="chartData.means.options"
                            :chartData="chartData.means.data"
                            :style="'height: 330px'"
                        ></DoughnutChart>
                    </v-col>

                    <v-col v-if="!chartData.categories.data.labels.length && !chartData.means.data.labels.length">
                        <div class="text-h4 text-center">No data</div>
                    </v-col>
                </v-row>
            </v-card-text>
        </div>

        <v-overlay v-else :value="true" opacity="1" absolute>
            <v-progress-circular
                indeterminate
                size="96"
            ></v-progress-circular>
        </v-overlay>
    </v-card>
</template>

<script>
import { useCurrenciesStore } from "&/stores/currencies";
import main from "&/mixins/main";

import DoughnutChart from "@/charts/DoughnutChart.vue";

export default {
    mixins: [main],
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    components: {
        DoughnutChart
    },
    props: {
        type: {
            type: String,
            required: true,
            validator: value => ["income", "outcome"].includes(value)
        },
        charts: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            start: "",
            end: "",

            chartData: {
                categories: {},
                means: {}
            },

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
                .get(`/web-api/${this.type}/currency/${this.currencies.usedCurrency}/overview`, {
                    params: {
                        start: this.start,
                        end: this.end
                    }
                })
                .then(response => {
                    const data = response.data;

                    this.chartData.categories = data.categories;
                    this.chartData.means = data.means;

                    this.ready = true;
                })
        }
    },
    mounted() {
        this.getData();
    }
}
</script>
