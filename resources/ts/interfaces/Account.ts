interface Account {
    id: number | null
    name: string
    icon?: string | null
    start_date: string | null
    start_balance?: number
}

export type {
    Account,
}
