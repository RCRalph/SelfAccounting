<template>
    <v-card class="sticky-panel loading-height">
        <v-card-title class="font-weight-bold justify-center text-h5">Overview</v-card-title>

        <v-card-text>
            <v-row>
                <v-col cols="12" class="d-flex justify-center flex-nowrap align-end">
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

                <v-col
                    v-for="chart in charts" :key="chart.id"
                    xl="12" lg="6" cols="12"
                >
                    <IOOverviewChartComponent
                        :chart="chart"
                        :start="start"
                        :end="end"
                    ></IOOverviewChartComponent>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script>
import { useCurrenciesStore } from "&/stores/currencies";

import IOOverviewChartComponent from "@/income-outcome/IOOverviewChartComponent.vue";


export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    components: {
        IOOverviewChartComponent
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
