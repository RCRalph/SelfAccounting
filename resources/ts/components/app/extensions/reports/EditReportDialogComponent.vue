<template>
    <v-dialog
        v-model="dialog"
        max-width="800"
    >
        <template v-slot:activator="{ props: dialogProps }">
            <v-tooltip
                v-if="props.showIcon"
                location="bottom"
                text="Edit report"
            >
                <template v-slot:activator="{ props: tooltipProps }">
                    <v-icon
                        v-bind="{...tooltipProps, ...dialogProps}"
                        class="mx-1 cursor-pointer"
                        icon="mdi-pencil"
                    ></v-icon>
                </template>
            </v-tooltip>

            <v-btn
                v-else
                v-bind="dialogProps"
                variant="outlined"
                text="Edit report"
            ></v-btn>
        </template>

        <v-card v-if="ready && reportData != undefined">
            <CardTitleWithButtons title="Edit report">
                <ShareReportDialogComponent
                    v-model="reportData.users"
                ></ShareReportDialogComponent>
            </CardTitleWithButtons>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <v-row>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-text-field
                                v-model="reportData.title"
                                :loading="loading.title"
                                :rules="[
                                    Validator.title('Title', 64)
                                ]"
                                variant="underlined"
                                label="Title"
                                counter="64"
                            ></v-text-field>
                        </v-col>

                        <v-col
                            class="text-center"
                            cols="12"
                            md="6"
                        >
                            <v-switch
                                v-model="reportData.calculate_sum"
                                class="d-inline-flex"
                                density="compact"
                            >
                                <template v-slot:prepend>
                                    Hide sum
                                </template>

                                <template v-slot:append>
                                    Show sum
                                </template>
                            </v-switch>
                        </v-col>

                        <v-col
                            class="text-center"
                            cols="12"
                            md="6"
                        >
                            <v-switch
                                v-model="reportData.income_addition"
                                class="d-inline-flex"
                                density="compact"
                            >
                                <template v-slot:prepend>
                                    Add expenses, subtract income
                                </template>

                                <template v-slot:append>
                                    Add income, subtract expenses
                                </template>
                            </v-switch>
                        </v-col>

                        <v-col
                            class="text-center"
                            cols="12"
                            md="6"
                        >
                            <v-switch
                                v-model="reportData.sort_dates_desc"
                                class="d-inline-flex"
                                density="compact"
                            >
                                <template v-slot:prepend>
                                    Sort dates ascending
                                </template>

                                <template v-slot:append>
                                    Sort dates descending
                                </template>
                            </v-switch>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" md="4" offset-md="1">
                            <ReportQueriesDialogComponent
                                v-model="reportData.queries"
                                :categories="categories"
                                :accounts="accounts"
                            ></ReportQueriesDialogComponent>
                        </v-col>

                        <v-col cols="12" md="4" offset-md="2">
                            <ReportAdditionalTransactionsDialogComponent
                                v-model="reportData.additionalTransactions"
                                :categories="categories"
                                :accounts="accounts"
                            ></ReportAdditionalTransactionsDialogComponent>
                        </v-col>
                    </v-row>

                    <v-divider class="mt-3"></v-divider>

                    <v-table>
                        <thead>
                            <tr>
                                <th class="text-center">Date</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Value</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Account</th>
                            </tr>
                        </thead>

                        <tbody class="disable-hover">
                            <tr>
                                <td>
                                    <v-checkbox
                                        v-model="reportData.columns.date"
                                        direction="vertical"
                                        class="d-flex justify-center"
                                    ></v-checkbox>
                                </td>

                                <td>
                                    <v-checkbox
                                        v-model="reportData.columns.title"
                                        direction="vertical"
                                        class="d-flex justify-center"
                                    ></v-checkbox>
                                </td>

                                <td>
                                    <v-checkbox
                                        v-model="reportData.columns.amount"
                                        direction="vertical"
                                        class="d-flex justify-center"
                                    ></v-checkbox>
                                </td>

                                <td>
                                    <v-checkbox
                                        v-model="reportData.columns.price"
                                        direction="vertical"
                                        class="d-flex justify-center"
                                    ></v-checkbox>
                                </td>

                                <td>
                                    <v-checkbox
                                        v-model="reportData.columns.value"
                                        direction="vertical"
                                        class="d-flex justify-center"
                                    ></v-checkbox>
                                </td>

                                <td>
                                    <v-checkbox
                                        v-model="reportData.columns.category_id"
                                        direction="vertical"
                                        class="d-flex justify-center"
                                    ></v-checkbox>
                                </td>

                                <td>
                                    <v-checkbox
                                        v-model="reportData.columns.account_id"
                                        direction="vertical"
                                        class="d-flex justify-center"
                                    ></v-checkbox>
                                </td>
                            </tr>
                        </tbody>
                    </v-table>
                </v-form>
            </v-card-text>

            <CardActionsResetUpdateComponent
                :loading="loading.submit"
                :can-submit="canSubmit"
                @reset="reset"
                @update="update"
            ></CardActionsResetUpdateComponent>
        </v-card>

        <CardLoadingComponent
            v-else
            title="Edit report"
        ></CardLoadingComponent>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { cloneDeep } from "lodash"
import { onMounted, ref, watch } from "vue"

import type { Report } from "@interfaces/Report"
import type { CategoryData } from "@interfaces/Category"
import type { AccountData } from "@interfaces/Account"

import ReportQueriesDialogComponent from "@components/app/extensions/reports/ReportQueriesDialogComponent.vue"
import ReportAdditionalTransactionsDialogComponent
    from "@components/app/extensions/reports/ReportAdditionalTransactionsDialogComponent.vue"
import ShareReportDialogComponent from "@components/app/extensions/reports/ShareReportDialogComponent.vue"

import { useStatusStore } from "@stores/status"
import useComponentState from "@composables/useComponentState"
import Validator from "@classes/Validator"
import Calculator from "@classes/Calculator"

const props = defineProps<{
    id: number
    showIcon?: boolean
}>()

const emit = defineEmits<{
    updated: []
}>()

const status = useStatusStore()

function useData() {
    const reportData = ref<Report>()

    const reportDataCopy = ref<Report>()

    const categories = ref<Record<number, CategoryData[]>>([])

    const accounts = ref<Record<number, AccountData[]>>([])

    function reset() {
        reportData.value = cloneDeep(reportDataCopy.value)
    }

    function update() {
        if (!reportData.value) return

        loading.value.submit = true

        const data = cloneDeep(reportData.value)

        for (let item of data.queries) {
            item.min_amount = item.min_amount === null ? null :
                new Calculator(item.min_amount, "amount").resultValue
            item.max_amount = item.max_amount === null ? null :
                new Calculator(item.max_amount, "amount").resultValue
            item.min_price = item.min_price === null ? null :
                new Calculator(item.min_price, "price").resultValue
            item.max_price = item.max_price === null ? null :
                new Calculator(item.max_price, "price").resultValue
        }

        for (let item of data.additionalTransactions) {
            item.amount = new Calculator(item.amount, "amount").resultValue
            item.price = new Calculator(item.price, "price").resultValue
        }

        const users = reportData.value?.users.map(item => item.email)

        axios.post(`/web-api/extensions/reports/${props.id}/update`, {
            ...data,
            users,
        })
            .then(() => {
                emit("updated")
                status.showSuccess(`updated report`)
                reportDataCopy.value = cloneDeep(reportData.value)
                dialog.value = false
                loading.value.submit = false
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.submit = false, 2000)
            })
    }

    function getData() {
        if (!dialog.value) return

        ready.value = false

        axios.get(`/web-api/extensions/reports/${props.id}/edit`)
            .then(response => {
                const data = response.data

                reportData.value = data.data
                reportDataCopy.value = cloneDeep(data.data)

                accounts.value = data.accounts
                categories.value = data.categories

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
            })
    }

    return {reportData, categories, accounts, reset, update, getData}
}

const {canSubmit, dialog, loading, ready} = useComponentState()
const {reportData, categories, accounts, reset, update, getData} = useData()

watch(dialog, getData)

onMounted(() => ready.value = true)
</script>
