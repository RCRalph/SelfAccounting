import { ref } from "vue"
import type { Ref } from "vue"
import { VDataTable } from "vuetify/labs/VDataTable"
import type { Loading } from "@interfaces/App"

export default function useTableSettings() {
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
    })

    function filterColor(length: number) {
        return length ? "rgba(var(--v-theme-on-surface))" : undefined
    }

    return {filterColor, filteredData, headers, loading, options, search}
}