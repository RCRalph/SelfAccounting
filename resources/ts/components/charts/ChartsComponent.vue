<template>
    <div v-if="ready">
        <v-tabs
            :height="60"
            :center-active="true"
            align-tabs="center"
            :show-arrows="true"
            :stacked="true"
            class="v-slide-group--horizontal"
        >
            <v-tab
                v-for="item in charts"
                :value="item.id"
            >
                {{ item.name }}
            </v-tab>
        </v-tabs>
    </div>

    <v-overlay
        v-else
        :model-value="true"
        :contained="true"
    >
        <v-progress-circular
            indeterminate
            size="128"
        ></v-progress-circular>
    </v-overlay>
</template>

<script setup lang="ts">
interface Chart {
    id: number
    name: string
}

import axios from "axios"
import {onMounted, ref} from "vue"
import type {Ref} from "vue"
import {useStatusStore} from "@stores/status";

const status = useStatusStore()
const charts: Ref<Chart[]> = ref([])
const ready = ref(false)

onMounted(() => {
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
})
</script>

<style scoped lang="scss">
.v-tab {
    max-width: 200px !important;
}
</style>
