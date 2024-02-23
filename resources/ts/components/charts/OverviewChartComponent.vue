<template>
    <div v-if="ready">
        <div
            class="text-h5 text-center text-capitalize"
        >
            {{ props.chart.name }}
        </div>

        <div v-if="chartData?.labels?.length" class="chart-size">
            <DoughnutChart
                :data="chartData"
                :options="options"
            ></DoughnutChart>
        </div>

        <div v-else class="text-h6 text-center">No data</div>
    </div>

    <v-overlay
        v-else
        :model-value="true"
        contained
    >
        <v-progress-circular
            indeterminate
            size="96"
        ></v-progress-circular>
    </v-overlay>
</template>

<script setup lang="ts">
import axios from "axios"
import { onMounted, watch } from "vue"

import type { Chart } from "@interfaces/Chart"

import DoughnutChart from "@components/charts/DoughnutChart.vue"

import { useStatusStore } from "@stores/status"
import { useCurrenciesStore } from "@stores/currencies"
import useComponentState from "@composables/useComponentState"
import { useDoughnutChartData } from "@composables/useChartData"
import { queryWithDates } from "@composables/useChartQueryParameters"

const props = defineProps<{
    chart: Chart,
    start: string,
    end: string
}>()

const currencies = useCurrenciesStore()
const status = useStatusStore()

const {ready} = useComponentState()
const {chartData, options} = useDoughnutChartData()

function getChartData() {
    ready.value = false

    axios.get(
        `/web-api/charts/${props.chart.id}/currency/${currencies.usedCurrency}`,
        {params: queryWithDates(props.start, props.end)},
    )
        .then(response => {
            const data = response.data

            chartData.value = data.data

            ready.value = true
        })
        .catch(err => {
            console.error(err)
            status.showError()
        })
}

watch(() => props.start, getChartData)
watch(() => props.end, getChartData)

onMounted(() => {
    getChartData()
    currencies.$subscribe(getChartData)
})
</script>

<style scoped>
.chart-size {
    max-width: calc(100% - 16px);
    margin-left: 8px;
    margin-bottom: 10px;
    height: 335px !important;
}
</style>
