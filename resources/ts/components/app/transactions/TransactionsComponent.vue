<template>
    <div v-if="ready">
        <v-row>
            <v-col
                xl="8"
                cols="12"
                order="last"
                order-xl="first"
            >
                <v-card class="loading-height">
                    <CardTitleWithButtons
                        :title="props.type"
                        large-font
                    ></CardTitleWithButtons>

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
import { watch, onMounted, ref } from "vue"

import type { AccountData } from "@interfaces/Account"
import type { CategoryData } from "@interfaces/Category"
import type { Chart } from "@interfaces/Chart"

import TransactionsTableComponent from "@components/app/transactions/TransactionsTableComponent.vue"
import OverviewComponent from "@components/app/charts/OverviewComponent.vue"

import { useCurrenciesStore } from "@stores/currencies"
import { useStatusStore } from "@stores/status"
import useComponentState from "@composables/useComponentState"

const props = defineProps<{
    type: "income" | "expenses"
}>()

const currencies = useCurrenciesStore()
const status = useStatusStore()

function useData() {
    const accounts = ref<AccountData[]>([])

    const categories = ref<CategoryData[]>([])

    const charts = ref<Chart[]>([])

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

    return {getData, accounts, categories, charts}
}

const {ready} = useComponentState()
const {getData, accounts, categories, charts} = useData()

watch(() => props.type, getData)

onMounted(() => {
    getData()
    currencies.$subscribe(getData)
})
</script>
