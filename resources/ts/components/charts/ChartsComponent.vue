<template>
    <div
        v-if="ready"
        class="tabs"
    >
        <v-tabs
            v-model="currentChart"
            :height="60"
            align-tabs="center"
            center-active
            show-arrows
            stacked
        >
            <v-tab
                v-for="item in charts"
                :value="item.id"
            >
                {{ item.name }}
            </v-tab>
        </v-tabs>

        <ChartComponent
            v-if="currentChartData"
            :chart="currentChartData"
        ></ChartComponent>
    </div>

    <v-overlay
        v-else
        :model-value="true"
        contained
    >
        <v-progress-circular
            indeterminate
            size="128"
        ></v-progress-circular>
    </v-overlay>
</template>

<script setup lang="ts">
import axios from "axios"
import { onMounted, ref, computed } from "vue"

import type { Chart } from "@interfaces/Chart"

import ChartComponent from "@components/charts/ChartComponent.vue"

import { useStatusStore } from "@stores/status"

const status = useStatusStore()

function useData() {
    const ready = ref(false)
    const charts = ref<Chart[]>([])
    const currentChart = ref<number | undefined>(undefined)

    function getData() {
        ready.value = false

        axios.get("/web-api/charts")
            .then(response => {
                const data = response.data

                charts.value = data.charts

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                status.showError()
            })
    }

    const currentChartData = computed(() => charts.value.find(item => item.id == currentChart.value))

    return {charts, currentChart, currentChartData, getData, ready}
}

const {charts, currentChart, currentChartData, getData, ready} = useData()

onMounted(getData)
</script>

<style scoped lang="scss">
.v-tab {
    max-width: 200px !important;
}
</style>
