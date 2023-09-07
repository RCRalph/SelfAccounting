import type { Icon } from "@interfaces/App"

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
    TransferRow,
    DataQuery,
}