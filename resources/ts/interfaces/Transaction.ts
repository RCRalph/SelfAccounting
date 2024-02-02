import type { Icon } from "@interfaces/App"

interface TransactionRow {
    id: number
    date: string
    title: string
    amount: number
    price: number
    value: number
    category: Icon
    account: Icon
}

interface Transaction {
    id?: number
    date: string
    title?: string
    amount: number | string
    price: number | string
    category_id: number | null
    account_id: number | null
    currency_id: number
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
    Transaction,
    TransactionRow,
    DataQuery,
}