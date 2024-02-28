import type { AccountRestoreData, CategoryRestoreData } from "@interfaces/Backup"
import { arrayHasUniqueEntries } from "@composables/useArrayUtils"
import { useCurrenciesStore } from "@stores/currencies"
import Validator from "@classes/Validator"

function useBackupValidation() {
    const currencies = useCurrenciesStore()

    function validateCategories(categories: unknown) {
        const isValid = Array.isArray(categories) && categories.every(
            item => [
                typeof currencies.findByISO(item.currency) != "undefined",
                Validator.title("Icon", 64, true)(item.icon) === true,
                Validator.title("Name", 32)(item.name) === true,
                typeof item.used_in_income == "boolean",
                typeof item.used_in_expenses == "boolean",
                typeof item.count_to_summary == "boolean",
                typeof item.show_on_charts == "boolean",
                Validator.date(true)(item.start_date) === true,
                Validator.date(true)(item.end_date) === true,
                !item.start_date || !item.end_date || item.start_date <= item.end_date,
            ].every(condition => condition),
        )

        if (!isValid) throw new Error("Invalid categories")
    }

    function validateAccounts(accounts: unknown) {
        const isValid = Array.isArray(accounts) && accounts.every(
            item => [
                typeof currencies.findByISO(item.currency) != "undefined",
                Validator.title("Icon", 64, true)(item.icon) === true,
                Validator.title("Name", 32)(item.name) === true,
                typeof item.used_in_income == "boolean",
                typeof item.used_in_expenses == "boolean",
                typeof item.count_to_summary == "boolean",
                typeof item.show_on_charts == "boolean",
                Validator.date(false)(item.start_date) === true,
                Validator.price(false, true, true)(item.start_balance) === true,
            ].every(condition => condition),
        )

        if (!isValid) throw new Error("Invalid accounts")
    }

    function validateTransactions(
        transactions: unknown,
        categories: Map<number, CategoryRestoreData>,
        accounts: Map<number, AccountRestoreData>,
    ) {
        const isValid = Array.isArray(transactions) && transactions.every(
            item => [
                Validator.date(
                    false,
                    new Date(accounts.get(item.account)?.start_date ?? "1970-01-01"),
                )(item.date),
                Validator.title("Title", 64)(item.title) === true,
                Validator.amount()(item.amount) === true,
                Validator.price()(item.price) === true,
                !item.category_id || (categories.get(item.category_id)?.currency === item.currency),
                !item.account_id || (accounts.get(item.account_id)?.currency === item.currency),
                typeof currencies.findByISO(item.currency) != "undefined",
            ].every(condition => condition),
        )

        if (!isValid) throw new Error("Invalid transactions")
    }

    function validateTransfers(
        transfers: unknown,
        accounts: Map<number, AccountRestoreData>,
    ) {
        const isValid = Array.isArray(transfers) && transfers.every(
            item => [
                Validator.date(false, new Date(
                    Math.min(
                        accounts.get(item.source_account_id)?.start_date.getTime() ?? Infinity,
                        accounts.get(item.target_account_id)?.start_date.getTime() ?? Infinity,
                    ),
                ))(item.date),
                accounts.has(item.source_account_id),
                accounts.has(item.target_account_id),
                item.source_account_id != item.target_account_id,
                Validator.price()(item.source_value) === true,
                Validator.price()(item.target_value) === true,
            ].every(condition => condition),
        )

        if (!isValid) throw new Error("Invalid transfers")
    }

    function validateCash(cash: unknown, accounts: Map<number, AccountRestoreData>) {
        const validateCashAmounts = (amounts: unknown) => Array.isArray(amounts) &&
            arrayHasUniqueEntries(amounts, ["currency", "value"]) &&
            amounts.every(
                item => [
                    typeof currencies.findByISO(item.currency) != "undefined",
                    Validator.cash()(item.amount) === true,
                    item.amount > 0,
                    !isNaN(Number(item.value)),
                ].every(condition => condition),
            )

        const validateCashAccounts = (cashAccounts: unknown) => Array.isArray(cashAccounts) &&
            arrayHasUniqueEntries(cashAccounts, ["currency"]) &&
            cashAccounts.every(
                item => [
                    typeof currencies.findByISO(item.currency) != "undefined",
                    accounts.get(item.account_id)?.currency == item.currency,
                ].every(condition => condition),
            )

        const isValid = typeof cash == "object" && cash != null &&
            "cash" in cash && validateCashAmounts(cash.cash) &&
            "accounts" in cash && validateCashAccounts(cash.accounts)

        if (!isValid) throw new Error("Invalid cash")
    }

    function validateReports(
        reports: unknown,
        categories: Map<number, CategoryRestoreData>,
        accounts: Map<number, AccountRestoreData>,
    ) {
        const validateReportQueries = (
            queries: unknown,
            categories: Map<number, CategoryRestoreData>,
            accounts: Map<number, AccountRestoreData>,
        ) => Array.isArray(queries) && queries.every(
            item => [
                ["income", "expenses"].includes(item.query_data),

                Validator.date(true)(item.min_date) === true,
                Validator.date(true)(item.max_date) === true,
                !item.min_date || !item.max_date || item.min_date <= item.max_date,

                Validator.title("Title", 64, true)(item.title) === true,

                Validator.amount(true, true)(item.min_amount) === true,
                Validator.amount(true, true)(item.max_amount) === true,
                isNaN(Number(item.min_amount)) || isNaN(Number(item.max_amount)) ||
                Number(item.min_amount) <= Number(item.max_amount),

                Validator.price(true, true)(item.min_price) === true,
                Validator.price(true, true)(item.max_price) === true,
                isNaN(Number(item.min_price)) || isNaN(Number(item.max_price)) ||
                Number(item.min_price) <= Number(item.max_price),

                item.currency !== null ?
                    (
                        (item.category_id === null || categories.get(item.category_id)?.currency === item.currency) &&
                        (item.account_id === null || accounts.get(item.account_id)?.currency === item.currency)
                    ) : (item.category_id === null && item.account_id === null),
            ].every(condition => condition),
        )

        const validateReportAdditionalTransactions = (
            transactions: unknown,
            categories: Map<number, CategoryRestoreData>,
            accounts: Map<number, AccountRestoreData>,
        ) => Array.isArray(transactions) && transactions.every(
            item => [
                Validator.date()(item.date) === true,
                Validator.title("Title", 64)(item.title) === true,
                Validator.amount()(item.amount) === true,
                Validator.price(false, true)(item.price) === true,

                item.currency !== null &&
                (item.category_id === null || categories.get(item.category_id)?.currency === item.currency) &&
                (item.account_id === null || accounts.get(item.account_id)?.currency === item.currency),
            ].every(condition => condition),
        )

        const validateReportUsers = (users: unknown) => Array.isArray(users) &&
            new Set(users).size == users.length &&
            users.every(item => Validator.email()(item))

        const isValid = typeof reports == "object" && reports != null &&
            "reports" in reports && Array.isArray(reports.reports) && reports.reports.every(
                item => [
                    Validator.title("Title", 64)(item.title),
                    typeof item.income_addition == "boolean",
                    typeof item.sort_dates_desc == "boolean",
                    typeof item.calculate_sum == "boolean",
                    Number.isInteger(item.show_columns),
                    0 <= item.show_columns && item.show_columns < 128,
                    validateReportQueries(item.queries, categories, accounts),
                    validateReportAdditionalTransactions(item.additionalEntries, categories, accounts),
                    validateReportUsers(item.users),
                ].every(condition => condition),
            )

        if (!isValid) throw new Error("Invalid report")
    }

    return {
        validateCategories,
        validateAccounts,
        validateTransactions,
        validateTransfers,
        validateCash,
        validateReports,
    }
}

export {
    useBackupValidation,
}