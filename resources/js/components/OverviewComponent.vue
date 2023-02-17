<template>
    <v-card class="sticky-panel loading-height overview-block">
        <v-card-title class="justify-center text-h5">Overview</v-card-title>

        <v-card-text>
            <div class="d-flex justify-center flex-nowrap align-end mb-4">
                <div class="me-2 ms-0" style="width: 145px">
                    <v-text-field type="date" label="Start" v-model="start" :max="end" min="1970-01-01" hide-details></v-text-field>
                </div>

                <div>
                    <v-icon>mdi-arrow-right-thick</v-icon>
                </div>

                <div class="ms-2 me-0" style="width: 145px">
                    <v-text-field type="date" label="End" v-model="end" :min="start || '1970-01-01'" hide-details></v-text-field>
                </div>
            </div>

            <div class="charts">
                <v-row no-gutters>
                    <v-col
                        v-for="chart in charts" :key="chart.id"
                        xl="12" lg="6" cols="12"
                    >
                        <OverviewChartComponent
                            :chart="chart"
                            :start="start"
                            :end="end"
                        ></OverviewChartComponent>
                    </v-col>
                </v-row>
            </div>
        </v-card-text>
    </v-card>
</template>

<script>
import { useCurrenciesStore } from "&/stores/currencies";

import OverviewChartComponent from "@/charts/OverviewChartComponent.vue";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    components: {
        OverviewChartComponent
    },
    props: {
        charts: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            start: "",
            end: "",

            ready: false
        }
    }
}
</script>
