<template>
    <div v-if="ready">
        <v-row class="pagination-fixed-margin">
            <v-col xl="9" cols="12" order="last" order-xl="first">
                <v-card>
                    <CardTitleWithButtons :title="reportInformation?.title"></CardTitleWithButtons>

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
                                <EditReportDialogComponent
                                    v-if="canEdit"
                                    :id="Number(route.params.id)"
                                    @updated="getData"
                                ></EditReportDialogComponent>
                            </v-col>
                        </v-row>

                        <v-data-table
                            v-model:search="search.title"
                            :headers="tableHeaders(headers.transactions, {excludedColumns})"
                            :items="tableData.data.value"
                            :items-per-page="-1"
                            class="table-bordered"
                            density="comfortable"
                            multi-sort
                        >
                            <template v-slot:item="{item, index}: {item: ReportTransactionRow, index: number}">
                                <tr class="text-center">
                                    <td
                                        v-if="tableData.getSpan(index, 'date')"
                                        :rowspan="tableData.getSpan(index, 'date')"
                                        :class="tableData.isRowHighlighted(index, tableData.getSpan(index, 'date')) && 'table-hover-background'"
                                        style="white-space: nowrap;"
                                        @mouseover="tableData.setHoveredRows(index, tableData.getSpan(index, 'date'))"
                                        @mouseleave="tableData.resetHoveredRows()"
                                    >
                                        {{ item.date }}
                                    </td>

                                    <td
                                        v-if="tableData.getSpan(index, 'title')"
                                        :rowspan="tableData.getSpan(index, 'title')"
                                        :class="tableData.isRowHighlighted(index, tableData.getSpan(index, 'title')) && 'table-hover-background'"
                                        @mouseover="tableData.setHoveredRows(index, tableData.getSpan(index, 'title'))"
                                        @mouseleave="tableData.resetHoveredRows()"
                                    >
                                        {{ item.title }}
                                    </td>

                                    <td
                                        v-if="tableData.getSpan(index, 'amount')"
                                        :rowspan="tableData.getSpan(index, 'amount')"
                                        :class="tableData.isRowHighlighted(index, tableData.getSpan(index, 'amount')) && 'table-hover-background'"
                                        @mouseover="tableData.setHoveredRows(index, tableData.getSpan(index, 'amount'))"
                                        @mouseleave="tableData.resetHoveredRows()"
                                    >
                                        {{ item.amount }}
                                    </td>

                                    <td
                                        v-if="tableData.getSpan(index, 'price')"
                                        :rowspan="tableData.getSpan(index, 'price')"
                                        :class="tableData.isRowHighlighted(index, tableData.getSpan(index, 'price')) && 'table-hover-background'"
                                        @mouseover="tableData.setHoveredRows(index, tableData.getSpan(index, 'price'))"
                                        @mouseleave="tableData.resetHoveredRows()"
                                    >
                                        {{
                                            formats.numberWithCurrency(
                                                item.price ?? 0,
                                                currencies.findCurrency(item.currency_id)?.ISO ?? "",
                                            )
                                        }}
                                    </td>

                                    <td
                                        v-if="tableData.getSpan(index, 'value')"
                                        :rowspan="tableData.getSpan(index, 'value')"
                                        :class="tableData.isRowHighlighted(index, tableData.getSpan(index, 'value')) && 'table-hover-background'"
                                        @mouseover="tableData.setHoveredRows(index, tableData.getSpan(index, 'value'))"
                                        @mouseleave="tableData.resetHoveredRows()"
                                    >
                                        {{
                                            formats.numberWithCurrency(
                                                item.value ?? 0,
                                                currencies.findCurrency(item.currency_id)?.ISO ?? "",
                                            )
                                        }}
                                    </td>

                                    <td
                                        v-if="tableData.getSpan(index, 'category')"
                                        :rowspan="tableData.getSpan(index, 'category')"
                                        style="max-width: 200px"
                                        :class="tableData.isRowHighlighted(index, tableData.getSpan(index, 'category')) && 'table-hover-background'"
                                        @mouseover="tableData.setHoveredRows(index, tableData.getSpan(index, 'category'))"
                                        @mouseleave="tableData.resetHoveredRows()"
                                    >
                                        <div class="d-flex justify-start align-center">
                                            <div class="mr-2">
                                                <v-icon
                                                    v-if="item.category?.icon"
                                                    style="min-width: 24px"
                                                >
                                                    {{ formats.iconName(item.category.icon) }}
                                                </v-icon>
                                            </div>

                                            <div class="d-flex justify-center align-center" style="width: 100%">
                                                {{ item.category?.name }}
                                            </div>
                                        </div>
                                    </td>

                                    <td
                                        v-if="tableData.getSpan(index, 'account')"
                                        :rowspan="tableData.getSpan(index, 'account')"
                                        style="max-width: 200px"
                                        :class="tableData.isRowHighlighted(index, tableData.getSpan(index, 'account')) && 'table-hover-background'"
                                        @mouseover="tableData.setHoveredRows(index, tableData.getSpan(index, 'account'))"
                                        @mouseleave="tableData.resetHoveredRows()"
                                    >
                                        <div class="d-flex justify-start align-center">
                                            <div class="mr-2">
                                                <v-icon
                                                    v-if="item.account?.icon"
                                                    style="min-width: 24px"
                                                >
                                                    {{ formats.iconName(item.account.icon) }}
                                                </v-icon>
                                            </div>

                                            <div class="d-flex justify-center align-center" style="width: 100%">
                                                {{ item.account?.name }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>

                            <template v-slot:bottom></template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col
                cols="12"
                xl="3"
                order-xl="last"
            >
                <v-row
                    :no-gutters="xl"
                    class="overview-block"
                >
                    <v-col
                        cols="12"
                        md="4"
                        xl="12"
                        class="mb-xl-4"
                    >
                        <v-card style="height: 100%;">
                            <CardTitleWithButtons
                                title="Owner"
                                large-font
                                extra-bottom
                            ></CardTitleWithButtons>

                            <v-card-text
                                class="d-flex align-center justify-center"
                                style="height: calc(100% - 64px)"
                            >
                                <v-avatar
                                    :image="reportInformation?.owner.profile_picture_link"
                                    size="64"
                                ></v-avatar>

                                <h2 class="ml-4">
                                    {{ reportInformation?.owner.username }}
                                </h2>
                            </v-card-text>
                        </v-card>
                    </v-col>

                    <v-col
                        cols="12"
                        md="4"
                        xl="12"
                        class="mb-xl-4"
                    >
                        <v-card style="height: 100%;">
                            <CardTitleWithButtons
                                title="Sum"
                                large-font
                                extra-bottom
                            ></CardTitleWithButtons>

                            <v-card-text
                                v-if="!reportInformation?.sum"
                                class="d-flex align-center justify-center"
                                style="height: calc(100% - 64px)"
                            >
                                Sum not calculated
                            </v-card-text>

                            <v-card-text
                                v-else-if="Object.keys(reportInformation?.sum).length == 1"
                                class="text-h4 text-center font-weight-regular mb-6"
                            >
                                {{
                                    formats.numberWithCurrency(
                                        reportInformation?.sum[Number(Object.keys(reportInformation?.sum)[0])] ?? 0,
                                        currencies.findCurrency(Number(Object.keys(reportInformation!.sum)[0]))?.ISO ?? "",
                                    )
                                }}
                            </v-card-text>

                            <v-card-text v-else>
                                <v-table class="mx-3">
                                    <tbody>
                                        <tr v-for="(item, key) in reportInformation.sum">
                                            <td class="text-h5 text-center font-weight-regular mb-6 py-2">
                                                {{
                                                    formats.numberWithCurrency(
                                                        item,
                                                        currencies.findCurrency(key)?.ISO ?? "",
                                                    )
                                                }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </v-table>
                            </v-card-text>
                        </v-card>
                    </v-col>

                    <v-col
                        cols="12"
                        md="4"
                        xl="12"
                        class="mb-xl-4"
                    >
                        <v-card style="height: 100%;">
                            <CardTitleWithButtons
                                title="Export"
                                large-font
                                extra-bottom
                            ></CardTitleWithButtons>

                            <v-card-text
                                class="d-flex align-center justify-center"
                                style="height: calc(100% - 64px)"
                            >
                                <div class="d-flex justify-space-around flex-wrap">
                                    <v-btn
                                        variant="outlined"
                                        class="mx-2 my-1"
                                        text="Export to .csv"
                                        width="175"
                                        @click="exportToCSV"
                                    ></v-btn>

                                    <v-btn
                                        variant="outlined"
                                        class="mx-2 my-1"
                                        text="Export to .xlsx"
                                        width="175"
                                        color="success"
                                        @click="exportToXLSX"
                                    ></v-btn>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>

        <div class="pagination-fixed">
            <v-card
                class="pa-1 flex-grow-1"
                elevation="6"
            >
                <v-pagination
                    v-model="currentReportIndex"
                    :length="reportIDs.length"
                    variant="elevated"
                    class="flex-grow-1"
                ></v-pagination>
            </v-card>
        </div>
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
import XLSX from "xlsx"
import { onMounted, ref, watch } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useDisplay } from "vuetify"

import type { ReportInformation, ReportTransactionRow } from "@interfaces/Report"

import EditReportDialogComponent from "@components/app/extensions/reports/EditReportDialogComponent.vue"

import { useCurrenciesStore } from "@stores/currencies"
import { useStatusStore } from "@stores/status"
import useComponentState from "@composables/useComponentState"
import useTableHeaders from "@composables/useTableHeaders"
import useTableQuery from "@composables/useTableQuery"
import useFormats from "@composables/useFormats"
import { downloadCSV } from "@composables/useDownload"
import { dateToUTC } from "@composables/useDates"
import TableDataMerger from "@classes/TableDataMerger"
import Validator from "@classes/Validator"

const currencies = useCurrenciesStore()
const status = useStatusStore()

const route = useRoute()
const router = useRouter()
const {xl} = useDisplay()
const formats = useFormats()

function useNavigation() {
    const reportIDs = ref<number[]>([])

    const currentReportIndex = ref<number>(0)

    watch(currentReportIndex, index => {
        if (currentReportIndex.value && route.params.id != reportIDs.value[index - 1].toString()) {
            router.push(`/extensions/reports/${reportIDs.value[index - 1]}`)
                .then(() => {
                    search.value.title = ""
                    getData()
                })
        }
    })

    return {currentReportIndex, reportIDs}
}

function useReportData() {
    const reportInformation = ref<ReportInformation>()

    const tableData = new TableDataMerger<ReportTransactionRow>(
        ["date"],
        ["id", "value"],
    )

    const canEdit = ref(false)

    const excludedColumns = ref<Set<string>>()

    function getData() {
        ready.value = false
        tableData.reset()

        axios.get(`/web-api/extensions/reports/${route.params.id}`)
            .then(response => {
                const data = response.data

                reportInformation.value = data.information
                excludedColumns.value = new Set(data.excludedColumns)
                canEdit.value = data.canEdit

                reportIDs.value = data.reports
                currentReportIndex.value = reportIDs.value.findIndex(item => item == Number(route.params.id)) + 1

                tableData.append(data.items)

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
            })
    }

    return {reportInformation, tableData, canEdit, excludedColumns, getData}
}

function useDataExports() {
    function exportToCSV() {
        const shownHeaders = headers.transactions.filter(item => !excludedColumns.value?.has(item.key))

        downloadCSV(
            `${reportInformation.value?.title}.csv`,
            shownHeaders,
            tableData.data.value.map(item => {
                const result: Record<string, string> = {}

                for (const header of shownHeaders) {
                    if (header.key == "price" || header.key == "value") {
                        result[header.key] = `${item[header.key]} ${currencies.findCurrency(item.currency_id)?.ISO}`
                    } else if (header.key == "category" || header.key == "account") {
                        result[header.key] = String(item[header.key]?.name)
                    } else {
                        result[header.key] = String(item[header.key as keyof ReportTransactionRow])
                    }
                }

                return result
            }),
        )

        status.showSuccess("exported to .csv")
    }

    function exportToXLSX() {
        const shownHeaders = headers.transactions.filter(item => !excludedColumns.value?.has(item.key))
        const content: unknown[][] = [shownHeaders.map(item => item.title)]

        for (const row of tableData.data.value) {
            content.push(shownHeaders.map(header => {
                if (header.key == "category" || header.key == "account") {
                    return row[header.key]?.name
                } else if (header.key == "date") {
                    return dateToUTC(new Date(row[header.key] ?? "1970-01-01"))
                } else {
                    return row[header.key as keyof ReportTransactionRow]
                }
            }))
        }

        const workbook = XLSX.utils.book_new()
        const sheet = XLSX.utils.aoa_to_sheet(content, {
            cellDates: true,
        })

        // Set date format to columns which include date
        if (!excludedColumns.value?.has("date")) {
            const range = XLSX.utils.decode_range(sheet["!ref"] ?? "")
            const columnIndex = shownHeaders.findIndex(item => item.key == "date")

            for (let i = range.s.r + 1; i <= range.e.r; i++) {
                sheet[XLSX.utils.encode_cell({r: i, c: columnIndex})].t = "d"
            }
        }

        for (const column of ["price", "value"]) {
            if (!excludedColumns.value?.has(column)) {
                const range = XLSX.utils.decode_range(sheet["!ref"] ?? "")
                const columnIndex = shownHeaders.findIndex(item => item.key == column)

                for (let i = range.s.r + 1; i <= range.e.r; i++) {
                    sheet[XLSX.utils.encode_cell({
                        r: i,
                        c: columnIndex,
                    })].z = `0.00 ${currencies.findCurrency(tableData.data.value[i - 1]?.currency_id)?.ISO}`
                }
            }
        }

        XLSX.utils.book_append_sheet(workbook, sheet, reportInformation.value?.title)
        XLSX.writeFile(workbook, `${reportInformation.value?.title}.xlsx`)
    }

    return {exportToCSV, exportToXLSX}
}

const {ready} = useComponentState()
const {headers, tableHeaders} = useTableHeaders()
const {search} = useTableQuery([])
const {currentReportIndex, reportIDs} = useNavigation()
const {reportInformation, tableData, canEdit, excludedColumns, getData} = useReportData()
const {exportToCSV, exportToXLSX} = useDataExports()

onMounted(getData)
</script>
