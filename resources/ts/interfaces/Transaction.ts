interface Icon {
    name: string
    icon: string | null
}

interface TransactionRow extends Record<string, any> {
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
    title: string
    amount: number | string
    price: number | string
    category_id: number | null
    account_id: number | null
    currency_id: number
}

export type {
    Transaction,
    TransactionRow,
}