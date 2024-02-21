import { computed, ref } from "vue"
import type { Loading } from "@interfaces/App"
import type { DataQuery as TransactionDataQuery } from "@interfaces/Transaction"
import type { DataQuery as TransferDataQuery } from "@interfaces/Transfer"
import type { OwnedDataQuery as OwnedReportsDataQuery } from "@interfaces/Reports"

export default function useTableSettings() {
    const loading = ref<Loading>({
        table: false,
    })

    // https://vuetifyjs.com/en/api/v-data-table/#events-update:options
    const options = ref<any>({})

    const search = ref({
        title: "",
    })

    const filteredData = ref({
        dates: [] as string[],
        categories: [] as number[],
        accounts: [] as number[],
        source_accounts: [] as number[],
        target_accounts: [] as number[],
    })

    const pagination = ref({
        page: 1,
        last: Infinity,
        perPage: Infinity,
    })

    const transactionQuery = computed(() => {
        const result: TransactionDataQuery = {}

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

    const transferQuery = computed(() => {
        const result: TransferDataQuery = {}

        if (Object.keys(options.value).length) {
            if (options.value.sortBy.length) {
                result.orderFields = []
                result.orderDirections = []

                for (let item of options.value.sortBy) {
                    result.orderFields.push(item.key)
                    result.orderDirections.push(item.order)
                }
            }

            if (filteredData.value.source_accounts.length) {
                result.source_accounts = filteredData.value.source_accounts
            }

            if (filteredData.value.target_accounts.length) {
                result.target_accounts = filteredData.value.target_accounts
            }

            if (filteredData.value.dates.length) {
                result.dates = filteredData.value.dates
            }
        }

        return result
    })

    const ownedReportsQuery = computed(() => {
        const result: OwnedReportsDataQuery = {
            page: options.value.page,
            items: options.value.itemsPerPage,
        }

        if (search.value.title) {
            result.search = search.value.title
        }

        if (options.value.sortBy?.length) {
            result.orderFields = options.value.sortBy.map((item: any) => item.key)
            result.orderDirections = options.value.sortBy.map((item: any) => item.order)
        }

        return result
    })

    function filterColor(length: number): string | undefined {
        return length ? "rgba(var(--v-theme-on-surface))" : undefined
    }

    return {
        filterColor,
        filteredData,
        loading,
        options,
        pagination,
        search,
        transactionQuery,
        transferQuery,
        ownedReportsQuery,
    }
}