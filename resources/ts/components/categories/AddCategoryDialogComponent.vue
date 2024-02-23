<template>
    <v-dialog
        v-model="dialog"
        max-width="700"
    >
        <template v-slot:activator="{ props }">
            <v-btn
                v-bind="props"
                variant="outlined"
            >
                Add category
            </v-btn>
        </template>

        <v-card v-if="categoryData">
            <CardTitleWithButtons title="Add category"></CardTitleWithButtons>

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

            <CardActionsSubmitComponent
                :loading="!!loading.submit"
                :can-submit="!!canSubmit"
                @submit="submit"
            ></CardActionsSubmitComponent>
        </v-card>

        <CardLoadingComponent
            v-else
            title="Add category"
        ></CardLoadingComponent>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref, onMounted } from "vue"
import { cloneDeep } from "lodash"

import type { Category } from "@interfaces/Category"

import IconPickerComponent from "@components/icon-picker/IconPickerComponent.vue"
import CardLoadingComponent from "@components/global/card/CardLoadingComponent.vue"

import { useCurrenciesStore } from "@stores/currencies"
import { useStatusStore } from "@stores/status"
import useComponentState from "@composables/useComponentState"
import Validator from "@classes/Validator"

const emit = defineEmits<{
    added: []
}>()

const currencies = useCurrenciesStore()
const status = useStatusStore()

function useData() {
    const categoryData = ref<Category>()
    const commonValues = ref<Category>({
        name: undefined,
        icon: null,
        used_in_income: true,
        used_in_expenses: true,
        show_on_charts: true,
        count_to_summary: false,
        start_date: null,
        end_date: null,
    })

    function setData() {
        categoryData.value = cloneDeep(commonValues.value)
    }

    function submit() {
        loading.value.submit = true

        axios
            .post(`/web-api/categories/${currencies.usedCurrency}`, categoryData.value)
            .then(() => {
                emit("added")
                setData()
                dialog.value = false
                loading.value.submit = false
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.submit = false, 2000)
            })
    }

    return {categoryData, setData, submit}
}

const {canSubmit, dialog, loading} = useComponentState()
const {categoryData, setData, submit} = useData()

onMounted(setData)
</script>
