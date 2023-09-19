<template>
    <v-dialog
        v-model="dialog"
        max-width="300"
    >
        <template v-slot:activator="{props: dialogProps}: any">
            <v-tooltip location="bottom">
                <template v-slot:activator="{props: tooltipProps}: any">
                    <v-icon
                        v-bind="{ ...dialogProps, ...tooltipProps }"
                        class="mx-1 cursor-pointer"
                        icon="mdi-content-duplicate"
                    ></v-icon>
                </template>

                <span>
                    Duplicate category
                </span>
            </v-tooltip>
        </template>

        <v-card>
            <CardTitleWithButtons title="Duplicate category"></CardTitleWithButtons>

            <v-card-text>
                <v-select
                    v-model="currency"
                    :items="currencies.currencies"
                    label="Currency"
                    item-title="ISO"
                    item-value="id"
                    variant="underlined"
                    hide-details
                ></v-select>
            </v-card-text>

            <CardActionsSubmitComponent
                :loading="!!loading.submit"
                :can-submit="true"
                @submit="duplicate"
            ></CardActionsSubmitComponent>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref } from "vue"

import { useStatusStore } from "@stores/status"
import { useCurrenciesStore } from "@stores/currencies"
import { useDialogSettings } from "@composables/useDialogSettings"

import CardTitleWithButtons from "@components/common/CardTitleWithButtons.vue"
import CardActionsSubmitComponent from "@components/common/card-actions/CardActionsSubmitComponent.vue"

const props = defineProps<{
    id: number
}>()

const emit = defineEmits<{
    duplicated: []
}>()

const currencies = useCurrenciesStore()
const status = useStatusStore()
const currency = ref(currencies.usedCurrency)

function duplicate() {
    loading.value.submit = true

    axios.post(
        `/web-api/categories/category/${props.id}/duplicate`,
        {
            currency: currency.value,
        })
        .then(() => {
            emit("duplicated")
            dialog.value = false
            loading.value.submit = false
        })
        .catch(err => {
            console.error(err)
            setTimeout(() => status.showError(), 1000)
            setTimeout(() => loading.value.submit = false, 2000)
        })
}

const {dialog, loading} = useDialogSettings()
</script>
