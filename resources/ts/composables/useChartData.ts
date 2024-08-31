import { computed, ref } from "vue"
import type { ChartData, ChartOptions, Point } from "chart.js"
import useThemeSettings from "@composables/useThemeSettings"

function useDoughnutChartData() {
    const {chartColors} = useThemeSettings()

    const chartData = ref<ChartData<"doughnut", number[], string>>()

    const options = computed<ChartOptions<"doughnut">>(() => ({
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

    const chartData = ref<ChartData<"line", Point[], string>>()

    const options = computed<ChartOptions<"line">>(() => ({
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                type: "time",
                time: {
                    displayFormats: {
                        day: "dd MMM",
                        year: "yyyy-MM-dd",
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