<template>
    <div
        v-if="ready"
        style="margin: 12px"
    >
        <v-row>
            <v-col
                cols="12"
                lg="10"
                offset-lg="1"
                xl="8"
                offset-xl="2"
            >
                <v-card class="loading-height">
                    <CardTitleWithButtons
                        :title="`Categories (${currencies.usedCurrencyObject.ISO})`"
                        :large-font="true"
                    >
                        <AddCategoryDialogComponent @added="getData"></AddCategoryDialogComponent>
                    </CardTitleWithButtons>

                    <v-card-text>
                        <v-data-table
                            v-model:options="options"
                            :headers="categoryHeaders()"
                            :items="categories"
                            :items-per-page="-1"
                            density="comfortable"
                        >
                            <template v-slot:[`item.icon`]="{ item }">
                                <v-icon v-if="item.raw.icon">
                                    {{ formats.iconName(item.raw.icon) }}
                                </v-icon>

                                <span v-else>
                                    N/A
                                </span>
                            </template>

                            <template v-slot:[`item.name`]="{ item }">
                                <span style="white-space: nowrap">{{ item.raw.name }}</span>
                            </template>

                            <template v-slot:[`item.used_in_income`]="{ item }">
                                <v-checkbox-btn
                                    v-model="item.raw.used_in_income"
                                    :disabled="true"
                                    direction="vertical"
                                    class="d-flex justify-center"
                                    false-icon="mdi-close"
                                    hide-details
                                ></v-checkbox-btn>
                            </template>

                            <template v-slot:[`item.used_in_expenses`]="{ item }">
                                <v-checkbox-btn
                                    v-model="item.raw.used_in_expenses"
                                    :disabled="true"
                                    direction="vertical"
                                    class="d-flex justify-center"
                                    false-icon="mdi-close"
                                    hide-details
                                ></v-checkbox-btn>
                            </template>

                            <template v-slot:[`item.show_on_charts`]="{ item }">
                                <v-checkbox-btn
                                    v-model="item.raw.show_on_charts"
                                    :disabled="true"
                                    direction="vertical"
                                    class="d-flex justify-center"
                                    false-icon="mdi-close"
                                    hide-details
                                ></v-checkbox-btn>
                            </template>

                            <template v-slot:[`item.count_to_summary`]="{ item }">
                                <v-checkbox-btn
                                    v-model="item.raw.count_to_summary"
                                    :disabled="true"
                                    direction="vertical"
                                    class="d-flex justify-center"
                                    false-icon="mdi-close"
                                    hide-details
                                ></v-checkbox-btn>
                            </template>

                            <template v-slot:[`item.actions`]="{ item }">
                                <div class="d-flex flex-nowrap justify-center align-center">
                                    <EditCategoryDialogComponent
                                        :id="item.raw.id"
                                        @updated="getData"
                                    ></EditCategoryDialogComponent>

                                    <DuplicateCategoryDialogComponent
                                        :id="item.raw.id"
                                        @duplicated="getData"
                                    ></DuplicateCategoryDialogComponent>

                                    <DeleteDialogComponent
                                        :url="`categories/category/${item.raw.id}`"
                                        thing="category"
                                        @deleted="getData"
                                    ></DeleteDialogComponent>
                                </div>
                            </template>

                            <template v-slot:bottom></template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>

    <v-overlay
        v-else
        :model-value="true"
        :contained="true"
    >
        <v-progress-circular
            indeterminate
            size="128"
        ></v-progress-circular>
    </v-overlay>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref, onMounted } from "vue"
import type { Ref } from "vue"

import type { Category } from "@interfaces/Category"

import { useCurrenciesStore } from "@stores/currencies"

import CardTitleWithButtons from "@components/common/CardTitleWithButtons.vue"
import DeleteDialogComponent from "@components/common/DeleteDialogComponent.vue"
import AddCategoryDialogComponent from "@components/categories/AddCategoryDialogComponent.vue"
import EditCategoryDialogComponent from "@components/categories/EditCategoryDialogComponent.vue"
import DuplicateCategoryDialogComponent from "@components/categories/DuplicateCategoryDialogComponent.vue"

import useTableSettings from "@composables/useTableSettings"
import useFormats from "@composables/useFormats"

const currencies = useCurrenciesStore()
const formats = useFormats()

function useData() {
    const ready = ref(false)
    const categories: Ref<Category[]> = ref([])

    function getData() {
        ready.value = false

        axios.get(`/web-api/categories/${currencies.usedCurrency}`)
            .then(response => {
                categories.value = response.data

                ready.value = true
            })
    }

    return {categories, getData, ready}
}

const {categoryHeaders, options} = useTableSettings()
const {categories, getData, ready} = useData()

onMounted(() => {
    getData()
    currencies.$subscribe(getData)
})
</script>
