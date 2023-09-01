interface Category {
    id: number | null
    name: string
    icon?: string | null
    count_to_summary?: boolean
    start_date?: string | null
    end_date?: string | null
}

export type {
    Category,
}
