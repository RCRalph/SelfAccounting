<template>
    <v-dialog
        v-model="dialog"
        max-width="1300"
        :persistent="loading.submit"
    >
        <template v-slot:activator="{ props: dialogProps }">
            <v-tooltip
                :disabled="typeof props.tooltip != 'string' || loading.submit"
                :text="props.tooltip.toString()"
                location="bottom"
            >
                <template v-slot:activator="{ props: tooltipProps }">
                    <!-- Binding has to be on button's parent element, because disabled button will not trigger the tooltip -->
                    <div v-bind="{ ...dialogProps, ...tooltipProps }">
                        <v-btn
                            :disabled="typeof props.tooltip == 'string' || loading.submit"
                            text="Restore backup"
                            variant="outlined"
                            width="200"
                            class="ma-1"
                            size="large"
                            @click=""
                        ></v-btn>
                    </div>
                </template>
            </v-tooltip>
        </template>

        <v-card>
            <CardTitleWithButtons
                title="Restore backup"
                large-font
            ></CardTitleWithButtons>

            <v-card-text>
                <v-row>
                    <v-col cols="12" md="8" offset-md="2" lg="6" offset-lg="3" offset-xl="4" xl="4">
                        <v-file-input
                            v-model="file"
                            :disabled="loading.submit"
                            accept=".selfacc2"
                            label="Backup file"
                            show-size
                            clearable
                        ></v-file-input>
                    </v-col>
                </v-row>

                <div v-if="typeof data != 'undefined'">
                    <CategoryTableComponent
                        :items="data.categories"
                    ></CategoryTableComponent>

                    <AccountsTableComponent
                        :items="data.accounts"
                    ></AccountsTableComponent>

                    <TransactionsTableComponent
                        :items="data.income"
                        :categories="categories"
                        :accounts="accounts"
                        title="Income"
                    ></TransactionsTableComponent>

                    <TransactionsTableComponent
                        :items="data.expenses"
                        :categories="categories"
                        :accounts="accounts"
                        title="Expenses"
                    ></TransactionsTableComponent>

                    <TransfersTableComponent
                        :items="data.transfers"
                        :accounts="accounts"
                    ></TransfersTableComponent>

                    <CashTableComponent
                        v-if="extensions.hasExtension('cashan')"
                        :items="data.extensions.cashan"
                        :accounts="accounts"
                    ></CashTableComponent>

                    <ReportsTableComponent
                        v-if="extensions.hasExtension('report')"
                        :items="data.extensions.report"
                        :categories="categories"
                        :accounts="accounts"
                    ></ReportsTableComponent>

                    <div
                        v-if="missingExtensions.size"
                        class="text-center text-error pt-4"
                    >
                        Your file includes data for extensions that aren't currently enabled.
                        Their data will not be restored.
                    </div>
                </div>
            </v-card-text>

            <v-card-actions
                v-if="typeof data != 'undefined'"
                class="d-flex justify-center"
            >
                <v-btn
                    :disabled="loading.submit"
                    :loading="loading.submit"
                    color="success"
                    text="Submit"
                    variant="outlined"
                    @click="submit"
                ></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref, watch } from "vue"

import type { CategoryRestoreData, AccountRestoreData } from "@interfaces/Backup"
import { useDialogSettings } from "@composables/useDialogSettings"
import { useBackupValidation } from "@composables/useBackupValidation"
import { useStatusStore } from "@stores/status"
import { useExtensionsStore } from "@stores/extensions"

import CategoryTableComponent from "@components/extensions/backup/CategoryTableComponent.vue"
import AccountsTableComponent from "@components/extensions/backup/AccountsTableComponent.vue"
import TransactionsTableComponent from "@components/extensions/backup/TransactionsTableComponent.vue"
import TransfersTableComponent from "@components/extensions/backup/TransfersTableComponent.vue"
import CashTableComponent from "@components/extensions/backup/CashTableComponent.vue"
import ReportsTableComponent from "@components/extensions/backup/ReportsTableComponent.vue"

const props = defineProps<{
    tooltip: string | boolean
}>()

const emit = defineEmits<{
    restore: []
}>()

const status = useStatusStore()
const extensions = useExtensionsStore()

function useRestore() {
    const {
        validateCategories,
        validateAccounts,
        validateTransactions,
        validateTransfers,
        validateCash,
        validateReports,
    } = useBackupValidation()

    const file = ref<File[]>([])

    const categories = ref<Map<number, CategoryRestoreData>>(new Map())

    const accounts = ref<Map<number, AccountRestoreData>>(new Map())

    const missingExtensions = ref<Set<string>>(new Set())

    const data = ref<Record<string, any>>()

    function setCategories(items: CategoryRestoreData[]) {
        categories.value.clear()

        items.forEach((item, index) => {
            categories.value.set(index + 1, {
                currency: item.currency,
                name: item.name,
            })
        })
    }

    function setAccounts(items: AccountRestoreData[]) {
        accounts.value.clear()

        items.forEach((item, index) => {
            accounts.value.set(index + 1, {
                currency: item.currency,
                name: item.name,
                start_date: new Date(item.start_date),
            })
        })
    }

    watch(file, () => {
        if (!file.value.length) {
            data.value = undefined
            return undefined
        }

        file.value[0].text()
            .then(content => JSON.parse(content))
            .then(content => {
                if (typeof content != "object" || content == null) {
                    throw new Error("Invalid data wrapper (wrapper not an object)")
                }

                validateCategories(content.categories)
                setCategories(content.categories)

                validateAccounts(content.accounts)
                setAccounts(content.accounts)

                validateTransactions(content.income, categories.value, accounts.value)
                validateTransactions(content.expenses, categories.value, accounts.value)

                validateTransfers(content.transfers, accounts.value)

                missingExtensions.value = new Set(extensions.extensionCodes.filter(item => item != "backup"))

                if (typeof content.extensions == "object" && content.extensions != null) {
                    if ("cashan" in content.extensions) {
                        validateCash(content.extensions.cashan, accounts.value)
                        missingExtensions.value.delete("cashan")
                    }

                    if ("report" in content.extensions) {
                        validateReports(content.extensions.report, categories.value, accounts.value)
                        missingExtensions.value.delete("report")
                    }
                }

                data.value = content
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => file.value.length = 0, 2000)
            })
    })

    function submit() {
        loading.value.submit = true

        axios.post(`/web-api/extensions/backup/restore`, data.value)
            .then(() => {
                emit("restore")
                dialog.value = false
                file.value.length = 0
                status.showSuccess("restored backup")
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.submit = false, 2000)
            })
    }

    return {file, data, submit, categories, accounts, missingExtensions}
}

const {dialog, loading} = useDialogSettings()
const {file, data, submit, categories, accounts, missingExtensions} = useRestore()
</script>
