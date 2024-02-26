<template>
    <v-dialog
        v-model="dialog"
        max-width="800"
    >
        <template v-slot:activator="{ props: dialogProps }">
            <v-btn
                v-bind="dialogProps"
                variant="outlined"
                text="Create report"
            ></v-btn>
        </template>

        <v-card v-if="ready && reportData != undefined">
            <CardTitleWithButtons title="Create report">
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

            <CardActionsSubmitComponent
                :loading="loading.submit"
                :can-submit="canSubmit"
                @submit="submit"
            ></CardActionsSubmitComponent>
        </v-card>

        <CardLoadingComponent
            v-else
            title="Create report"
        ></CardLoadingComponent>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { cloneDeep } from "lodash"
import { ref, watch } from "vue"
import { useRouter } from "vue-router"

import type { Report } from "@interfaces/Reports"
import type { CategoryData } from "@interfaces/Category"
import type { AccountData } from "@interfaces/Account"

import ReportQueriesDialogComponent from "@components/extensions/reports/ReportQueriesDialogComponent.vue"
import ReportAdditionalTransactionsDialogComponent
    from "@components/extensions/reports/ReportAdditionalTransactionsDialogComponent.vue"
import ShareReportDialogComponent from "@components/extensions/reports/ShareReportDialogComponent.vue"

import { useStatusStore } from "@stores/status"
import useComponentState from "@composables/useComponentState"
import Validator from "@classes/Validator"
import Calculator from "@classes/Calculator"

const router = useRouter()
const status = useStatusStore()

function useData() {
    const startData: Report = {
        title: "",
        calculate_sum: true,
        income_addition: true,
        sort_dates_desc: false,
        columns: {
            date: true,
            title: true,
            amount: true,
            price: true,
            value: true,
            category_id: true,
            account_id: true,
        },
        queries: [],
        additionalTransactions: [],
        users: [],
    }

    const reportData = ref<Report>()

    const categories = ref<Record<number, CategoryData[]>>([])

    const accounts = ref<Record<number, AccountData[]>>([])

    function submit() {
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

        axios.post(`/web-api/extensions/reports/create`, {
            ...data,
            users,
        })
            .then(response => {
                const data = response.data

                status.showSuccess(`created report`)
                dialog.value = false
                loading.value.submit = false

                router.push(`/extensions/reports/${data.id}`)
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

        axios.get(`/web-api/extensions/reports/create`)
            .then(response => {
                const data = response.data

                reportData.value = cloneDeep(startData)

                accounts.value = data.accounts
                categories.value = data.categories

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
            })
    }

    return {reportData, categories, accounts, getData, submit}
}

const {canSubmit, dialog, loading, ready} = useComponentState()
const {reportData, categories, accounts, getData, submit} = useData()

watch(dialog, getData)
</script>
