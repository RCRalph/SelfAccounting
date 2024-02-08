<template>
    <v-card style="padding: 0">
        <router-link
            :to="`/extensions/${props.extension.directory}`"
            style="text-decoration: none; color: inherit;"
        >
            <v-img
                :src="props.extension.thumbnail"
                height="250"
                cover
            ></v-img>

            <v-card-title class="d-flex justify-center">{{ props.extension.title }}</v-card-title>

            <v-card-text>
                <div class="text-center">{{ props.extension.short_description }}</div>
            </v-card-text>
        </router-link>

        <v-card-actions>
            <v-row v-if="props.extension.bought">
                <v-col
                    cols="12"
                    sm="6"
                    md="4"
                    offset-md="2"
                >
                    <v-btn
                        :color="props.extension.enabled ? 'error' : 'success'"
                        :loading="loading.enabled"
                        :disabled="loading.enabled"
                        :text="props.extension.enabled ? 'Disable' : 'Enable'"
                        variant="outlined"
                        block
                        @click="toggleEnabled"
                    ></v-btn>
                </v-col>

                <v-col
                    cols="12"
                    sm="6"
                    md="4"
                >
                    <ExtensionDetailsDialogComponent
                        :extension="extension"
                    ></ExtensionDetailsDialogComponent>
                </v-col>
            </v-row>

            <v-row v-else>
                <v-col cols="12" sm="4">
                    <v-tooltip
                        :text="formats.numberWithCurrency(extension.price, 'EUR')"
                        location="bottom"
                    >
                        <template v-slot:activator="{ props: tooltipProps }">
                            <v-btn
                                v-bind="tooltipProps"
                                :to="`/payment/extensions/${extension.id}`"
                                color="primary"
                                variant="outlined"
                                text="Buy"
                                block
                            ></v-btn>
                        </template>
                    </v-tooltip>
                </v-col>

                <v-col cols="12" sm="4">
                    <ExtensionDetailsDialogComponent
                        :extension="extension"
                    ></ExtensionDetailsDialogComponent>
                </v-col>

                <v-col cols="12" sm="4">
                    <v-tooltip
                        v-if="user.isPremium"
                        :text="`${extension.enabled ? 'Disable' : 'Enable'} with Premium`"
                        location="bottom"
                    >
                        <template v-slot:activator="{ props: tooltipProps }">
                            <v-btn
                                v-bind="tooltipProps"
                                :text="extension.enabled ? 'Disable' : 'Enable'"
                                :loading="loading.enabled"
                                :disabled="loading.enabled"
                                :color="props.extension.enabled ? 'error' : 'success'"
                                variant="outlined"
                                block
                                @click="toggleEnabled"
                            ></v-btn>
                        </template>
                    </v-tooltip>

                    <v-tooltip
                        v-else
                        text="Premium account is required"
                        location="bottom"
                    >
                        <template v-slot:activator="{ props: tooltipProps }">
                            <v-btn
                                v-bind="tooltipProps"
                                text="Enable"
                                color="success"
                                variant="outlined"
                                block
                                disabled
                            ></v-btn>
                        </template>
                    </v-tooltip>
                </v-col>
            </v-row>
        </v-card-actions>
    </v-card>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref } from "vue"
import type { Extension, ExtensionData } from "@interfaces/Extension"
import type { Loading } from "@interfaces/App"

import { useUserStore } from "@stores/user"
import { useExtensionsStore } from "@stores/extensions"
import { useStatusStore } from "@stores/status"
import useFormats from "@composables/useFormats"

import ExtensionDetailsDialogComponent from "@components/extensions/store/ExtensionDetailsDialogComponent.vue"

const user = useUserStore()
const extensions = useExtensionsStore()
const status = useStatusStore()
const formats = useFormats()

const props = defineProps<{
    extension: Extension & ExtensionData
}>()

function useSettings() {
    const loading = ref<Loading>({
        enabled: false,
    })

    function toggleEnabled() {
        loading.value.enabled = true

        axios.post(`/web-api/extensions/${props.extension.code}/toggle-enabled`)
            .then(() => {
                extensions.toggleExtensionEnabled(props.extension.code)
                loading.value.enabled = false
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.enabled = false, 2000)
            })
    }

    return {loading, toggleEnabled}
}

const {loading, toggleEnabled} = useSettings()
</script>
