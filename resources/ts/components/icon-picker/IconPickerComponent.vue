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
                :append-icon="formats.iconName(icon)"
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
                            variant="text"
                            :color="icon == item ? 'primary' : undefined"
                            @click="icon = item"
                            density="comfortable"
                            :icon="formats.iconName(item)"
                        ></v-btn>
                    </v-col>
                </v-row>

                <SupportedIconSetsComponent></SupportedIconSetsComponent>

                <v-form v-model="canSubmit">
                    <v-text-field
                        v-model="icon"
                        :rules="[
                            Validator.title('Icon', 64, true)
                        ]"
                        label="Icon name"
                        counter="64"
                        variant="underlined"
                    >
                        <template v-slot:append>
                            <v-icon
                                v-if="props.modelValue"
                                style="min-width: 26px"
                            >
                                {{ formats.iconName(props.modelValue) }}
                            </v-icon>
                        </template>
                    </v-text-field>
                </v-form>
            </v-card-text>

            <CardActionsSubmitComponent
                :loading="false"
                :can-submit="!!canSubmit"
                @submit="dialog = false"
            ></CardActionsSubmitComponent>
        </v-card>

        <CardLoadingComponent
            v-else
            title="Select icon"
        ></CardLoadingComponent>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref, watch, computed } from "vue"
import type { Ref } from "vue"

import SupportedIconSetsComponent from "@components/icon-picker/SupportedIconSetsComponent.vue"

import { useStatusStore } from "@stores/status"
import { useDialogSettings } from "@composables/useDialogSettings"
import useFormats from "@composables/useFormats"
import Validator from "@classes/Validator"

const props = defineProps<{
    modelValue: string | null
    type: "categories" | "accounts"
}>()

const emit = defineEmits<{
    "update:modelValue": [payload: string | null]
}>()

const status = useStatusStore()
const formats = useFormats()

function useData() {
    const icons: Ref<string[]> = ref([])

    const icon = computed({
        get() {
            return props.modelValue
        },
        set(value) {
            emit("update:modelValue", value)
        },
    })

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

    return {getData, icon, icons}
}

const {canSubmit, dialog, ready} = useDialogSettings()
const {getData, icon, icons} = useData()

watch(dialog, getData)
</script>
