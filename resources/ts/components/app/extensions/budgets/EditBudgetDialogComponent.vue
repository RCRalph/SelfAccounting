<template>
    <v-dialog
        v-model="dialog"
        max-width="400"
    >
        <template v-slot:activator="{ props: dialogProps }">
            <v-tooltip
                v-if="props.showIcon"
                location="bottom"
                text="Edit budget"
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
                text="Edit budget"
            ></v-btn>
        </template>

        <v-card v-if="ready && budgetData">
            <CardTitleWithButtons title="Edit budget"></CardTitleWithButtons>

            <v-card-text>
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

            <CardActionsResetUpdateComponent
                :loading="loading.submit"
                :can-submit="canSubmit"
                @reset="reset"
                @update="update"
            ></CardActionsResetUpdateComponent>
        </v-card>

        <CardLoadingComponent
            v-else
            title="Edit budget"
        ></CardLoadingComponent>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { cloneDeep } from "lodash"
import { ref, watch } from "vue"

import useComponentState from "@composables/useComponentState"
import { useStatusStore } from "@stores/status"

import type { BudgetInformation } from "@interfaces/Budget"
import Validator from "@classes/Validator"

const props = defineProps<{
    id: number
    showIcon?: boolean
}>()

const emit = defineEmits<{
    updated: []
}>()

const status = useStatusStore()

function useData() {
    const budgetData = ref<BudgetInformation>()

    const budgetDataCopy = ref<BudgetInformation>()

    function reset() {
        budgetData.value = cloneDeep(budgetDataCopy.value)
    }

    function getData() {
        if (!dialog.value) return

        ready.value = false

        axios.get(`/web-api/extensions/budgets/${props.id}/edit`)
            .then(response => {
                const data = response.data

                budgetData.value = data.data
                budgetDataCopy.value = cloneDeep(data.data)

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
            })
    }

    function update() {
        loading.value.submit = true

        axios
            .post(`/web-api/extensions/budgets/${props.id}/update`, budgetData.value)
            .then(() => {
                emit("updated")
                status.showSuccess(`updated report`)
                budgetDataCopy.value = cloneDeep(budgetData.value)
                dialog.value = false
                loading.value.submit = false
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.submit = false, 2000)
            })
    }

    return {budgetData, reset, getData, update}
}

const {canSubmit, dialog, loading, ready} = useComponentState()
const {budgetData, reset, getData, update} = useData()

watch(dialog, getData)
</script>
