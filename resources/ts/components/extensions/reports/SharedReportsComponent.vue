<template>
    <v-card v-if="ready">
        <CardTitleWithButtons
            title="Reports shared with me"
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
            </v-row>

            <v-data-table-server
                v-model:options="options"
                :headers="sharedReportsHeaders({
                    appendActions: true
                })"
                :items="reports"
                :items-length="total"
                :loading="loading.table"
                :items-per-page-options="[10, 15, 20, 25, 30]"
                density="comfortable"
            >
                <template v-slot:[`header.owner`]="{ column }">
                    <div class="d-flex justify-center align-center">
                        <v-menu
                            offset-y
                            :close-on-content-click="false"
                        >
                            <template v-slot:activator="{ props }">
                                <div class="d-flex justify-end">
                                    <v-btn
                                        v-bind="props"
                                        :color="filterColor(filteredData.owners.length)"
                                        :style="filteredData.owners.length && 'opacity: 1'"
                                        icon="mdi-filter"
                                        size="x-small"
                                        variant="plain"
                                    ></v-btn>
                                </div>
                            </template>

                            <v-card class="filtered-list">
                                <v-card-text class="py-0 px-2">
                                    <v-checkbox
                                        v-for="(item, i) in owners"
                                        v-model="filteredData.owners"
                                        :value="item.id"
                                        :class="i == 0 && 'mt-0 pt-0'"
                                        :label="item.username"
                                        density="comfortable"
                                        hide-details
                                    ></v-checkbox>
                                </v-card-text>
                            </v-card>
                        </v-menu>

                        <span class="mx-1">{{ column.title }}</span>
                    </div>
                </template>

                <template v-slot:[`item.title`]="{ item }">
                    <span style="white-space: nowrap">{{ item.title }}</span>
                </template>

                <template v-slot:[`item.owner`]="{ item }">
                    <span style="white-space: nowrap">{{ item.owner }}</span>
                </template>

                <template v-slot:[`item.actions`]="{ item }">
                    <div class="d-flex flex-nowrap justify-center align-center">
                        <v-tooltip
                            text="View report"
                            location="bottom"
                        >
                            <template v-slot:activator="{ props: tooltipProps }">
                                <router-link
                                    :to="`/extensions/reports/${item.id}`"
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

                        <v-tooltip
                            text="Share report"
                            location="bottom"
                        >
                            <template v-slot:activator="{ props: tooltipProps }">
                                <v-icon
                                    v-bind="tooltipProps"
                                    class="mx-1 cursor-pointer"
                                    icon="mdi-share"
                                    @click="share(item.id)"
                                ></v-icon>
                            </template>
                        </v-tooltip>

                        <DeleteDialogComponent
                            thing="report"
                            :url="`extensions/reports/${item.id}`"
                            @deleted="getData"
                        ></DeleteDialogComponent>
                    </div>
                </template>
            </v-data-table-server>
        </v-card-text>
    </v-card>

    <CardLoadingComponent
        v-else
        title="Reports shared with me"
        title-class="text-h5 text-center"
    ></CardLoadingComponent>
</template>

<script setup lang="ts">
import axios from "axios"
import { onMounted, ref, watch } from "vue"

import type { ReportOwners, SharedReport } from "@interfaces/Report"

import useUpdateWithOffset from "@composables/useUpdateWithOffset"
import useTableHeaders from "@composables/useTableHeaders"
import useTableSettings from "@composables/useTableSettings"
import useComponentState from "@composables/useComponentState"
import { useStatusStore } from "@stores/status"
import Validator from "@classes/Validator"

import CardTitleWithButtons from "@components/global/card/CardTitleWithButtonsComponent.vue"

const status = useStatusStore()

function useSharedReports() {
    const total = ref(0)

    const reports = ref<SharedReport[]>()

    const owners = ref<ReportOwners[]>([])

    async function share(id: number) {
        await navigator.clipboard.writeText(`${window.location.href}/${id}`)
        status.showSuccess("copied report link")
    }

    function getOwners() {
        ready.value = false

        axios.get("/web-api/extensions/reports")
            .then(response => {
                const data = response.data

                owners.value = data.owners

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
            })
    }

    function getData() {
        loading.value.table = true

        axios.get("web-api/extensions/reports/shared-reports", {
            params: sharedReportsQuery.value,
        })
            .then(response => {
                const data = response.data

                reports.value = data.reports.data
                total.value = data.reports.total

                loading.value.table = false
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.table = false, 2000)
            })
    }

    return {ready, total, reports, owners, share, getOwners, getData}
}

const {loading, ready} = useComponentState()
const {sharedReportsHeaders} = useTableHeaders()
const {search, options, filterColor, filteredData, sharedReportsQuery} = useTableSettings()
const {total, reports, owners, share, getOwners, getData} = useSharedReports()
const {updateWithOffset} = useUpdateWithOffset(getData)

watch(options, (_, oldValue) => {
    if (Object.keys(oldValue).length) {
        getData()
    }
})

watch(filteredData, updateWithOffset, {
    deep: true,
})

watch(search, updateWithOffset, {
    deep: true,
})

onMounted(getOwners)
</script>
