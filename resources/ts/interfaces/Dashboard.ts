interface CurrentBalance {
    account_id?: number
    category_id?: number
    balance: number
    icon: string
    name: string
}

interface Chart {
    id: number
    name: string
    type: string
}

interface DataQuery {
    title?: string
    orderFields?: string[]
    orderDirections?: ("asc" | "desc")[]
    dates?: string[]
    categories?: number[]
    accounts?: number[]
}

export type {
    Chart,
    CurrentBalance,
    DataQuery,
}
