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
                        title="Transfers"
                        large-font
                        extra-bottom
                    >
                        <AddTransferDialogComponent
                            @added="getData"
                        ></AddTransferDialogComponent>
                    </CardTitleWithButtons>

                    <v-card-text>
                        <TransfersTableComponent
                            :accounts="accounts"
                            @updated="getData"
                        ></TransfersTableComponent>
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
import { onMounted, ref } from "vue"

import type { AccountData } from "@interfaces/Account"
import type { Chart } from "@interfaces/Chart"

import TransfersTableComponent from "@components/transfers/TransfersTableComponent.vue"
import AddTransferDialogComponent from "@components/transfers/AddTransferDialogComponent.vue"
import OverviewComponent from "@components/charts/OverviewComponent.vue"

import { useCurrenciesStore } from "@stores/currencies"
import { useStatusStore } from "@stores/status"
import useComponentState from "@composables/useComponentState"

const currencies = useCurrenciesStore()
const status = useStatusStore()

function useData() {
    const accounts = ref<AccountData[]>([])

    const charts = ref<Chart[]>([])

    function getData() {
        ready.value = false

        axios.get(`/web-api/transfers/currency/${currencies.usedCurrency}`)
            .then(response => {
                const data = response.data

                accounts.value = data.accounts
                charts.value = data.charts

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
            })
    }

    return {getData, accounts, charts}
}

const {ready} = useComponentState()
const {getData, accounts, charts} = useData()

onMounted(() => {
    getData()
    currencies.$subscribe(getData)
})
</script>