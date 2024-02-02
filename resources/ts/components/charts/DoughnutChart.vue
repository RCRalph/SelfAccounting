<template>
    <Doughnut
        v-if="props.data"
        :data="props.data"
        :options="props.options"
    ></Doughnut>
</template>

<script setup lang="ts">
import {
    Chart as ChartJS,
    ArcElement,
    Tooltip,
    Legend,
    type ChartOptions,
    type ChartData,
} from "chart.js"
import { Doughnut } from "vue-chartjs"

ChartJS.register(ArcElement, Tooltip, Legend)

const props = defineProps<{
    data?: ChartData<"doughnut", number[], string>,
    options: ChartOptions<"doughnut">
}>()
</script>
