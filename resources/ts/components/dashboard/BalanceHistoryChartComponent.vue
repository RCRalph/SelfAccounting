<template>
    <div v-if="ready">
        <LineChart
            v-if="chartData?.datasets.length"
            :data="chartData"
            :options="options"
            style="height: 400px"
        ></LineChart>

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
import { ref, onMounted, watch } from "vue"

import LineChart from "@components/charts/LineChart.vue"

import { useCurrenciesStore } from "@stores/currencies"
import { useStatusStore } from "@stores/status"
import { useLineChartData } from "@composables/useChartData"
import { last30DaysQuery } from "@composables/useChartQueryParameters"

const props = defineProps<{
    id: number
}>()

const currencies = useCurrenciesStore()
const status = useStatusStore()
const {chartData, options} = useLineChartData()
const ready = ref(false)

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
    currencies.$subscribe(getChartData)
})
</script>
