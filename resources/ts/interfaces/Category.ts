interface Category {
    id: number | null
    name: string
    icon?: string | null
    used_in_income?: boolean
    used_in_expenses?: boolean
    show_on_charts?: boolean
    count_to_summary?: boolean
    start_date?: string | null
    end_date?: string | null
}

export type {
    Category,
}
