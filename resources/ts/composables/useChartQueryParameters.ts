import { computed } from "vue"

interface QueryParameters {
    start?: string
    end?: string
}

const last30DaysQuery = computed(() => {
    const result: QueryParameters = {}

    const start = new Date()
    start.setDate(start.getDate() - 31)
    result.start = start.toISOString().split("T")[0]

    result.end = new Date().toISOString().split("T")[0]

    return result
})

function queryWithDates(start: string, end: string): QueryParameters {
    return {
        start,
        end,
    }
}

export {
    last30DaysQuery,
    queryWithDates,
}
