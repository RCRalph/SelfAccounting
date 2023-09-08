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
                    <CardTitleWithButtons
                        title="Transfers"
                        :large-font="true"
                        :extra-bottom="true"
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
import { onMounted, ref } from "vue"
import type { Ref } from "vue"

import type { Account } from "@interfaces/Account"
import type { Chart } from "@interfaces/Chart"

import CardTitleWithButtons from "@components/common/CardTitleWithButtons.vue"
import TransfersTableComponent from "@components/transfers/TransfersTableComponent.vue"
import AddTransferDialogComponent from "@components/transfers/AddTransferDialogComponent.vue"
import OverviewComponent from "@components/charts/OverviewComponent.vue"

import { useCurrenciesStore } from "@stores/currencies"
import { useStatusStore } from "@stores/status"

const currencies = useCurrenciesStore()
const status = useStatusStore()

function useData() {
    const ready = ref(false)

    const accounts: Ref<Account[]> = ref([])
    const charts: Ref<Chart[]> = ref([])

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

    return {ready, getData, accounts, charts}
}

const {getData, ready, accounts, charts} = useData()

onMounted(() => {
    getData()
    currencies.$subscribe(getData)
})
</script>