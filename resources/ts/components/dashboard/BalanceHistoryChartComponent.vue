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
import type {ChartOptions, ChartData, Point} from "chart.js";

import LineChart from "@components/charts/LineChart.vue";

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
const {last30DaysQuery} = useChartQueryParameters()
const ready = ref(false)

function useChartData() {
    const chartData: Ref<ChartData<"line", Point[], string> | undefined> = ref(undefined)

    const options: ComputedRef<ChartOptions<"line">> = computed(() => ({
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                type: "time",
                time: {
                    displayFormats: {
                        day: "dd MMM",
                        year: "YYYY-MM-dd",
                    },
                    minUnit: "day",
                },
                ticks: {
                    color: chartColors.value.fontColor,
                },
                grid: {
                    color: chartColors.value.gridColor,
                },
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: chartColors.value.fontColor,
                },
                grid: {
                    color: chartColors.value.gridColor,
                },
            },
        },
        elements: {
            line: {
                tension: 0,
            },
        },
        plugins: {
            legend: {
                display: true,
                labels: {
                    color: chartColors.value.fontColor,
                },
            },
            tooltip: {
                callbacks: {
                    title: (context: any) => context[0].raw.x,
                },
            },
        },
    }))

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

    return {chartData, getChartData, options}
}

const {chartData, getChartData, options} = useChartData()

watch(() => props.id, getChartData)

onMounted(() => {
    getChartData()
    currencies.$subscribe(getChartData)
})
</script>
