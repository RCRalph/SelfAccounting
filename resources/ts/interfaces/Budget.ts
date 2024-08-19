interface BudgetInformation {
    id?: number
    title: string
    start_date: string
    end_date: string
}

interface BudgetEntry {
    id?: number,
    category_id: number
    transaction_type: "income" | "expenses"
    value: number
}

interface BudgetEntriesGrouped {
    income: Record<number, BudgetEntry>,
    expenses: Record<number, BudgetEntry>,
}

export type {
    BudgetEntry,
    BudgetInformation,
    BudgetEntriesGrouped,
}