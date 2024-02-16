<template>
    <v-dialog
        v-model="dialog"
        max-width="600"
        persistent
    >
        <template v-slot:activator="{ props: dialogProps }">
            <v-btn
                v-bind="dialogProps"
                :disabled="props.disabled || !(cashAccount && props.accountIDs.includes(cashAccount))"
                class="mx-1"
                width="90"
                variant="outlined"
            >
                Cash
            </v-btn>
        </template>

        <v-card v-if="ready">
            <CardTitleWithButtons title="Set cash"></CardTitleWithButtons>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <div v-for="id in Object.keys(cash)">
                        <v-row>
                            <v-col
                                cols="12"
                                sm="4"
                                class="d-flex flex-wrap flex-column align-center"
                                style="overflow-x: hidden"
                            >
                                <div class="text-caption">
                                    Value
                                </div>

                                <div
                                    class="text-h5"
                                    style="overflow-x: hidden"
                                >
                                    {{ format.numberWithCurrency(cash[id], currencies.usedCurrencyObject.ISO) }}
                                </div>
                            </v-col>

                            <v-col
                                cols="12"
                                sm="4"
                            >
                                <v-text-field
                                    v-model="model[id]"
                                    :rules="[
                                        Validator.cash(ownedCash[id], props.type)
                                    ]"
                                    label="Amount"
                                    variant="underlined"
                                ></v-text-field>
                            </v-col>

                            <v-col
                                cols="12"
                                sm="4"
                                class="d-flex flex-wrap flex-column align-center"
                                style="overflow-x: hidden"
                            >
                                <div class="text-caption">
                                    Sum
                                </div>

                                <div
                                    class="text-h5"
                                    style="overflow-x: hidden"
                                >
                                    {{
                                        format.numberWithCurrency(
                                            model[id] * cash[id] || 0,
                                            currencies.usedCurrencyObject.ISO,
                                        )
                                    }}
                                </div>
                            </v-col>

                            <v-divider
                                v-if="xs"
                                class="my-4"
                            ></v-divider>
                        </v-row>
                    </div>
                </v-form>

                <v-divider
                    v-if="smAndUp"
                    class="my-4 mt-sm-0"
                ></v-divider>

                <v-row>
                    <v-col
                        cols="12"
                        sm="4"
                        class="d-flex flex-wrap flex-column align-center"
                        style="overflow-x: hidden"
                    >
                        <div class="text-caption">
                            Sum
                        </div>

                        <div
                            class="text-h5"
                            style="overflow-x: hidden"
                        >
                            {{
                                format.numberWithCurrency(sum, currencies.usedCurrencyObject.ISO)
                            }}
                        </div>
                    </v-col>

                    <v-col
                        cols="12"
                        sm="4"
                        class="d-flex flex-wrap flex-column align-center"
                        style="overflow-x: hidden"
                    >
                        <div class="text-caption">
                            Current balance
                        </div>

                        <div
                            class="text-h5"
                            style="overflow-x: hidden"
                        >
                            {{
                                format.numberWithCurrency(props.sumByAccount[cashAccount], currencies.usedCurrencyObject.ISO)
                            }}
                        </div>
                    </v-col>

                    <v-col
                        cols="12"
                        sm="4"
                        class="d-flex flex-wrap flex-column align-center"
                        style="overflow-x: hidden"
                    >
                        <div class="text-caption">
                            Difference
                        </div>

                        <div
                            class="text-h5"
                            style="overflow-x: hidden"
                            :class="differenceColor"
                        >
                            {{
                                format.numberWithCurrency(
                                    sum - props.sumByAccount[cashAccount],
                                    currencies.usedCurrencyObject.ISO,
                                    true,
                                )
                            }}
                        </div>
                    </v-col>
                </v-row>
            </v-card-text>

            <CardActionsSubmitComponent
                :loading="loading.submit"
                :can-submit="canSubmit"
                @submit="dialog = false"
            ></CardActionsSubmitComponent>
        </v-card>

        <CardLoadingComponent
            v-else
            title="Set cash"
        ></CardLoadingComponent>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { computed, onMounted, ref, watch } from "vue"
import { useDisplay } from "vuetify"

import { useDialogSettings } from "@composables/useDialogSettings"
import { useCurrenciesStore } from "@stores/currencies"
import { useStatusStore } from "@stores/status"
import useFormats from "@composables/useFormats"
import Validator from "@classes/Validator"

const model = defineModel<Record<string, number>>({
    default: {},
})

const props = defineProps<{
    type: "income" | "expenses"
    accountIDs: number[],
    disabled: boolean,
    sumByAccount: Record<number, number>,
}>()

const currencies = useCurrenciesStore()
const status = useStatusStore()

function useCash() {
    const ready = ref(false)

    const cash = ref<Record<string, number>>({})

    const cashAccount = ref<number>(0)

    const ownedCash = ref<Record<string, number>>({})

    const sum = computed(() => Object.keys(cash.value)
        .map(item => cash.value[item] * model.value[item] || 0)
        .reduce((carry, item) => carry + item, 0),
    )

    const differenceColor = computed(() =>
        sum.value - props.sumByAccount[cashAccount.value] != 0 ?
            "text-error" :
            "text-success",
    )

    function getData() {
        ready.value = false

        axios.get(`/web-api/extensions/cash/${currencies.usedCurrency}`)
            .then(response => {
                const data = response.data

                cash.value = data.cash
                cashAccount.value = data.cashAccount
                ownedCash.value = data.ownedCash

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
            })
    }

    return {ready, cash, cashAccount, ownedCash, sum, differenceColor, getData}
}

const format = useFormats()
const {xs, smAndUp} = useDisplay()
const {dialog, loading, canSubmit} = useDialogSettings()
const {ready, cash, cashAccount, ownedCash, sum, differenceColor, getData} = useCash()

watch(() => props.sumByAccount, () => {
    if (typeof props.sumByAccount[cashAccount.value] == "undefined") {
        model.value = {}
    }
})

onMounted(getData)
</script>
