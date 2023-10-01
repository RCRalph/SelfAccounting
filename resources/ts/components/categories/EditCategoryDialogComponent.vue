<template>
    <v-dialog
        v-model="dialog"
        max-width="700"
    >
        <template v-slot:activator="{props: dialogProps}: any">
            <v-tooltip location="bottom">
                <template v-slot:activator="{props: tooltipProps}: any">
                    <v-icon
                        v-bind="{ ...dialogProps, ...tooltipProps }"
                        class="mx-1 cursor-pointer"
                        icon="mdi-pencil"
                    ></v-icon>
                </template>

                <span>
                    Edit category
                </span>
            </v-tooltip>
        </template>

        <v-card v-if="ready && categoryData">
            <CardTitleWithButtons title="Edit Category"></CardTitleWithButtons>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <v-row>
                        <v-col cols="12" sm="4" class="d-flex justify-center align-center">
                            <IconPickerComponent
                                v-model="categoryData.icon"
                                type="categories"
                            ></IconPickerComponent>
                        </v-col>

                        <v-col cols="12" sm="8">
                            <v-text-field
                                v-model="categoryData.name"
                                :rules="[
                                    Validator.title('Name', 32)
                                ]"
                                variant="underlined"
                                label="Name"
                                counter="32"
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="6" sm="4">
                            <v-switch
                                v-model="categoryData.used_in_income"
                                label="Show in income"
                            ></v-switch>
                        </v-col>

                        <v-col cols="6" sm="4">
                            <v-switch
                                v-model="categoryData.used_in_expenses"
                                label="Show in expenses"
                            ></v-switch>
                        </v-col>

                        <v-col cols="6" sm="4">
                            <v-switch
                                v-model="categoryData.show_on_charts"
                                label="Show on charts"
                            ></v-switch>
                        </v-col>

                        <v-col cols="6" sm="4">
                            <v-switch
                                v-model="categoryData.count_to_summary"
                                label="Count to summary"
                            ></v-switch>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <v-text-field
                                v-model="categoryData.start_date"
                                :disabled="!categoryData.count_to_summary"
                                :max="categoryData.end_date"
                                :rules="[
                                    Validator.date(true)
                                ]"
                                type="date"
                                label="Start date"
                                variant="underlined"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <v-text-field
                                v-model="categoryData.end_date"
                                :disabled="!categoryData.count_to_summary"
                                :min="categoryData.start_date || '1970-01-01'"
                                :rules="[
                                    Validator.date(true)
                                ]"
                                type="date"
                                label="End date"
                                variant="underlined"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <CardActionsResetUpdateComponent
                :loading="!!loading.submit"
                :can-submit="!!canSubmit"
                @reset="reset"
                @update="update"
            ></CardActionsResetUpdateComponent>
        </v-card>

        <CardLoadingComponent
            v-else
            title="Edit category"
        ></CardLoadingComponent>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref, watch } from "vue"
import type { Ref } from "vue"
import { cloneDeep } from "lodash"

import type { Category } from "@interfaces/Category"

import { useStatusStore } from "@stores/status"
import { useDialogSettings } from "@composables/useDialogSettings"

import Validator from "@classes/Validator"
import IconPickerComponent from "@components/icon-picker/IconPickerComponent.vue"

const props = defineProps<{
    id: number
}>()

const emit = defineEmits<{
    updated: []
}>()

const status = useStatusStore()

function useData() {
    const categoryData: Ref<Category | undefined> = ref(undefined)
    const categoryDataCopy: Ref<Category | undefined> = ref(undefined)

    function getData() {
        if (!dialog.value) return

        ready.value = false

        axios.get(`/web-api/categories/category/${props.id}`)
            .then(response => {
                const data = response.data

                categoryData.value = data.data
                categoryDataCopy.value = cloneDeep(data.data)

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
            })
    }

    return {categoryData, categoryDataCopy, getData}
}

function useActions() {
    function reset() {
        categoryData.value = cloneDeep(categoryDataCopy.value)
    }

    function update() {
        loading.value.submit = true

        axios.patch(`/web-api/categories/category/${props.id}`, categoryData.value)
            .then(() => {
                emit("updated")
                status.showSuccess("updated category")
                categoryDataCopy.value = cloneDeep(categoryData.value)
                dialog.value = false
                loading.value.submit = false
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.submit = false, 2000)
            })
    }

    return {reset, update}
}

const {canSubmit, dialog, loading, ready} = useDialogSettings()
const {categoryData, categoryDataCopy, getData} = useData()
const {reset, update} = useActions()

watch(dialog, getData)
</script>
