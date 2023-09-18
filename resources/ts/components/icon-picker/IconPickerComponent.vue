<template>
    <v-dialog
        v-model="dialog"
        max-width="300"
        :persistent="true"
    >
        <template v-slot:activator="{ props }: any">
            <v-btn
                v-bind="props"
                variant="outlined"
            >
                Select Icon
            </v-btn>
        </template>

        <v-card v-if="ready">
            <CardTitleWithButtons title="Select Icon"></CardTitleWithButtons>

            <v-card-text>
                <v-row class="mb-0">
                    <v-col
                        v-for="item in icons"
                        class="d-flex justify-center"
                        cols="3"
                    >
                        <v-btn
                            icon
                            variant="text"
                            :color="props.modelValue == item ? 'primary' : undefined"
                            @click="emit('update:modelValue', item)"
                            density="comfortable"
                        >
                            <v-icon :icon="item"></v-icon>
                        </v-btn>
                    </v-col>
                </v-row>

                <SupportedIconSetsComponent></SupportedIconSetsComponent>

                <v-form v-model="canSubmit">
                    <v-text-field
                        v-model="props.modelValue"
                        :rules="[
                            Validator.title('Icon', 64, true)
                        ]"
                        label="Icon name"
                        counter="64"
                        variant="underlined"
                        @input="emit('update:modelValue', props.modelValue)"
                    >
                        <template v-slot:append>
                            <v-icon style="min-width: 26px">
                                {{ props.modelValue }}
                            </v-icon>
                        </template>
                    </v-text-field>
                </v-form>
            </v-card-text>

            <CardActionsSubmitComponent
                :loading="false"
                :can-submit="canSubmit"
                @submit="dialog = false"
            ></CardActionsSubmitComponent>
        </v-card>

        <CardLoadingComponent v-else>
            Select Icon
        </CardLoadingComponent>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref, watch } from "vue"
import type { Ref } from "vue"

import CardTitleWithButtons from "@components/common/CardTitleWithButtons.vue"
import SupportedIconSetsComponent from "@components/icon-picker/SupportedIconSetsComponent.vue"

import { useStatusStore } from "@stores/status"
import { useDialogSettings } from "@composables/useDialogSettings"
import Validator from "@classes/Validator"
import CardLoadingComponent from "@components/common/CardLoadingComponent.vue"
import CardActionsSubmitComponent from "@components/common/card-actions/CardActionsSubmitComponent.vue"

const props = defineProps<{
    modelValue?: string
    type: "categories" | "accounts"
}>()

const emit = defineEmits<{
    "update:modelValue": [payload: string]
}>()

const status = useStatusStore()

function useData() {
    const icons: Ref<string[]> = ref([])

    function getData() {
        if (!dialog.value) return

        ready.value = false

        axios.get(`/web-api/${props.type}/icons`)
            .then(response => {
                const data = response.data

                icons.value = data.icons

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
            })
    }

    return {getData, icons}
}

const {canSubmit, dialog, ready} = useDialogSettings()
const {getData, icons} = useData()

watch(dialog, getData)
</script>
