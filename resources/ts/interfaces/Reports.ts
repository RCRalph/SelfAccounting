interface OwnedReport {
    id: number
    title: string
}

interface OwnedReportsDataQuery {
    page: number
    items: number
    search?: string
    orderFields?: string[]
    orderDirections?: ("asc" | "desc")[]
}

export type {
    OwnedDataQuery,
    OwnedReport,
    OwnedReportsDataQuery,
}