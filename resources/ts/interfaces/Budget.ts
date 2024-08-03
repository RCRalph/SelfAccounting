interface BudgetInformation {
    id?: number
    title: string
    start_date: string
    end_date: string
}

interface BudgetEntry {
    id: number,
    category_id: number
    transaction_type: "income" | "expenses"
    value: number
}

interface Budget {
    id?: number
    title: string
    start_date: string
    end_date: string
    budget_entries: BudgetEntry[]
}

export type {
    Budget,
    BudgetEntry,
    BudgetInformation,
}