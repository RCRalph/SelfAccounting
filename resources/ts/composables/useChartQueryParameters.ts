import { computed } from "vue"
import { formatDate } from "@composables/useDates"

interface QueryParameters {
    start?: string
    end?: string
}

const last30DaysQuery = computed(() => {
    const result: QueryParameters = {}

    const start = new Date()
    start.setDate(start.getDate() - 31)
    result.start = formatDate(start, false)
    result.end = formatDate(new Date(), false)

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
