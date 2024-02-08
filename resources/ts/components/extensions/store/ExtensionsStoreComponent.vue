<template>
    <v-row v-if="ready">
        <v-col
            v-for="extension in extensionItems"
            xl="3"
            lg="4"
            md="6"
            cols="12"
        >
            <ExtensionCardComponent
                :extension="extension"
            ></ExtensionCardComponent>
        </v-col>
    </v-row>

    <v-overlay
        v-else
        :model-value="true"
        contained
    >
        <v-progress-circular
            indeterminate
            size="128"
        ></v-progress-circular>
    </v-overlay>
</template>

<script setup lang="ts">
import axios from "axios"
import { computed, onMounted, ref } from "vue"
import type { ExtensionData } from "@interfaces/Extension"

import { useExtensionsStore } from "@stores/extensions"
import ExtensionCardComponent from "@components/extensions/store/ExtensionCardComponent.vue"

const extensions = useExtensionsStore()

function useData() {
    const ready = ref(false)

    const extensionData = ref<Record<string, ExtensionData>>({})

    function getExtensionData() {
        ready.value = false

        axios.get("/web-api/extensions")
            .then(response => {
                const data = response.data

                for (let item of data.extensions) {
                    extensionData.value[item.code] = item
                }

                ready.value = true
            })
    }

    const extensionItems = computed(() => extensions.extensionCodes.map(code => ({
        ...extensionData.value[code],
        ...extensions.extensionMap[code],
    })))

    return {ready, getExtensionData, extensionItems}
}

const {ready, getExtensionData, extensionItems} = useData()

onMounted(getExtensionData)
</script>
