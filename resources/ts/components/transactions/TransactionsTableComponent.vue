<template>
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
            <AddTransactionsDialogComponent
                :type="props.type"
                @added="getStartData"
            ></AddTransactionsDialogComponent>
        </v-col>
    </v-row>

    <v-data-table
        v-model:options="options"
        :headers="transactionHeaders({
            appendActions: true
        })"
        :items="tableData.data.value"
        :loading="loading.table"
        :items-per-page="-1"
        class="table-bordered"
        density="comfortable"
        multi-sort
    >
        <template v-slot:[`header.date`]="{column, getSortIcon, isSorted, sortBy}">
            <div class="d-flex justify-center align-center">
                <v-menu
                    offset-y
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ props: buttonProps }">
                        <div class="d-flex justify-end">
                            <v-btn
                                v-bind="buttonProps"
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

        <template v-slot:[`header.category`]="{column}">
            <div class="d-flex justify-center align-center">
                <v-menu
                    offset-y
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ props: buttonProps }">
                        <div class="d-flex justify-end">
                            <v-btn
                                v-bind="buttonProps"
                                :color="filterColor(filteredData.categories.length)"
                                :style="filteredData.categories.length && 'opacity: 1'"
                                icon="mdi-filter"
                                size="x-small"
                                variant="plain"
                            ></v-btn>
                        </div>
                    </template>

                    <v-card class="filtered-list">
                        <v-card-text class="py-0 px-2">
                            <v-checkbox
                                v-for="(item, i) in props.categories"
                                v-model="filteredData.categories"
                                :value="item.id"
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

        <template v-slot:[`header.account`]="{column}">
            <div class="d-flex justify-center align-center">
                <v-menu
                    offset-y
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ props: buttonProps }">
                        <div class="d-flex justify-end">
                            <v-btn
                                v-bind="buttonProps"
                                :color="filterColor(filteredData.accounts.length)"
                                :style="filteredData.accounts.length && 'opacity: 1'"
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
                                v-model="filteredData.accounts"
                                :value="item.id"
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

        <template v-slot:item="{item, index}: {item: TransactionRow, index: number}">
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
                            item.price,
                            currencies.usedCurrencyObject.ISO,
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
                            item.value,
                            currencies.usedCurrencyObject.ISO,
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
                                v-if="item.category.icon"
                                style="min-width: 24px"
                            >
                                {{ formats.iconName(item.category.icon) }}
                            </v-icon>
                        </div>

                        <div class="d-flex justify-center align-center" style="width: 100%">
                            {{ item.category.name }}
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
                                v-if="item.account.icon"
                                style="min-width: 24px"
                            >
                                {{ formats.iconName(item.account.icon) }}
                            </v-icon>
                        </div>

                        <div class="d-flex justify-center align-center" style="width: 100%">
                            {{ item.account.name }}
                        </div>
                    </div>
                </td>

                <td
                    :class="tableData.isRowHighlighted(index, 1) && 'table-hover-background'"
                    @mouseover="tableData.setHoveredRows(index, 1)"
                    @mouseleave="tableData.resetHoveredRows()"
                >
                    <div class="d-flex flex-nowrap justify-center align-center">
                        <EditTransactionDialogComponent
                            :type="props.type"
                            :id="item.id"
                            @updated="emit('updated')"
                        ></EditTransactionDialogComponent>

                        <DeleteDialogComponent
                            :thing="typeSingular"
                            :url="`${props.type}/${item.id}`"
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
import { computed, watch, onMounted } from "vue"

import type { TransactionRow } from "@interfaces/Transaction"
import type { AccountData } from "@interfaces/Account"
import type { CategoryData } from "@interfaces/Category"

import AddTransactionsDialogComponent from "@components/transactions/AddTransactionsDialogComponent.vue"
import EditTransactionDialogComponent from "@components/transactions/EditTransactionDialogComponent.vue"

import { useCurrenciesStore } from "@stores/currencies"
import useFormats from "@composables/useFormats"
import useTableSettings from "@composables/useTableSettings"
import useUpdateWithOffset from "@composables/useUpdateWithOffset"
import TableDataMerger from "@classes/TableDataMerger"
import Validator from "@classes/Validator"

const props = defineProps<{
    type: "income" | "expenses"
    accounts: AccountData[],
    categories: CategoryData[]
}>()

const emit = defineEmits<{
    updated: []
}>()

const formats = useFormats()
const currencies = useCurrenciesStore()

function useTableData() {
    const typeSingular = computed(() => props.type == "expenses" ? "expense" : "income")

    const tableData = new TableDataMerger<TransactionRow>(
        ["date"],
        ["id", "value"],
    )

    async function getData() {
        return axios
            .get(`/web-api/${props.type}/currency/${currencies.usedCurrency}/list`, {
                params: {
                    page: pagination.value.page,
                    ...transactionQuery.value,
                },
            })
            .then(response => {
                const data: { items: Record<string, any> } = response.data

                tableData.append(data.items.data)

                pagination.value.page++
                pagination.value.last = data.items.last_page
                pagination.value.perPage = data.items.per_page
            })
    }

    async function getStartData() {
        loading.value.table = true
        pagination.value.page = 1
        pagination.value.last = Infinity
        tableData.reset()

        await getData().then(() => loading.value.table = false)
    }

    async function getMoreData(state: {
        done: Function
    }) {
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

    return {getStartData, getMoreData, tableData, typeSingular}
}

const {
    filterColor,
    filteredData,
    transactionHeaders,
    loading,
    options,
    search,
    pagination,
    transactionQuery,
} = useTableSettings()
const {getStartData, getMoreData, tableData, typeSingular} = useTableData()
const {updateWithOffset} = useUpdateWithOffset(getStartData)

watch(options, (_, oldValue) => {
    if (Object.keys(oldValue).length) {
        updateWithOffset()
    }
})

watch(search, updateWithOffset as () => void, {
    deep: true,
})

watch(filteredData, updateWithOffset as () => void, {
    deep: true,
})

onMounted(getStartData)
</script>
