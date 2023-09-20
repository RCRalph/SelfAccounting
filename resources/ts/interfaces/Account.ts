interface Account {
    id?: number
    name?: string
    icon: string | null
    used_in_income: boolean
    used_in_expenses: boolean
    show_on_charts: boolean
    count_to_summary: boolean
    start_date: string
    start_balance: number
}

interface AccountData {
    id: number | null
    name: string
    icon?: string | null
    start_date: string | null
    start_balance?: number
}


export type {
    Account,
    AccountData,
}
