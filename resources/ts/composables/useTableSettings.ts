import { computed, ref } from "vue"
import type { Ref } from "vue"
import { VDataTable } from "vuetify/labs/VDataTable"
import type { Loading } from "@interfaces/App"
import type { DataQuery as TransactionDataQuery } from "@interfaces/Transaction"
import type { DataQuery as TransferDataQuery } from "@interfaces/Transfer"


export default function useTableSettings() {
    const transactions = [
        {title: "Date", key: "date", align: "center"},
        {title: "Title", key: "title", align: "center"},
        {title: "Amount", key: "amount", align: "center"},
        {title: "Price", key: "price", align: "center"},
        {title: "Value", key: "value", align: "center"},
        {title: "Category", key: "category", align: "center", sortable: false},
        {title: "Account", key: "account", align: "center", sortable: false},
        {title: "Actions", key: "", align: "center", sortable: false},
    ]

    function transactionHeaders(columns: string[] | true = true): VDataTable["headers"] {
        return (
            columns === true ?
                transactions :
                transactions.filter(item => columns.includes(item.key))
        ) as VDataTable["headers"]
    }

    const transfers = [
        {title: "Date", key: "date", align: "center"},
        {title: "Source value", key: "source_value", align: "center"},
        {title: "Source account", key: "source_account", align: "center", sortable: false},
        {title: "Target value", key: "target_value", align: "center"},
        {title: "Target account", key: "target_account", align: "center", sortable: false},
        {title: "Actions", key: "", align: "center", value: "", sortable: false},
    ]

    function transferHeaders(columns: string[] | true = true): VDataTable["headers"] {
        return (
            columns === true ?
                transfers :
                transfers.filter(item => columns.includes(item.key))
        ) as VDataTable["headers"]
    }


    const loading: Ref<Loading> = ref({
        table: false,
    })

    const options: Ref<any> = ref({})

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
        transactionHeaders,
        transactionQuery,
        transferHeaders,
        transferQuery,
    }
}