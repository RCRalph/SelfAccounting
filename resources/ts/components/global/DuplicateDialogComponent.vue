<template>
    <v-dialog
        v-model="dialog"
        max-width="300"
    >
        <template v-slot:activator="{ props: dialogProps }">
            <v-tooltip location="bottom">
                <template v-slot:activator="{ props: tooltipProps }">
                    <v-icon
                        v-bind="{ ...dialogProps, ...tooltipProps }"
                        class="mx-1 cursor-pointer"
                        icon="mdi-content-duplicate"
                    ></v-icon>
                </template>

                <span>
                    Duplicate {{ props.thing }}
                </span>
            </v-tooltip>
        </template>

        <v-card>
            <CardTitleWithButtons :title="`Duplicate ${props.thing}`"></CardTitleWithButtons>

            <v-card-text>
                <v-select
                    v-if="props.specifyCurrency"
                    v-model="currency"
                    :items="currencies.selectCurrenciesWithout(currencies.usedCurrency)"
                    label="Currency"
                    item-title="ISO"
                    item-value="id"
                    variant="underlined"
                    hide-details
                ></v-select>

                <div
                    v-else
                    class="text-h7 mx-5"
                >
                    Are you sure you want to duplicate this {{ props.thing }}?
                </div>
            </v-card-text>

            <CardActionsSubmitComponent
                :loading="!!loading.submit"
                :can-submit="!props.specifyCurrency || !!currency"
                @submit="duplicate"
            ></CardActionsSubmitComponent>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref } from "vue"

import { useStatusStore } from "@stores/status"
import { useDialogSettings } from "@composables/useDialogSettings"
import { useCurrenciesStore } from "@stores/currencies"

const props = defineProps<{
    url: string,
    thing: string,
    specifyCurrency: boolean
}>()

const emit = defineEmits<{
    duplicated: [currency?: number]
}>()

const currencies = useCurrenciesStore()
const status = useStatusStore()
const {dialog, loading} = useDialogSettings()

const currency = ref<number>()

function duplicate() {
    const data: Record<string, unknown> = {}

    if (props.specifyCurrency) {
        data.currency = currency.value
    }

    loading.value.submit = true

    axios.post(`/web-api/${props.url}`, data)
        .then(() => {
            emit("duplicated", currency.value)
            dialog.value = false
            loading.value.submit = false
        })
        .catch(err => {
            console.error(err)
            setTimeout(() => status.showError(), 1000)
            setTimeout(() => loading.value.submit = false, 2000)
        })
}
</script>