<template>
    <v-row>
        <v-col
            cols="12"
            md="6"
        >
            <CardTitleWithButtons title="Cash"></CardTitleWithButtons>

            <v-data-table
                :headers="cashHeaders"
                :items="props.items.cash"
            >
                <template v-slot:[`item.value`]="{ item }">
                    {{ formats.numberWithCurrency(item.value, item.currency) }}
                </template>
            </v-data-table>
        </v-col>

        <v-col
            cols="12"
            md="6"
        >
            <CardTitleWithButtons title="Cash accounts"></CardTitleWithButtons>

            <v-data-table
                :headers="accountHeaders"
                :items="props.items.accounts"
            >
                <template v-slot:[`item.account_id`]="{ item }">
                    {{ accounts.get(item.account_id)?.name ?? "N/A" }}
                </template>
            </v-data-table>
        </v-col>
    </v-row>
</template>

<script setup lang="ts">
import type { VDataTable } from "vuetify/components"
import useFormats from "@composables/useFormats"

import type { AccountRestoreData } from "@interfaces/Backup"

const cashHeaders = [
    {title: "Currency", align: "center", value: "currency"},
    {title: "Value", align: "center", value: "value"},
    {title: "Amount", align: "center", value: "amount"},
]

const accountHeaders = [
    {title: "Currency", align: "center", value: "currency"},
    {title: "Account", align: "center", value: "account_id"},
]

const props = defineProps<{
    items: {
        cash: unknown[],
        accounts: unknown[]
    },
    accounts: Map<number, AccountRestoreData>
}>()

const formats = useFormats()
</script>