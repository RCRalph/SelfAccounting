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
        :contained="true"
    >
        <v-progress-circular
            indeterminate
            size="96"
        ></v-progress-circular>
    </v-overlay>
</template>

<script setup lang="ts">
import axios from "axios"
import {computed, ref, onMounted, watch} from "vue"
import type {ComputedRef, Ref} from "vue"
import type {ChartOptions, ChartData} from "chart.js";

import DoughnutChart from "@components/charts/DoughnutChart.vue";

import {useCurrenciesStore} from "@stores/currencies";
import {useStatusStore} from "@stores/status";
import useThemeSettings from "@composables/useThemeSettings";
import useChartQueryParameters from "@composables/useChartQueryParameters";

const props = defineProps<{
    id: number
}>()

const currencies = useCurrenciesStore()
const status = useStatusStore()
const {chartColors} = useThemeSettings()
const {dashboardQuery} = useChartQueryParameters()
const ready = ref(false)

function useChartData() {
    const chartData: Ref<ChartData<"doughnut", number[], string> | undefined> = ref(undefined)

    const options: ComputedRef<ChartOptions<"doughnut">> = computed(() => ({
        responsive: true,
        maintainAspectRatio: false,
        circumference: 180,
        rotation: -90,
        plugins: {
            legend: {
                display: true,
                labels: {
                    color: chartColors.value.fontColor,
                },
            },
        },
    }))

    function getChartData() {
        ready.value = false

        axios.get(
            `/web-api/charts/${props.id}/currency/${currencies.usedCurrency}`,
            {params: dashboardQuery.value},
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

    return {chartData, getChartData, options}
}

const {chartData, getChartData, options} = useChartData()

watch(() => props.id, getChartData)

onMounted(() => {
    getChartData()
    currencies.$subscribe(() => getChartData())
})
</script>
