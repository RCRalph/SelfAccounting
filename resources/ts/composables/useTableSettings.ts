import { ref } from "vue"
import type { Loading } from "@interfaces/App"

export default function useTableSettings() {
    const loading = ref<Loading>({
        table: false,
    })

    const pagination = ref({
        page: 1,
        last: Infinity,
        perPage: Infinity,
    })

    function filterColor(length: number): string | undefined {
        return length ? "rgba(var(--v-theme-on-surface))" : undefined
    }

    return {
        filterColor,
        loading,
        pagination,
    }
}