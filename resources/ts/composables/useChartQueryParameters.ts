import {computed} from "vue";

interface QueryParameters {
    start?: string
    end?: string
}

export default function useChartQueryParameters() {
    const last30DaysQuery = computed(() => {
        const result: QueryParameters = {}

        const start = new Date();
        start.setDate(start.getDate() - 31)
        result.start = start.toISOString().split("T")[0]

        result.end = new Date().toISOString().split("T")[0]

        return result
    })

    return {last30DaysQuery}
}
