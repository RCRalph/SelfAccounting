<template>
    <v-card
        v-if="ready"
        class="chart-card"
    >
        <v-card-text class="d-flex justify-center flex-nowrap align-end">
            <div class="me-2 ms-0" style="width: 145px">
                <v-text-field
                    v-model="start"
                    v-model:focused="startFocused"
                    :max="end"
                    type="date"
                    label="Start"
                    min="1970-01-01"
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

            <div class="ms-2 me-0" style="width: 145px">
                <v-text-field
                    v-model="end"
                    v-model:focused="endFocused"
                    :min="start || '1970-01-01'"
                    type="date"
                    label="End"
                    variant="underlined"
                    hide-details
                ></v-text-field>
            </div>
        </v-card-text>

        <div
            v-if="chartHasData"
            class="chart-block"
        >
            <LineChart
                v-if="chart.type == 'line'"
                :options="lineOptions"
                :data="lineChartData"
            ></LineChart>

            <DoughnutChart
                v-if="chart.type == 'doughnut'"
                :options="doughnutOptions"
                :data="doughnutChartData"
            ></DoughnutChart>
        </div>

        <div v-else class="text-h6 text-center">No data</div>
    </v-card>

    <CardLoadingComponent
        v-else
        card-class="chart-card"
    ></CardLoadingComponent>
</template>

<script setup lang="ts">
import axios from "axios"
import { onMounted, ref, watch, computed } from "vue"

import type { Chart } from "@interfaces/Chart"

import LineChart from "@components/app/charts/LineChart.vue"
import DoughnutChart from "@components/app/charts/DoughnutChart.vue"

import { useStatusStore } from "@stores/status"
import useComponentState from "@composables/useComponentState"
import { useDoughnutChartData, useLineChartData } from "@composables/useChartData"
import { queryWithDates } from "@composables/useChartQueryParameters"
import { useCurrenciesStore } from "@stores/currencies"

const props = defineProps<{
    chart: Chart
}>()

const currencies = useCurrenciesStore()
const status = useStatusStore()

function useData() {
    const start = ref("")
    const startFocused = ref(false)

    const end = ref("")
    const endFocused = ref(false)

    const chartHasData = computed(() =>
        lineChartData.value?.datasets.length ||
        doughnutChartData.value?.datasets.length && doughnutChartData.value?.datasets[0].data.length,
    )

    function getChartData() {
        if (startFocused.value || endFocused.value) return

        ready.value = false

        axios.get(
            `/web-api/charts/${props.chart.id}/currency/${currencies.usedCurrency}`,
            {params: queryWithDates(start.value, end.value)},
        )
            .then(response => {
                const data = response.data

                switch (props.chart.type) {
                    case "line":
                        lineChartData.value = data.data
                        break
                    case "doughnut":
                        doughnutChartData.value = data.data
                        break
                    default:
                        throw new Error("Invalid chart type")
                }

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                status.showError()
            })
    }

    return {chartHasData, end, endFocused, getChartData, start, startFocused}
}

const {ready} = useComponentState()
const {chartHasData, end, endFocused, getChartData, start, startFocused} = useData()
const {chartData: lineChartData, options: lineOptions} = useLineChartData()
const {chartData: doughnutChartData, options: doughnutOptions} = useDoughnutChartData()

watch(startFocused, getChartData)
watch(endFocused, getChartData)
watch(() => props.chart, getChartData)

onMounted(() => {
    getChartData()
    currencies.$subscribe(getChartData)
})
</script>