<template>
    <v-row
        class="align-center"
        no-gutters
    >
        <v-col cols="12" sm="5" lg="4">
            <v-text-field
                v-model="search.title"
                variant="underlined"
                append-inner-icon="mdi-magnify"
                label="Search"
                density="compact"
                :single-line="true"
                counter="64"
                :rules="[
                    Validator.search(64)
                ]"
            ></v-text-field>
        </v-col>

        <!--<v-col cols="12" sm="7" lg="8" :order="$vuetify.breakpoint.xsOnly ? 'first' : 'last'"
               class="multi-button-table-top">
            <AddTransactionsDialogComponent
                type="income"
                @added="added"
            ></AddTransactionsDialogComponent>

            <AddTransactionsDialogComponent
                type="expenses"
                @added="added"
            ></AddTransactionsDialogComponent>
        </v-col>-->
    </v-row>

    <v-data-table
        v-model:options="options"
        :headers="headers"
        :items="tableData.data.value"
        :loading="loading"
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

        <template v-slot:[`column.category`]="{column}">
            <div class="d-flex justify-center align-center">
                <v-menu
                    offset-y
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ props }: any">
                        <div class="d-flex justify-end">
                            <v-btn
                                v-bind="props"
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

        <template v-slot:[`column.account`]="{column}">
            <div class="d-flex justify-center align-center">
                <v-menu
                    offset-y
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ props }: any">
                        <div class="d-flex justify-end">
                            <v-btn
                                v-bind="props"
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
                    v-if="item.raw.title.span"
                    :rowspan="item.raw.title.span"
                    :class="tableData.isRowHighlighted(index, item.raw.title.span) && 'table-hover-background'"
                    @mouseover="tableData.setHoveredRows(index, item.raw.title.span)"
                    @mouseleave="tableData.resetHoveredRows()"
                >
                    {{ item.raw.title.value }}
                </td>

                <td
                    v-if="item.raw.amount.span"
                    :rowspan="item.raw.amount.span"
                    :class="tableData.isRowHighlighted(index, item.raw.amount.span) && 'table-hover-background'"
                    @mouseover="tableData.setHoveredRows(index, item.raw.amount.span)"
                    @mouseleave="tableData.resetHoveredRows()"
                >
                    {{ item.raw.amount.value }}
                </td>

                <td
                    v-if="item.raw.price.span"
                    :rowspan="item.raw.price.span"
                    :class="tableData.isRowHighlighted(index, item.raw.price.span) && 'table-hover-background'"
                    @mouseover="tableData.setHoveredRows(index, item.raw.price.span)"
                    @mouseleave="tableData.resetHoveredRows()"
                >
                    {{
                        formats.numberWithCurrency(
                            item.raw.price.value,
                            currencies.usedCurrencyObject.ISO,
                        )
                    }}
                </td>

                <td
                    v-if="item.raw.value.span"
                    :rowspan="item.raw.value.span"
                    :class="tableData.isRowHighlighted(index, item.raw.value.span) && 'table-hover-background'"
                    @mouseover="tableData.setHoveredRows(index, item.raw.value.span)"
                    @mouseleave="tableData.resetHoveredRows()"
                >
                    {{
                        formats.numberWithCurrency(
                            item.raw.value.value,
                            currencies.usedCurrencyObject.ISO,
                        )
                    }}
                </td>

                <td
                    v-if="item.raw.category.span"
                    :rowspan="item.raw.category.span"
                    style="max-width: 200px"
                    :class="tableData.isRowHighlighted(index, item.raw.category.span) && 'table-hover-background'"
                    @mouseover="tableData.setHoveredRows(index, item.raw.category.span)"
                    @mouseleave="tableData.resetHoveredRows()"
                >
                    <div class="d-flex justify-start align-center">
                        <div class="mr-2">
                            <v-icon
                                v-if="item.raw.category.value.icon"
                                style="min-width: 24px"
                            >
                                {{ formats.iconName(item.raw.category.value.icon) }}
                            </v-icon>
                        </div>

                        <div class="d-flex justify-center align-center" style="width: 100%">
                            {{ item.raw.category.value.name }}
                        </div>
                    </div>
                </td>

                <td
                    v-if="item.raw.account.span"
                    :rowspan="item.raw.account.span"
                    style="max-width: 200px"
                    :class="tableData.isRowHighlighted(index, item.raw.account.span) && 'table-hover-background'"
                    @mouseover="tableData.setHoveredRows(index, item.raw.account.span)"
                    @mouseleave="tableData.resetHoveredRows()"
                >
                    <div class="d-flex justify-start align-center">
                        <div class="mr-2">
                            <v-icon
                                v-if="item.raw.account.value.icon"
                                style="min-width: 24px"
                            >
                                {{ formats.iconName(item.raw.account.value.icon) }}
                            </v-icon>
                        </div>

                        <div class="d-flex justify-center align-center" style="width: 100%">
                            {{ item.raw.account.value.name }}
                        </div>
                    </div>
                </td>

                <td
                    :class="tableData.isRowHighlighted(index, 1) && 'table-hover-background'"
                    @mouseover="tableData.setHoveredRows(index, 1)"
                    @mouseleave="tableData.resetHoveredRows()"
                >
                    <div class="d-flex flex-nowrap justify-center align-center">
                        <EditTransactionsDialogComponent
                            :type="item.raw.value.value < 0 ? 'expenses' : 'income'"
                            :id="item.raw.id.value"
                            @updated="emit('updated')"
                        ></EditTransactionsDialogComponent>

                        <DeleteDialogComponent
                            :thing="item.raw.value.value < 0 ? 'expense' : 'income'"
                            :url="`${item.raw.value.value < 0 ? 'expenses' : 'income'}/${item.raw.id.value}`"
                            @deleted="emit('updated')"
                        ></DeleteDialogComponent>
                    </div>
                </td>
            </tr>
        </template>

        <template v-slot:bottom></template>
    </v-data-table>

    <v-infinite-scroll
        v-if="!loading"
        @load="getMoreData"
    ></v-infinite-scroll>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref, computed, onMounted, watch } from "vue"
import type { Ref } from "vue"
import type { VDataTable } from "vuetify/labs/VDataTable"

import type { DataQuery } from "@interfaces/Dashboard"
import type { TransactionRow } from "@interfaces/Transaction"
import type { Account } from "@interfaces/Account"
import type { Category } from "@interfaces/Category"

import EditTransactionsDialogComponent from "@components/transactions/EditTransactionsDialogComponent.vue"
import DeleteDialogComponent from "@components/common/DeleteDialogComponent.vue"

import { useCurrenciesStore } from "@stores/currencies"
import TableDataMerger from "@classes/TableDataMerger"
import Validator from "@classes/Validator"
import useFormats from "@composables/useFormats"

const props = defineProps<{
    accounts: Account[]
    categories: Category[]
}>()

const emit = defineEmits<{
    updated: []
}>()

const currencies = useCurrenciesStore()
const formats = useFormats()

function useTableSettings() {
    const headers: VDataTable["headers"] = [
        {title: "Date", key: "date", align: "center"},
        {title: "Title", key: "title", align: "center"},
        {title: "Amount", key: "amount", align: "center"},
        {title: "Price", key: "price", align: "center"},
        {title: "Value", key: "value", align: "center"},
        {title: "Category", key: "category", align: "center", sortable: false},
        {title: "Account", key: "account", align: "center", sortable: false},
        {title: "Actions", key: "", align: "center", sortable: false},
    ]

    const loading = ref(true)

    const options: Ref<any> = ref({})

    const filteredData = ref({
        dates: [] as string[],
        categories: [] as number[],
        accounts: [] as number[],
    })

    function filterColor(length: number) {
        return length ? "rgba(var(--v-theme-on-surface))" : undefined
    }

    return {filterColor, filteredData, headers, loading, options}
}

function useTableData() {
    const search = ref({
        title: "",
    })

    const tableData = new TableDataMerger<TransactionRow>(
        ["date"],
        ["id", "value"],
    )

    const pagination = ref({
        page: 1,
        last: Infinity,
        perPage: Infinity,
    })

    const query = computed(() => {
        let result: DataQuery = {}

        if (Object.keys(options.value).length) {
            if (search.value.title.length) {
                result.title = search.value.title
            }

            if (options.value.sortBy.length) {
                result.orderFields = []
                result.orderDirections = []

                for (let item of options.value.sortBy) {
                    result.orderFields.push(item.key)
                    result.orderDirections.push(item.order)
                }
            }

            if (filteredData.value.accounts.length) {
                result.accounts = filteredData.value.accounts
            }

            if (filteredData.value.categories.length) {
                result.categories = filteredData.value.categories
            }

            if (filteredData.value.dates.length) {
                result.dates = filteredData.value.dates
            }
        }

        return result
    })

    async function getData() {
        return axios
            .get(`/web-api/dashboard/${currencies.usedCurrency}/recent-transactions`, {
                params: {
                    page: pagination.value.page,
                    ...query.value,
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
        loading.value = true
        pagination.value.page = 1
        pagination.value.last = Infinity
        tableData.resetData()

        await getData().then(() => loading.value = false)
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

    return {getStartData, getMoreData, tableData, search}
}

function useUpdateWithOffset() {
    const timeOffset = 250
    const lastChange: Ref<Date> = ref(new Date())

    function updateWithOffset() {
        lastChange.value = new Date()

        setTimeout(
            () => {
                if (new Date().getTime() - lastChange.value.getTime() >= timeOffset) {
                    getStartData()
                }
            },
            timeOffset,
        )
    }

    return {updateWithOffset}
}

const {filterColor, filteredData, headers, loading, options} = useTableSettings()
const {getStartData, getMoreData, tableData, search} = useTableData()
const {updateWithOffset} = useUpdateWithOffset()

watch(options, (_, oldValue) => {
    if (Object.keys(oldValue).length) {
        updateWithOffset()
    }
})

watch(search, updateWithOffset, {
    deep: true,
})

watch(filteredData, updateWithOffset, {
    deep: true,
})

onMounted(getStartData)
</script>
