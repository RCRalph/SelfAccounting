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

interface Icon {
    name: string
    icon: string | null
}

interface Transaction extends Record<string, any> {
    id: number
    date: string
    title: string
    amount: number
    price: number
    value: number
    category: Icon
    account: Icon
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
    Transaction,
    DataQuery,
}
