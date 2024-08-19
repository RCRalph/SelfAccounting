<template>
    <v-card>
        <CardTitleWithButtons
            title="Owned reports"
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
                    <CreateReportDialogComponent></CreateReportDialogComponent>
                </v-col>
            </v-row>

            <v-data-table-server
                v-model:options="options"
                :headers="tableHeaders(headers.ownedReports, {
                    appendActions: true
                })"
                :items="reports"
                :items-length="total"
                :loading="loading.table"
                :items-per-page-options="[10, 15, 20, 25, 30]"
                density="comfortable"
            >
                <template v-slot:[`item.title`]="{ item }">
                    <span style="white-space: nowrap">{{ item.title }}</span>
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

                        <EditReportDialogComponent
                            :id="item.id"
                            show-icon
                            @updated="getData"
                        ></EditReportDialogComponent>

                        <v-tooltip
                            :text="duplicatedReportID == item.id ? 'Duplicating...' : 'Duplicate report'"
                            location="bottom"
                        >
                            <template v-slot:activator="{ props: tooltipProps }">
                                <v-btn
                                    v-bind="tooltipProps"
                                    :loading="duplicatedReportID == item.id"
                                    :disabled="!!duplicatedReportID"
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
                            :url="`extensions/reports/${item.id}`"
                            @deleted="getData"
                        ></DeleteDialogComponent>
                    </div>
                </template>
            </v-data-table-server>
        </v-card-text>
    </v-card>
</template>

<script setup lang="ts">
import axios from "axios"
import { onMounted, ref, watch } from "vue"
import { useRouter } from "vue-router"

import type { OwnedReport } from "@interfaces/Report"

import useUpdateWithOffset from "@composables/useUpdateWithOffset"
import useTableHeaders from "@composables/useTableHeaders"
import useTableQuery from "@composables/useTableQuery"
import useComponentState from "@composables/useComponentState"
import { useStatusStore } from "@stores/status"
import Validator from "@classes/Validator"

import CreateReportDialogComponent from "@components/app/extensions/reports/CreateReportDialogComponent.vue"
import EditReportDialogComponent from "@components/app/extensions/reports/EditReportDialogComponent.vue"

const router = useRouter()
const status = useStatusStore()

function useOwnedReports() {
    const total = ref(0)

    const reports = ref<OwnedReport[]>([])

    function getData() {
        loading.value.table = true

        axios.get(`/web-api/extensions/reports/owned-reports`, {
            params: query.value,
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

    return {total, reports, getData}
}

function useActions() {
    const duplicatedReportID = ref<number>()

    function duplicate(id: number) {
        duplicatedReportID.value = id

        axios.post(`/web-api/extensions/reports/${id}/duplicate`)
            .then(response => router.push(`/extensions/reports/${response.data.id}`))
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => duplicatedReportID.value = undefined, 2000)
            })
    }

    async function share(id: number) {
        await navigator.clipboard.writeText(`${window.location.href}/${id}`)
        status.showSuccess("copied report link")
    }

    return {duplicatedReportID, duplicate, share}
}

const {loading} = useComponentState()
const {headers, tableHeaders} = useTableHeaders()
const {options, search, query} = useTableQuery(["page", "items", "owners", "orderFields", "orderDirections"])
const {total, reports, getData} = useOwnedReports()
const {duplicatedReportID, duplicate, share} = useActions()
const {updateWithOffset} = useUpdateWithOffset(getData)

watch(options, (_, oldValue) => {
    if (Object.keys(oldValue).length) {
        getData()
    }
})

watch(search, updateWithOffset, {
    deep: true,
})

onMounted(getData)
</script>