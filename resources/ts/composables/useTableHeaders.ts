import type { VDataTable } from "vuetify/components"

interface HeaderData {
    title: string,
    key: string,
    align?: string,
    sortable?: boolean,
}

interface TableHeadersParameters {
    excludedColumns?: Set<string>
    appendActions?: boolean
    prependCurrency?: boolean,
    disableSort?: boolean
}

const transactions: HeaderData[] = [
    {title: "Date", key: "date", align: "center"},
    {title: "Title", key: "title", align: "center"},
    {title: "Amount", key: "amount", align: "center"},
    {title: "Price", key: "price", align: "center"},
    {title: "Value", key: "value", align: "center"},
    {title: "Category", key: "category", align: "center", sortable: false},
    {title: "Account", key: "account", align: "center", sortable: false},
]

const transfers: HeaderData[] = [
    {title: "Date", key: "date", align: "center"},
    {title: "Source value", key: "source_value", align: "center"},
    {title: "Source account", key: "source_account", align: "center", sortable: false},
    {title: "Target value", key: "target_value", align: "center"},
    {title: "Target account", key: "target_account", align: "center", sortable: false},
]

const categories: HeaderData[] = [
    {title: "Icon", key: "icon", align: "center", sortable: false},
    {title: "Name", key: "name", align: "center", sortable: false},
    {title: "Show in income", key: "used_in_income", align: "center", sortable: false},
    {title: "Show in expenses", key: "used_in_expenses", align: "center", sortable: false},
    {title: "Show on charts", key: "show_on_charts", align: "center", sortable: false},
    {title: "Count to summary", key: "count_to_summary", align: "center", sortable: false},
    {title: "Start date", key: "start_date", align: "center", sortable: false},
    {title: "End date", key: "end_date", align: "center", sortable: false},
]

const accounts: HeaderData[] = [
    {title: "Icon", key: "icon", align: "center", sortable: false},
    {title: "Name", key: "name", align: "center", sortable: false},
    {title: "Show in income", key: "used_in_income", align: "center", sortable: false},
    {title: "Show in expenses", key: "used_in_expenses", align: "center", sortable: false},
    {title: "Show on charts", key: "show_on_charts", align: "center", sortable: false},
    {title: "Count to summary", key: "count_to_summary", align: "center", sortable: false},
    {title: "Start date", key: "start_date", align: "center", sortable: false},
    {title: "Start balance", key: "start_balance", align: "center", sortable: false},
]

const ownedReports: HeaderData[] = [
    {title: "ID", key: "id", align: "center", sortable: true},
    {title: "Title", key: "title", align: "center", sortable: true},
]

const sharedReports: HeaderData[] = [
    ...ownedReports,
    {title: "Owner", key: "owner", align: "center", sortable: false},
]

export default function useTableHeaders() {
    function tableHeaders(headers: HeaderData[], parameters: TableHeadersParameters) {
        headers = headers.filter(item => !parameters.excludedColumns?.has(item.key))

        if (parameters.disableSort) {
            headers = headers.map(item => ({
                ...item,
                sortable: false,
            }))
        }

        if (parameters.prependCurrency) {
            headers.unshift({
                title: "Currency",
                key: "currency",
                align: "center",
                sortable: false,
            })
        }

        if (parameters.appendActions) {
            headers.push({
                title: "Actions",
                key: "actions",
                align: "center",
                sortable: false,
            })
        }

        return headers as VDataTable["headers"]
    }

    function transactionHeaders(parameters: TableHeadersParameters = {}): VDataTable["headers"] {
        return tableHeaders(transactions, parameters)
    }

    function transferHeaders(parameters: TableHeadersParameters = {}): VDataTable["headers"] {
        return tableHeaders(transfers, parameters)
    }

    function categoryHeaders(parameters: TableHeadersParameters = {}): VDataTable["headers"] {
        return tableHeaders(categories, parameters)
    }

    function accountHeaders(parameters: TableHeadersParameters = {}): VDataTable["headers"] {
        return tableHeaders(accounts, parameters)
    }

    function ownedReportsHeaders(parameters: TableHeadersParameters = {}): VDataTable["headers"] {
        return tableHeaders(ownedReports, parameters)
    }

    function sharedReportsHeaders(parameters: TableHeadersParameters = {}): VDataTable["headers"] {
        return tableHeaders(sharedReports, parameters)
    }

    return {
        transactionHeaders,
        transferHeaders,
        categoryHeaders,
        accountHeaders,
        ownedReportsHeaders,
        sharedReportsHeaders,
    }
}