<template>
    <Line
        v-if="props.data"
        :data="props.data"
        :options="props.options"
    ></Line>
</template>

<script setup lang="ts">
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    TimeScale,
    PointElement,
    LineElement,
    Tooltip,
    Legend,
} from "chart.js"

import "chartjs-adapter-date-fns"
import { Line } from "vue-chartjs"
import type { ChartOptions, ChartData, Point } from "chart.js"

ChartJS.register(CategoryScale, LinearScale, TimeScale, PointElement, LineElement, Tooltip, Legend)

const props = defineProps<{
    data?: ChartData<"line", Point[], string>,
    options: ChartOptions<"line">
}>()
</script>
