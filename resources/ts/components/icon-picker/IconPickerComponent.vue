<template>
    <v-dialog
        v-model="dialog"
        max-width="300"
        persistent
    >
        <template v-slot:activator="{ props: buttonProps }">
            <v-btn
                v-bind="buttonProps"
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
                            :color="model == item ? 'primary' : undefined"
                            @click="model = item"
                            density="comfortable"
                            :icon="formats.iconName(item)"
                        ></v-btn>
                    </v-col>
                </v-row>

                <SupportedIconSetsComponent></SupportedIconSetsComponent>

                <v-form v-model="canSubmit">
                    <v-text-field
                        v-model="model"
                        :rules="[
                            Validator.title('Icon', 64, true)
                        ]"
                        label="Icon name"
                        counter="64"
                        variant="underlined"
                    >
                        <template v-slot:append>
                            <v-icon
                                v-if="model"
                                style="min-width: 26px"
                            >
                                {{ formats.iconName(model) }}
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
import { ref, watch } from "vue"

import SupportedIconSetsComponent from "@components/icon-picker/SupportedIconSetsComponent.vue"

import { useStatusStore } from "@stores/status"
import { useDialogSettings } from "@composables/useDialogSettings"
import useFormats from "@composables/useFormats"
import Validator from "@classes/Validator"

const props = defineProps<{
    type: "categories" | "accounts"
}>()

const model = defineModel<string | null>({
    default: null,
})

const status = useStatusStore()
const formats = useFormats()

function useData() {
    const icons = ref<string[]>([])

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
