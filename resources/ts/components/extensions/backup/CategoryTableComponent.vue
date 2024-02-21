<template>
    <CardTitleWithButtons title="Categories"></CardTitleWithButtons>

    <v-data-table
        :headers="categoryHeaders({
            prependCurrency: true
        })"
        :items="props.items"
    >
        <template v-slot:[`item.icon`]="{ item }">
            <v-icon v-if="item.icon">{{ item.icon }}</v-icon>

            <span v-else>N/A</span>
        </template>

        <template v-slot:[`item.title`]="{ item }">
            <span style="white-space: nowrap">{{ item.title }}</span>
        </template>

        <template v-slot:[`item.used_in_income`]="{ item }">
            <v-checkbox
                v-model="item.used_in_income"
                direction="vertical"
                class="d-flex justify-center"
                false-icon="mdi-close"
                readonly
                hide-details
            ></v-checkbox>
        </template>

        <template v-slot:[`item.used_in_expenses`]="{ item }">
            <v-checkbox
                v-model="item.used_in_expenses"
                direction="vertical"
                class="d-flex justify-center"
                false-icon="mdi-close"
                readonly
                hide-details
            ></v-checkbox>
        </template>

        <template v-slot:[`item.show_on_charts`]="{ item }">
            <v-checkbox
                v-model="item.show_on_charts"
                direction="vertical"
                class="d-flex justify-center"
                false-icon="mdi-close"
                readonly
                hide-details
            ></v-checkbox>
        </template>

        <template v-slot:[`item.count_to_summary`]="{ item }">
            <v-checkbox
                v-model="item.count_to_summary"
                direction="vertical"
                class="d-flex justify-center"
                false-icon="mdi-close"
                readonly
                hide-details
            ></v-checkbox>
        </template>

        <template v-slot:[`item.start_date`]="{ item }">
            {{ item.start_date || "N/A" }}
        </template>

        <template v-slot:[`item.end_date`]="{ item }">
            {{ item.end_date || "N/A" }}
        </template>
    </v-data-table>
</template>

<script setup lang="ts">
import useTableHeaders from "@composables/useTableHeaders"

const props = defineProps<{
    items: unknown[]
}>()

const {categoryHeaders} = useTableHeaders()
</script>