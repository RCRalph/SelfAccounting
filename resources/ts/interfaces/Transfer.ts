import type { Icon } from "@interfaces/App"

interface Account {
    account_id?: number
    currency_id?: number
    value: string | number
}

interface Transfer {
    id?: number
    date: string
    source: Account
    target: Account
}

interface TransferRow extends Record<string, any> {
    id: number
    date: string
    source_value: number
    source_account: Icon
    target_value: number
    target_account: Icon
}

interface DataQuery {
    orderFields?: string[]
    orderDirections?: ("asc" | "desc")[]
    dates?: string[]
    source_accounts?: number[]
    target_accounts?: number[]
}

export type {
    Transfer,
    TransferRow,
    DataQuery,
}