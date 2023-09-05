<template>
    <div v-if="ready" style="margin: 12px">
        <v-row>
            <v-col
                xl="8"
                cols="12"
                order="last"
                order-xl="first"
            >
                <v-card class="loading-height">
                    <v-card-title class="text-center text-h5 text-capitalize pb-lg-0">
                        {{ props.type }}
                    </v-card-title>

                    <v-card-text>
                        <TransactionsTableComponent
                            :type="type"
                            :categories="categories"
                            :accounts="accounts"
                            @updated="getData"
                        ></TransactionsTableComponent>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col xl="4" cols="12" order-xl="last">
                <OverviewComponent
                    :charts="charts"
                ></OverviewComponent>
            </v-col>
        </v-row>
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
import axios from "axios"
import { watch, onMounted, ref } from "vue"
import type { Ref } from "vue"

import type { Account } from "@interfaces/Account"
import type { Category } from "@interfaces/Category"
import type { Chart } from "@interfaces/Chart"

import TransactionsTableComponent from "@components/transactions/TransactionsTableComponent.vue"
import OverviewComponent from "@components/charts/OverviewComponent.vue"

import { useCurrenciesStore } from "@stores/currencies"
import { useStatusStore } from "@stores/status"

const props = defineProps<{
    type: "income" | "expenses"
}>()

const currencies = useCurrenciesStore()
const status = useStatusStore()

function useData() {
    const ready = ref(false)

    const accounts: Ref<Account[]> = ref([])
    const categories: Ref<Category[]> = ref([])
    const charts: Ref<Chart[]> = ref([])

    function getData() {
        ready.value = false

        axios.get(`/web-api/${props.type}/currency/${currencies.usedCurrency}`)
            .then(response => {
                const data = response.data

                categories.value = data.categories
                accounts.value = data.accounts
                charts.value = data.charts

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
            })
    }

    return {ready, getData, accounts, categories, charts}
}

const {getData, ready, accounts, categories, charts} = useData()

watch(() => props.type, getData)

onMounted(() => {
    getData()
    currencies.$subscribe(getData)
})
</script>
