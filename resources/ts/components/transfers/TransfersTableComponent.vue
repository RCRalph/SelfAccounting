<template>
    <v-data-table
        v-model:options="options"
        :headers="transferHeaders()"
        :items="tableData.data.value"
        :loading="loading.table"
        :items-per-page="-1"
        class="table-bordered"
        density="comfortable"
        multi-sort
    >
        <template v-slot:[`column.date`]="{column, getSortIcon, isSorted, sortBy}">
            <div class="d-flex justify-center align-center">
                <v-menu
                    offset-y
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ props }: any">
                        <div class="d-flex justify-end">
                            <v-btn
                                v-bind="props"
                                :color="filterColor(filteredData.dates.length)"
                                :style="filteredData.dates.length && 'opacity: 1'"
                                icon="mdi-filter"
                                size="x-small"
                                variant="plain"
                            ></v-btn>
                        </div>
                    </template>

                    <v-date-picker
                        v-model="filteredData.dates"
                        :multiple="true"
                        min="1970-01-01"
                        color="primary"
                        prev-icon="mdi-skip-previous"
                        next-icon="mdi-skip-next"
                    ></v-date-picker>
                </v-menu>

                <span class="mx-1">{{ column.title }}</span>

                <div class="d-flex justify-start">
                    <v-icon
                        :icon="getSortIcon(column)"
                        key="icon"
                        class="v-data-table-header__sort-icon"
                    ></v-icon>

                    <div
                        v-if="isSorted(column)"
                        key="badge"
                        class="v-data-table-header__sort-badge"
                    >
                        {{ sortBy.findIndex(x => x.key === column.key) + 1 }}
                    </div>
                </div>
            </div>
        </template>

        <template v-slot:[`column.source_account`]="{column}">
            <div class="d-flex justify-center align-center">
                <v-menu
                    offset-y
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ props }: any">
                        <div class="d-flex justify-end">
                            <v-btn
                                v-bind="props"
                                :color="filterColor(filteredData.source_accounts.length)"
                                :style="filteredData.source_accounts.length && 'opacity: 1'"
                                icon="mdi-filter"
                                size="x-small"
                                variant="plain"
                            ></v-btn>
                        </div>
                    </template>

                    <v-card class="filtered-list">
                        <v-card-text class="py-0 px-2">
                            <v-checkbox
                                v-for="(item, i) in props.accounts"
                                v-model="filteredData.source_accounts"
                                :value="item.id"
                                :key="i"
                                :class="i == 0 && 'mt-0 pt-0'"
                                density="comfortable"
                                hide-details
                            >
                                <template v-slot:label>
                                    <div class="pr-3">
                                        <v-icon v-if="item.icon" class="mr-2">
                                            {{ formats.iconName(item.icon) }}
                                        </v-icon>

                                        {{ item.name }}
                                    </div>
                                </template>
                            </v-checkbox>
                        </v-card-text>
                    </v-card>
                </v-menu>

                <span class="mx-1">{{ column.title }}</span>
            </div>
        </template>

        <template v-slot:[`column.target_account`]="{column}">
            <div class="d-flex justify-center align-center">
                <v-menu
                    offset-y
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ props }: any">
                        <div class="d-flex justify-end">
                            <v-btn
                                v-bind="props"
                                :color="filterColor(filteredData.target_accounts.length)"
                                :style="filteredData.target_accounts.length && 'opacity: 1'"
                                icon="mdi-filter"
                                size="x-small"
                                variant="plain"
                            ></v-btn>
                        </div>
                    </template>

                    <v-card class="filtered-list">
                        <v-card-text class="py-0 px-2">
                            <v-checkbox
                                v-for="(item, i) in props.accounts"
                                v-model="filteredData.target_accounts"
                                :value="item.id"
                                :key="i"
                                :class="i == 0 && 'mt-0 pt-0'"
                                density="comfortable"
                                hide-details
                            >
                                <template v-slot:label>
                                    <div class="pr-3">
                                        <v-icon v-if="item.icon" class="mr-2">
                                            {{ formats.iconName(item.icon) }}
                                        </v-icon>

                                        {{ item.name }}
                                    </div>
                                </template>
                            </v-checkbox>
                        </v-card-text>
                    </v-card>
                </v-menu>

                <span class="mx-1">{{ column.title }}</span>
            </div>
        </template>

        <template v-slot:[`item`]="{item, index}">
            <tr class="text-center">
                <td
                    v-if="item.raw.date.span"
                    :rowspan="item.raw.date.span"
                    :class="tableData.isRowHighlighted(index, item.raw.date.span) && 'table-hover-background'"
                    style="white-space: nowrap;"
                    @mouseover="tableData.setHoveredRows(index, item.raw.date.span)"
                    @mouseleave="tableData.resetHoveredRows()"
                >
                    {{ item.raw.date.value }}
                </td>

                <td
                    v-if="item.raw.source_value.span"
                    :rowspan="item.raw.source_value.span"
                    :class="tableData.isRowHighlighted(index, item.raw.source_value.span) && 'table-hover-background'"
                    @mouseover="tableData.setHoveredRows(index, item.raw.source_value.span)"
                    @mouseleave="tableData.resetHoveredRows()"
                >
                    {{
                        formats.numberWithCurrency(
                            item.raw.source_value.value,
                            currencies.usedCurrencyObject.ISO,
                        )
                    }}
                </td>

                <td
                    v-if="item.raw.source_account.span"
                    :rowspan="item.raw.source_account.span"
                    style="max-width: 200px"
                    :class="tableData.isRowHighlighted(index, item.raw.source_account.span) && 'table-hover-background'"
                    @mouseover="tableData.setHoveredRows(index, item.raw.source_account.span)"
                    @mouseleave="tableData.resetHoveredRows()"
                >
                    <div class="d-flex justify-start align-center">
                        <div class="mr-2">
                            <v-icon
                                v-if="item.raw.source_account.value.icon"
                                style="min-width: 24px"
                            >
                                {{ formats.iconName(item.raw.source_account.value.icon) }}
                            </v-icon>
                        </div>

                        <div class="d-flex justify-center align-center" style="width: 100%">
                            {{ item.raw.source_account.value.name }}
                        </div>
                    </div>
                </td>

                <td
                    v-if="item.raw.target_value.span"
                    :rowspan="item.raw.target_value.span"
                    :class="tableData.isRowHighlighted(index, item.raw.target_value.span) && 'table-hover-background'"
                    @mouseover="tableData.setHoveredRows(index, item.raw.target_value.span)"
                    @mouseleave="tableData.resetHoveredRows()"
                >
                    {{
                        formats.numberWithCurrency(
                            item.raw.target_value.value,
                            currencies.usedCurrencyObject.ISO,
                        )
                    }}
                </td>

                <td
                    v-if="item.raw.target_account.span"
                    :rowspan="item.raw.target_account.span"
                    style="max-width: 200px"
                    :class="tableData.isRowHighlighted(index, item.raw.target_account.span) && 'table-hover-background'"
                    @mouseover="tableData.setHoveredRows(index, item.raw.target_account.span)"
                    @mouseleave="tableData.resetHoveredRows()"
                >
                    <div class="d-flex justify-start align-center">
                        <div class="mr-2">
                            <v-icon
                                v-if="item.raw.target_account.value.icon"
                                style="min-width: 24px"
                            >
                                {{ formats.iconName(item.raw.target_account.value.icon) }}
                            </v-icon>
                        </div>

                        <div class="d-flex justify-center align-center" style="width: 100%">
                            {{ item.raw.target_account.value.name }}
                        </div>
                    </div>
                </td>

                <td
                    :class="tableData.isRowHighlighted(index, 1) && 'table-hover-background'"
                    @mouseover="tableData.setHoveredRows(index, 1)"
                    @mouseleave="tableData.resetHoveredRows()"
                >
                    <div class="d-flex flex-nowrap justify-center align-center">
                        <EditTransferDialogComponent
                            :id="item.raw.id.value"
                            @updated="emit('updated')"
                        ></EditTransferDialogComponent>

                        <DeleteDialogComponent
                            thing="transfer"
                            :url="`transfers/${item.raw.id.value}`"
                            @deleted="emit('updated')"
                        ></DeleteDialogComponent>
                    </div>
                </td>
            </tr>
        </template>

        <template v-slot:bottom></template>
    </v-data-table>

    <v-infinite-scroll
        v-if="!loading.table"
        @load="getMoreData"
    ></v-infinite-scroll>
</template>

<script setup lang="ts">
import axios from "axios"
import { watch, onMounted } from "vue"

import type { Account } from "@interfaces/Account"
import type { TransferRow } from "@interfaces/Transfer"

import EditTransferDialogComponent from "@components/transfers/EditTransferDialogComponent.vue"
import DeleteDialogComponent from "@components/common/DeleteDialogComponent.vue"

import { useCurrenciesStore } from "@stores/currencies"
import useFormats from "@composables/useFormats"
import TableDataMerger from "@classes/TableDataMerger"
import useTableSettings from "@composables/useTableSettings"
import useUpdateWithOffset from "@composables/useUpdateWithOffset"

const props = defineProps<{
    accounts: Account[]
}>()

const emit = defineEmits<{
    updated: []
}>()

const formats = useFormats()
const currencies = useCurrenciesStore()

function useTableData() {
    const tableData = new TableDataMerger<TransferRow>(
        ["date"],
        ["id", "source_value", "target_value"],
    )

    async function getData() {
        return axios
            .get(`/web-api/transfers/currency/${currencies.usedCurrency}/list`, {
                params: {
                    page: pagination.value.page,
                    ...transferQuery.value,
                },
            })
            .then(response => {
                const data = response.data

                tableData.appendData(data.items.data)

                pagination.value.page++
                pagination.value.last = data.items.last_page
                pagination.value.perPage = data.items.per_page
            })
    }

    async function getStartData() {
        loading.value.table = true
        pagination.value.page = 1
        pagination.value.last = Infinity
        tableData.resetData()

        await getData().then(() => loading.value.table = false)
    }

    async function getMoreData(state: { done: Function }) {
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

    return {getStartData, getMoreData, tableData}
}

const {filterColor, filteredData, transferHeaders, loading, options, pagination, transferQuery} = useTableSettings()
const {getStartData, getMoreData, tableData} = useTableData()
const {updateWithOffset} = useUpdateWithOffset<void>(getStartData)

watch(options, (_, oldValue) => {
    if (Object.keys(oldValue).length) {
        updateWithOffset()
    }
})

watch(filteredData, updateWithOffset as () => void, {
    deep: true,
})

onMounted(getStartData)
</script>
