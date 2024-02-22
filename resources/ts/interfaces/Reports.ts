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

export type {
    OwnedReport,
    OwnedReportsDataQuery,
    ReportOwners,
    SharedReport,
    SharedReportsDataQuery,
}