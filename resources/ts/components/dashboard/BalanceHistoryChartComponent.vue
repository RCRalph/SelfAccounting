<template>
    <div v-if="ready && chartData != undefined">
        <LineChart
            :options="options"
            :data="chartData"
            style="height: 400px"
        ></LineChart>
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

    <ErrorSnackbarComponent v-model="error"></ErrorSnackbarComponent>
</template>

<script setup lang="ts">
interface QueryParameters {
    start?: string
    end?: string
}

import axios from "axios"
import {computed, ref, onMounted} from "vue"
import type {ComputedRef, Ref} from "vue"
import type {ChartOptions, ChartData, Point} from "chart.js";

import ErrorSnackbarComponent from "@components/common/ErrorSnackbarComponent.vue";
import LineChart from "@components/charts/LineChart.vue";

import {useCurrenciesStore} from "@stores/currencies";
import useThemeSettings from "@composables/useThemeSettings";

const props = defineProps<{
    id: number
}>()

const currencies = useCurrenciesStore()
const {chartColors} = useThemeSettings()
const ready = ref(false)
const error = ref(false)

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

    const queryParameters = computed(() => {
        const result: QueryParameters = {}

        const start = new Date();
        start.setDate(start.getDate() - 31)
        result.start = start.toISOString().split("T")[0]

        result.end = new Date().toISOString().split("T")[0]

        return result
    })

    function getChartData() {
        ready.value = false

        axios.get(
            `/web-api/charts/${props.id}/currency/${currencies.usedCurrency}`,
            {params: queryParameters.value},
        )
            .then(response => {
                const data = response.data

                chartData.value = data.data

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                error.value = true
            })
    }

    return {chartData, getChartData, options}
}

const {chartData, getChartData, options} = useChartData()

onMounted(() => {
    getChartData()
    currencies.$subscribe(() => getChartData())
})
</script>
