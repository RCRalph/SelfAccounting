<template>
    <Doughnut
        :data="props.data"
        :options="props.options"
    ></Doughnut>
</template>

<script setup lang="ts">
import {Chart as ChartJS, ArcElement, Tooltip, Legend} from "chart.js"
import {Doughnut} from "vue-chartjs"
import type {ChartOptions, ChartData} from "chart.js"

ChartJS.register(ArcElement, Tooltip, Legend)

const props = defineProps<{
    data: ChartData<"doughnut", number[], string>,
    options: ChartOptions<"doughnut">
}>()
</script>
