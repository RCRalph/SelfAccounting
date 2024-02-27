import { computed, ref, type Ref } from "vue"
import { formatDate } from "@composables/useDates"

interface FilteredData {
    dates: string[]
    categories: number[]
    accounts: number[]
    source_accounts: number[]
    target_accounts: number[]
    owners: number[]
}

interface Search {
    title: string
}

interface QueryData extends FilteredData, Search {
    page: number,
    items: number,
    title: string
    orderFields: string[]
    orderDirections: ("asc" | "desc")[]
}

export default function useTableQuery(queryKeys: (keyof QueryData)[]) {
    // https://vuetifyjs.com/en/api/v-data-table/#events-update:options
    const options = ref<any>({})

    const search = ref<Search>({
        title: "",
    })

    const filteredData: Ref<FilteredData> = ref({
        dates: [],
        categories: [],
        accounts: [],
        source_accounts: [],
        target_accounts: [],
        owners: [],
    })

    const queryData = computed(() => {
        if (!Object.keys(options.value).length) return {}

        const result: Record<string, unknown> = {
            page: options.value.page,
            items: options.value.itemsPerPage,
        }

        if (search.value.title.length) {
            result.title = search.value.title
        }

        if (options.value.sortBy?.length) {
            result.orderFields = options.value.sortBy.map((item: any) => item.key)
            result.orderDirections = options.value.sortBy.map((item: any) => item.order)
        }

        if (filteredData.value.dates.length) {
            result.dates = filteredData.value.dates
                .map(item => new Date(item))
                .map(item => formatDate(item, false))
        }

        if (filteredData.value.categories.length) {
            result.categories = filteredData.value.categories
        }

        if (filteredData.value.accounts.length) {
            result.accounts = filteredData.value.accounts
        }

        if (filteredData.value.source_accounts.length) {
            result.source_accounts = filteredData.value.source_accounts
        }

        if (filteredData.value.target_accounts.length) {
            result.target_accounts = filteredData.value.target_accounts
        }

        if (filteredData.value.owners.length) {
            result.owners = filteredData.value.owners
        }

        return result
    })

    const query = computed(() => {
        const result: Record<string, unknown> = {}

        for (const key of queryKeys) {
            if (queryData.value[key]) {
                result[key] = queryData.value[key]
            }
        }

        return result
    })

    return {options, search, filteredData, query}
}