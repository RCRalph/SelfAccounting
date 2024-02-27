import type { Icon } from "@interfaces/App"

interface Columns {
    date: boolean
    title: boolean
    amount: boolean
    price: boolean
    value: boolean
    category_id: boolean
    account_id: boolean
}

interface ReportQuery {
    id?: number
    query_data: "income" | "expenses"
    min_date: string | null
    max_date: string | null
    title: string | null
    min_amount: number | string | null
    max_amount: number | string | null
    min_price: number | string | null
    max_price: number | string | null
    currency_id: number | null
    category_id: number | null
    account_id: number | null
}

interface ReportTransaction {
    id?: number
    date: string
    title?: string
    amount: number | string
    price: number | string
    category_id: number | null
    account_id: number | null
    currency_id: number
}

interface ReportUser {
    username: string
    email: string,
    profile_picture_link: string
}

interface Report {
    title: string
    calculate_sum: boolean
    income_addition: boolean
    sort_dates_desc: boolean
    columns: Columns
    queries: ReportQuery[]
    additionalTransactions: ReportTransaction[]
    users: ReportUser[]
}

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

interface ReportOwners {
    id: number
    username: string
}

interface SharedReport {
    id: number
    title: string
    owner: string
}

interface SharedReportsDataQuery {
    page: number
    items: number
    search?: string
    owners?: number[]
    orderFields?: string[]
    orderDirections?: ("asc" | "desc")[]
}

interface ReportTransactionRow {
    id?: number
    date?: string
    title?: string
    amount?: number
    price?: number
    value?: number
    category?: Icon
    account?: Icon
    currency_id: number
}

interface ReportInformation {
    title: string,
    owner: {
        username: string,
        profile_picture_link: string,
    },
    sum: Record<number, number>
}

export type {
    Report,
    ReportQuery,
    ReportTransaction,
    ReportUser,
    OwnedReport,
    OwnedReportsDataQuery,
    ReportOwners,
    SharedReport,
    SharedReportsDataQuery,
    ReportTransactionRow,
    ReportInformation,
}