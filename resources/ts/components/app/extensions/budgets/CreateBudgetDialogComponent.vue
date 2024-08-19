<template>
    <v-dialog
        v-model="dialog"
        max-width="400"
    >
        <template v-slot:activator="{ props }">
            <v-btn
                v-bind="props"
                variant="outlined"
            >
                Create budget
            </v-btn>
        </template>

        <v-card>
            <CardTitleWithButtons title="Create budget"></CardTitleWithButtons>

            <v-card-text v-if="budgetData">
                <v-form v-model="canSubmit">
                    <v-text-field
                        v-model="budgetData.title"
                        :rules="[
                            Validator.title('Title', 64)
                        ]"
                        variant="underlined"
                        label="Title"
                        counter="64"
                    ></v-text-field>

                    <v-text-field
                        v-model="budgetData.start_date"
                        :max="budgetData.end_date"
                        :rules="[
                            Validator.date()
                        ]"
                        type="date"
                        label="Start date"
                        variant="underlined"
                    ></v-text-field>

                    <v-text-field
                        v-model="budgetData.end_date"
                        :min="budgetData.start_date || '1970-01-01'"
                        :rules="[
                            Validator.date()
                        ]"
                        type="date"
                        label="End date"
                        variant="underlined"
                    ></v-text-field>
                </v-form>
            </v-card-text>

            <CardActionsSubmitComponent
                :loading="!!loading.submit"
                :can-submit="canSubmit"
                @submit="submit"
            ></CardActionsSubmitComponent>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { cloneDeep } from "lodash"
import { ref, watch } from "vue"
import { useRouter } from "vue-router"

import useComponentState from "@composables/useComponentState"
import { currentTimeZoneDate } from "@composables/useDates"
import { useStatusStore } from "@stores/status"

import type { BudgetInformation } from "@interfaces/Budget"
import Validator from "@classes/Validator"

const router = useRouter()
const status = useStatusStore()

function useData() {
    const startData: BudgetInformation = {
        title: "",
        start_date: currentTimeZoneDate(),
        end_date: currentTimeZoneDate(),
    }

    const budgetData = ref<BudgetInformation>()

    function getData() {
        if (!dialog.value) return

        budgetData.value = cloneDeep(startData)
    }

    function submit() {
        loading.value.submit = true

        axios
            .post(`/web-api/extensions/budgets/create`, budgetData.value)
            .then(response => response.data)
            .then(data => {
                status.showSuccess(`created budget`)
                dialog.value = false
                loading.value.submit = false

                router.push(`/extensions/budgets/${data.id}`)
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.submit = false, 2000)
            })
    }

    return {budgetData, getData, submit}
}

const {canSubmit, dialog, loading} = useComponentState()
const {budgetData, getData, submit} = useData()

watch(dialog, getData)
</script>
