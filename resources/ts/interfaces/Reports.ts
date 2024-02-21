interface OwnedDataQuery {
    page: number
    items: number
    search?: string
    orderFields?: string[]
    orderDirections?: ("asc" | "desc")[]
}

export type {
    OwnedDataQuery,
}