<template>
    <CardTitleWithButtons title="Transfers"></CardTitleWithButtons>

    <v-data-table
        :headers="transferHeaders({
            disableSort: true
        })"
        :items="props.items"
    >
        <template v-slot:[`item.source_value`]="{ item }">
            {{ item.source_value }}&nbsp;{{ props.accounts.get(item.source_account_id)?.currency ?? "" }}
        </template>

        <template v-slot:[`item.source_account`]="{ item }">
            {{ props.accounts.get(item.source_account_id)?.name ?? "N/A" }}
        </template>

        <template v-slot:[`item.target_value`]="{ item }">
            {{ item.target_value }}&nbsp;{{ props.accounts.get(item.target_account_id)?.currency ?? "" }}
        </template>

        <template v-slot:[`item.target_account`]="{ item }">
            {{ props.accounts.get(item.target_account_id)?.name ?? "N/A" }}
        </template>
    </v-data-table>
</template>

<script setup lang="ts">
import type { VDataTable } from "vuetify/components"
import useTableSettings from "@composables/useTableSettings"

import type { AccountRestoreData } from "@interfaces/Backup"

const props = defineProps<{
    items: unknown[],
    accounts: Map<number, AccountRestoreData>
}>()

const {transferHeaders} = useTableSettings()
</script>