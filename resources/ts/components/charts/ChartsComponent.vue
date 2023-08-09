<template>
    <div v-if="ready">
        <v-tabs
            :height="60"
            :center-active="true"
            align-tabs="center"
            :show-arrows="true"
            :stacked="true"
            bg-color="grey-darken-4"
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

<script lang="ts">
interface Chart {
    id: number
    name: string
}
</script>

<script setup lang="ts">
import axios from "axios"
import {onMounted, ref} from "vue"
import type {Ref} from "vue"
//import {useRoute} from "vue-router"

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
})
</script>

<style scoped lang="scss">
.v-tab {
    max-width: 200px !important;
}
</style>
