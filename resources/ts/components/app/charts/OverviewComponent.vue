<template>
    <v-card class="loading-height overview-block">
        <CardTitleWithButtons
            title="Overview"
            large-font
        ></CardTitleWithButtons>

        <v-card-text>
            <div class="d-flex justify-center flex-nowrap align-end mb-4">
                <div
                    class="me-2 ms-0"
                    style="width: 145px"
                >
                    <v-text-field
                        v-model="start"
                        :max="end"
                        min="1970-01-01"
                        type="date"
                        label="Start"
                        variant="underlined"
                        hide-details
                    ></v-text-field>
                </div>

                <div>
                    <v-icon
                        icon="mdi-arrow-right-thick"
                        class="mb-2"
                    ></v-icon>
                </div>

                <div
                    class="ms-2 me-0"
                    style="width: 145px"
                >
                    <v-text-field
                        v-model="end"
                        :min="start || '1970-01-01'"
                        type="date"
                        label="End"
                        variant="underlined"
                        hide-details
                    ></v-text-field>
                </div>
            </div>

            <div class="charts">
                <v-row no-gutters>
                    <v-col
                        v-for="chart in props.charts"
                        cols="12"
                        lg="6"
                        xl="12"
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

<script setup lang="ts">
import { ref } from "vue"
import type { Chart } from "@interfaces/Chart"

import OverviewChartComponent from "@components/app/charts/OverviewChartComponent.vue"

const props = defineProps<{
    charts: Chart[]
}>()

const start = ref("")
const end = ref("")
</script>
