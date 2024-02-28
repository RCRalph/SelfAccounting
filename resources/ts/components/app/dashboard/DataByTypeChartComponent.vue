<template>
    <div v-if="ready">
        <DoughnutChart
            v-if="chartData?.labels?.length"
            :data="chartData"
            :options="options"
            style="height: 400px"
        ></DoughnutChart>

        <div
            v-else
            class="d-flex justify-center align-center my-7"
        >
            <span class="">No data available</span>
        </div>
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

import DoughnutChart from "@components/app/charts/DoughnutChart.vue"

import { useCurrenciesStore } from "@stores/currencies"
import { useStatusStore } from "@stores/status"
import useComponentState from "@composables/useComponentState"
import { useDoughnutChartData } from "@composables/useChartData"
import { last30DaysQuery } from "@composables/useChartQueryParameters"

const props = defineProps<{
    id: number
}>()

const currencies = useCurrenciesStore()
const status = useStatusStore()

const {ready} = useComponentState()
const {chartData, options} = useDoughnutChartData()

function getChartData() {
    ready.value = false

    axios.get(
        `/web-api/charts/${props.id}/currency/${currencies.usedCurrency}`,
        {params: last30DaysQuery.value},
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

watch(() => props.id, getChartData)

onMounted(() => {
    getChartData()
    currencies.$subscribe(() => getChartData())
})
</script>
