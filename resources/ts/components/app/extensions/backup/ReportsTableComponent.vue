<template>
    <CardTitleWithButtons title="Reports"></CardTitleWithButtons>

    <v-data-table
        :headers="reportHeaders"
        :items="props.items.reports"
        show-expand
        single-expand
    >
        <template v-slot:[`item.income_addition`]="{ item }">
            <v-checkbox
                v-model="item.income_addition"
                direction="vertical"
                class="d-flex justify-center"
                false-icon="mdi-close"
                readonly
                hide-details
            ></v-checkbox>
        </template>

        <template v-slot:[`item.sort_dates_desc`]="{ item }">
            <v-checkbox
                v-model="item.sort_dates_desc"
                direction="vertical"
                class="d-flex justify-center"
                false-icon="mdi-close"
                readonly
                hide-details
            ></v-checkbox>
        </template>

        <template v-slot:[`item.calculate_sum`]="{ item }">
            <v-checkbox
                v-model="item.calculate_sum"
                direction="vertical"
                class="d-flex justify-center"
                false-icon="mdi-close"
                readonly
                hide-details
            ></v-checkbox>
        </template>

        <template v-slot:expanded-row="{ item, columns }">
            <td :colspan="columns.length" class="pa-4">
                <CardTitleWithButtons title="Queries"></CardTitleWithButtons>

                <v-data-table
                    :headers="queryHeaders"
                    :items="item.queries"
                >
                    <template v-slot:[`item.query_data`]="{ item }">
                        <span class="text-capitalize">{{ item.query_data }}</span>
                    </template>

                    <template v-slot:[`item.min_date`]="{ item }">
                        {{ item.min_date || "N/A" }}
                    </template>

                    <template v-slot:[`item.max_date`]="{ item }">
                        {{ item.max_date || "N/A" }}
                    </template>

                    <template v-slot:[`item.title`]="{ item }">
                        <span style="white-space: nowrap">{{ item.title || "All titles" }}</span>
                    </template>

                    <template v-slot:[`item.min_amount`]="{ item }">
                        {{ item.min_amount || "N/A" }}
                    </template>

                    <template v-slot:[`item.max_amount`]="{ item }">
                        {{ item.max_amount || "N/A" }}
                    </template>

                    <template v-slot:[`item.min_price`]="{ item }">
                        {{ item.min_price || "N/A" }}&nbsp;{{ item.min_price && item.currency || "" }}
                    </template>

                    <template v-slot:[`item.max_price`]="{ item }">
                        {{ item.max_price || "N/A" }}&nbsp;{{ item.max_price && item.currency || "" }}
                    </template>

                    <template v-slot:[`item.currency`]="{ item }">
                        {{ item.currency || "All currencies" }}
                    </template>

                    <template v-slot:[`item.category_id`]="{ item }">
                        {{ props.categories.get(item.category_id)?.name ?? "All categories" }}
                    </template>

                    <template v-slot:[`item.account_id`]="{ item }">
                        {{ props.accounts.get(item.account_id)?.name ?? "All accounts" }}
                    </template>
                </v-data-table>

                <CardTitleWithButtons title="Additional transactions"></CardTitleWithButtons>

                <v-data-table
                    :headers="additionalTransactionHeaders"
                    :items="item.additionalEntries"
                >
                    <template v-slot:[`item.title`]="{ item }">
                        <span style="white-space: nowrap">{{ item.title }}</span>
                    </template>

                    <template v-slot:[`item.price`]="{ item }">
                        {{ formats.numberWithCurrency(item.price, item.currency) }}
                    </template>

                    <template v-slot:[`item.category_id`]="{ item }">
                        {{ props.categories.get(item.category_id)?.name ?? "N/A" }}
                    </template>

                    <template v-slot:[`item.account_id`]="{ item }">
                        {{ props.accounts.get(item.account_id)?.name ?? "N/A" }}
                    </template>
                </v-data-table>

                <CardTitleWithButtons title="Users"></CardTitleWithButtons>

                <div
                    class="d-flex justify-center flex-wrap"
                    style="gap: 12px"
                >
                    <v-chip
                        v-for="user in item.users"
                        :text="user"
                        variant="outlined"
                        pill
                    ></v-chip>
                </div>
            </td>
        </template>
    </v-data-table>
</template>

<script setup lang="ts">
import type { CategoryRestoreData, AccountRestoreData } from "@interfaces/Backup"
import useFormats from "@composables/useFormats"

const reportHeaders = [
    {title: "Title", align: "center", value: "title"},
    {title: "Show sum", align: "center", value: "calculate_sum"},
    {title: "Add income", align: "center", value: "income_addition"},
    {title: "Sort dates desc", align: "center", value: "sort_dates_desc"},
    {title: "Show columns", align: "center", value: "show_columns"},
]

const queryHeaders = [
    {title: "Query type", align: "center", value: "query_data"},
    {title: "Min date", align: "center", value: "min_date"},
    {title: "Max date", align: "center", value: "max_date"},
    {title: "Title", align: "center", value: "title"},
    {title: "Min amount", align: "center", value: "min_amount"},
    {title: "Max amount", align: "center", value: "max_amount"},
    {title: "Min price", align: "center", value: "min_price"},
    {title: "Max price", align: "center", value: "max_price"},
    {title: "Currency", align: "center", value: "currency"},
    {title: "Category", align: "center", value: "category_id"},
    {title: "Account", align: "center", value: "account_id"},
]

const additionalTransactionHeaders = [
    {title: "Date", align: "center", value: "date"},
    {title: "Title", align: "center", value: "title"},
    {title: "Amount", align: "center", value: "amount"},
    {title: "Price", align: "center", value: "price"},
    {title: "Category", align: "center", value: "category_id"},
    {title: "Account", align: "center", value: "account_id"},
]

const props = defineProps<{
    items: {
        reports: unknown[]
    },
    categories: Map<number, CategoryRestoreData>,
    accounts: Map<number, AccountRestoreData>,
}>()

const formats = useFormats()
</script>