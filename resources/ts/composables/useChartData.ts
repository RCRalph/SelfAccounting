import { computed, ref } from "vue"
import type { ComputedRef, Ref } from "vue"
import type { ChartData, ChartOptions, Point } from "chart.js"
import useThemeSettings from "@composables/useThemeSettings"

function useDoughnutChartData() {
    const {chartColors} = useThemeSettings()

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

    return {chartData, options}
}

function useLineChartData() {
    const {chartColors} = useThemeSettings()

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

    return {chartData, options}
}

export {
    useDoughnutChartData,
    useLineChartData,
}