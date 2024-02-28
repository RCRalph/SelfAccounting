<template>
    <v-dialog
        v-model="dialog"
        max-width="500"
        persistent
    >
        <template v-slot:activator="{ props: dialogProps }">
            <v-btn
                v-bind="dialogProps"
                variant="outlined"
                text="Set account"
            ></v-btn>
        </template>

        <v-card>
            <CardTitleWithButtons title="Set Account Of Payment"></CardTitleWithButtons>

            <v-card-text>
                <v-select
                    v-model="model"
                    :items="props.accounts"
                    item-title="name"
                    item-value="id"
                    label="Account"
                >
                    <template v-slot:item="{ item, props: listItemProps }">
                        <v-list-item v-bind="listItemProps">
                            <template v-slot:prepend>
                                <v-icon v-if="item.raw.icon">
                                    {{ formats.iconName(item.raw.icon) }}
                                </v-icon>
                            </template>
                        </v-list-item>
                    </template>
                </v-select>
            </v-card-text>

            <CardActionsSubmitComponent
                :can-submit="true"
                :loading="loading.submit"
                @submit="submit"
            ></CardActionsSubmitComponent>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"

import type { Account } from "@interfaces/Cash"

import useComponentState from "@composables/useComponentState"
import { useCurrenciesStore } from "@stores/currencies"
import { useStatusStore } from "@stores/status"
import useFormats from "@composables/useFormats"

const model = defineModel<number>()

const props = defineProps<{
    accounts: Account[]
}>()

const currencies = useCurrenciesStore()
const status = useStatusStore()

function submit() {
    loading.value.submit = true

    axios.post(`/web-api/extensions/cash/${currencies.usedCurrency}`, {
        account: model.value,
    })
        .then(() => {
            dialog.value = false
            loading.value.submit = false
            status.showSuccess("updated account")
        })
        .catch(err => {
            console.error(err)
            setTimeout(() => status.showError(), 1000)
            setTimeout(() => loading.value.submit = false, 2000)
        })
}

const formats = useFormats()
const {dialog, loading} = useComponentState()
</script>
