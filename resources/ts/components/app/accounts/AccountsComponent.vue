<template>
    <div v-if="ready">
        <v-row>
            <v-col
                cols="12"
                lg="10"
                offset-lg="1"
                xl="8"
                offset-xl="2"
            >
                <v-card class="loading-height">
                    <CardTitleWithButtons
                        :title="`Accounts: ${currencies.usedCurrencyObject.ISO}`"
                        large-font
                    >
                        <AddAccountDialogComponent @added="getData"></AddAccountDialogComponent>
                    </CardTitleWithButtons>

                    <v-card-text>
                        <v-data-table
                            v-model:options="options"
                            :headers="tableHeaders(headers.accounts,{
                                excludedColumns: new Set(['start_date', 'start_balance']),
                                appendActions: true
                            })"
                            :items="accounts"
                            :items-per-page="-1"
                            density="comfortable"
                        >
                            <template v-slot:[`item.icon`]="{ item }">
                                <v-icon v-if="item.icon">
                                    {{ formats.iconName(item.icon) }}
                                </v-icon>

                                <span v-else>
                                    N/A
                                </span>
                            </template>

                            <template v-slot:[`item.name`]="{ item }">
                                <span style="white-space: nowrap">{{ item.name }}</span>
                            </template>

                            <template v-slot:[`item.used_in_income`]="{ item }">
                                <v-checkbox-btn
                                    v-model="item.used_in_income"
                                    direction="vertical"
                                    class="d-flex justify-center"
                                    false-icon="mdi-close"
                                    disabled
                                    hide-details
                                ></v-checkbox-btn>
                            </template>

                            <template v-slot:[`item.used_in_expenses`]="{ item }">
                                <v-checkbox-btn
                                    v-model="item.used_in_expenses"
                                    direction="vertical"
                                    class="d-flex justify-center"
                                    false-icon="mdi-close"
                                    disabled
                                    hide-details
                                ></v-checkbox-btn>
                            </template>

                            <template v-slot:[`item.show_on_charts`]="{ item }">
                                <v-checkbox-btn
                                    v-model="item.show_on_charts"
                                    direction="vertical"
                                    class="d-flex justify-center"
                                    false-icon="mdi-close"
                                    disabled
                                    hide-details
                                ></v-checkbox-btn>
                            </template>

                            <template v-slot:[`item.count_to_summary`]="{ item }">
                                <v-checkbox-btn
                                    v-model="item.count_to_summary"
                                    direction="vertical"
                                    class="d-flex justify-center"
                                    false-icon="mdi-close"
                                    disabled
                                    hide-details
                                ></v-checkbox-btn>
                            </template>

                            <template v-slot:[`item.actions`]="{ item }">
                                <div class="d-flex flex-nowrap justify-center align-center">
                                    <EditAccountDialogComponent
                                        :id="item.id"
                                        @updated="getData"
                                    ></EditAccountDialogComponent>

                                    <DuplicateDialogComponent
                                        :url="`accounts/account/${item.id}/duplicate`"
                                        thing="account"
                                        :specify-currency="true"
                                        @duplicated="getData"
                                    ></DuplicateDialogComponent>

                                    <DeleteDialogComponent
                                        :url="`accounts/account/${item.id}`"
                                        thing="account"
                                        @deleted="getData"
                                    ></DeleteDialogComponent>
                                </div>
                            </template>

                            <template v-slot:bottom></template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
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
import { ref, onMounted } from "vue"
import type { Account } from "@interfaces/Account"

import AddAccountDialogComponent from "@components/app/accounts/AddAccountDialogComponent.vue"
import EditAccountDialogComponent from "@components/app/accounts/EditAccountDialogComponent.vue"

import { useCurrenciesStore } from "@stores/currencies"
import useComponentState from "@composables/useComponentState"
import useTableHeaders from "@composables/useTableHeaders"
import useTableSettings from "@composables/useTableSettings"
import useFormats from "@composables/useFormats"

const currencies = useCurrenciesStore()
const formats = useFormats()

function useData() {
    const accounts = ref<Account[]>([])

    function getData() {
        ready.value = false

        axios.get(`/web-api/accounts/${currencies.usedCurrency}`)
            .then(response => {
                accounts.value = response.data

                ready.value = true
            })
    }

    return {accounts, getData}
}

const {ready} = useComponentState()
const {headers, tableHeaders} = useTableHeaders()
const {options} = useTableSettings()
const {accounts, getData} = useData()

onMounted(() => {
    getData()
    currencies.$subscribe(getData)
})
</script>
