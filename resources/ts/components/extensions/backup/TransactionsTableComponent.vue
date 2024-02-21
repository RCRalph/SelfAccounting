<template>
    <CardTitleWithButtons :title="props.title"></CardTitleWithButtons>

    <v-data-table
        :headers="transactionHeaders({
            excludedColumns: new Set(['value']),
            prependCurrency: true,
            disableSort: true
        })"
        :items="props.items"
    >
        <template v-slot:[`item.title`]="{ item }">
            <span style="white-space: nowrap">{{ item.title }}</span>
        </template>

        <template v-slot:[`item.price`]="{ item }">
            {{ formats.numberWithCurrency(item.price, item.currency) }}
        </template>

        <template v-slot:[`item.category`]="{ item }">
            {{ props.categories.get(item.category_id)?.name ?? "N/A" }}
        </template>

        <template v-slot:[`item.account`]="{ item }">
            {{ props.accounts.get(item.account_id)?.name ?? "N/A" }}
        </template>
    </v-data-table>
</template>

<script setup lang="ts">
import type { VDataTable } from "vuetify/components"
import useTableHeaders from "@composables/useTableHeaders"
import useFormats from "@composables/useFormats"

import type { CategoryRestoreData, AccountRestoreData } from "@interfaces/Backup"

const props = defineProps<{
    items: unknown[],
    title: string,
    categories: Map<number, CategoryRestoreData>,
    accounts: Map<number, AccountRestoreData>
}>()

const formats = useFormats()

const {transactionHeaders} = useTableHeaders()
</script>