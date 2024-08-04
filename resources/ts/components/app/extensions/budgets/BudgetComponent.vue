<template>
    <v-row v-if="ready">
        <v-col
            cols="12"
            md="10"
            offset-md="1"
            lg="12"
            offset-lg="0"
            xl="8"
            offset-xl="2"
        >
            <v-card>
                <CardTitleWithButtons
                    title="Budgets"
                    large-font
                ></CardTitleWithButtons>

                <v-card-text>
                    <v-row
                        class="align-center"
                        no-gutters
                    >
                        <v-col cols="12" sm="5" lg="4">
                            <v-text-field
                                v-model="search.title"
                                :rules="[
                                    Validator.search(64)
                                ]"
                                variant="underlined"
                                append-inner-icon="mdi-magnify"
                                label="Search"
                                density="compact"
                                counter="64"
                                single-line
                            ></v-text-field>
                        </v-col>

                        <v-col
                            order="first"
                            order-sm="last"
                            cols="12"
                            sm="7"
                            lg="8"
                            class="multi-button-table-top"
                        >
                            <CreateBudgetDialogComponent></CreateBudgetDialogComponent>
                        </v-col>
                    </v-row>

                    <v-data-table
                        v-model:options="options"
                        :headers="tableHeaders(headers.budgets, {
                            appendActions: true
                        })"
                        :items="budgets"
                        :loading="loading.table"
                        :items-per-page="-1"
                        density="comfortable"
                        multi-sort
                    >
                        <template v-slot:[`item.title`]="{ item }">
                            <span style="white-space: nowrap">{{ item.title }}</span>
                        </template>

                        <template v-slot:[`item.actions`]="{ item }">
                            <div
                                v-if="item.id"
                                class="d-flex flex-nowrap justify-center align-center"
                            >
                                <v-tooltip
                                    text="View report"
                                    location="bottom"
                                >
                                    <template v-slot:activator="{ props: tooltipProps }">
                                        <router-link
                                            :to="`/extensions/budgets/${item.id}`"
                                            style="color: inherit"
                                        >
                                            <v-icon
                                                v-bind="tooltipProps"
                                                class="mx-1 cursor-pointer"
                                                icon="mdi-open-in-app"
                                            ></v-icon>
                                        </router-link>
                                    </template>
                                </v-tooltip>

                                <EditBudgetDialogComponent
                                    :id="item.id"
                                    show-icon
                                    @updated="getStartData"
                                ></EditBudgetDialogComponent>

                                <v-tooltip
                                    :text="duplicatedBudgetID == item.id ? 'Duplicating...' : 'Duplicate report'"
                                    location="bottom"
                                >
                                    <template v-slot:activator="{ props: tooltipProps }">
                                        <v-btn
                                            v-bind="tooltipProps"
                                            :loading="duplicatedBudgetID == item.id"
                                            :disabled="!!duplicatedBudgetID"
                                            class="mx-1 cursor-pointer"
                                            icon="mdi-content-duplicate"
                                            variant="text"
                                            density="compact"
                                            size="auto"
                                            @click="duplicate(item.id)"
                                        ></v-btn>
                                    </template>
                                </v-tooltip>

                                <DeleteDialogComponent
                                    thing="report"
                                    :url="`extensions/budgets/${item.id}`"
                                    @deleted="getStartData"
                                ></DeleteDialogComponent>
                            </div>
                        </template>

                        <template v-slot:bottom></template>
                    </v-data-table>

                    <v-infinite-scroll
                        v-if="!loading.table"
                        @load="getMoreData"
                    ></v-infinite-scroll>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>

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
import { onMounted, ref, watch } from "vue"
import { useRouter } from "vue-router"

import type { BudgetInformation } from "@interfaces/Budget"

import useUpdateWithOffset from "@composables/useUpdateWithOffset"
import useTableHeaders from "@composables/useTableHeaders"
import useTableQuery from "@composables/useTableQuery"
import { useStatusStore } from "@stores/status"
import Validator from "@classes/Validator"
import useTableSettings from "@composables/useTableSettings"
import useComponentState from "@composables/useComponentState"

import CreateBudgetDialogComponent from "@components/app/extensions/budgets/CreateBudgetDialogComponent.vue"
import EditBudgetDialogComponent from "@components/app/extensions/budgets/EditBudgetDialogComponent.vue"

const router = useRouter()
const status = useStatusStore()

function useBudgets() {
    const budgets = ref<BudgetInformation[]>([])

    async function getData() {
        return axios
            .get(`/web-api/extensions/budgets`, {
                params: {
                    page: pagination.value.page,
                    ...query.value,
                },
            })
            .then(response => {
                const data = response.data

                budgets.value = budgets.value.concat(data.budgets.data)

                pagination.value.page++
                pagination.value.last = data.budgets.last_page
                pagination.value.perPage = data.budgets.per_page
            })

    }

    async function getStartData() {
        loading.value.table = true
        pagination.value.page = 1
        pagination.value.last = Infinity
        budgets.value.length = 0

        await getData()
            .then(() => loading.value.table = false)
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.table = false, 2000)
            })
    }

    async function getMoreData(state: {
        done: Function
    }) {
        if (pagination.value.page > pagination.value.last) {
            state.done("empty")
            return
        }

        getData()
            .then(() => {
                if (pagination.value.page <= pagination.value.last) {
                    state.done("ok")
                } else {
                    state.done("empty")
                }
            })
            .catch(() => state.done("error"))
    }

    return {budgets, getMoreData, getStartData}
}

function useActions() {
    const duplicatedBudgetID = ref<number>()

    function duplicate(id?: number) {
        if (!id) return

        duplicatedBudgetID.value = id

        axios.post(`/web-api/extensions/budgets/${id}/duplicate`)
            .then(response => router.push(`/extensions/budgets/${response.data.id}`))
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => duplicatedBudgetID.value = undefined, 2000)
            })
    }

    return {duplicatedBudgetID, duplicate}
}

const {ready} = useComponentState()
const {headers, tableHeaders} = useTableHeaders()
const {loading, pagination} = useTableSettings()
const {options, search, query} = useTableQuery(["page", "title", "orderFields", "orderDirections"])
const {budgets, getMoreData, getStartData} = useBudgets()
const {duplicatedBudgetID, duplicate} = useActions()
const {updateWithOffset} = useUpdateWithOffset(getStartData)

watch(options, (_, oldValue) => {
    if (Object.keys(oldValue).length) {
        getStartData()
    }
})

watch(search, updateWithOffset, {
    deep: true,
})

onMounted(async () => {
    await getStartData()
    ready.value = true
})
</script>