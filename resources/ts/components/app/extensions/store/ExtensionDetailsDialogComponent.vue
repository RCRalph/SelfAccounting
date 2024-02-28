<template>
    <v-dialog
        v-model="dialog"
        max-width="800"
    >
        <template v-slot:activator="{ props: dialogProps }">
            <v-btn
                v-bind="dialogProps"
                variant="outlined"
                block
            >
                Details
            </v-btn>
        </template>

        <v-card>
            <v-card-title class="justify-center mb-3">
                {{ props.extension.title }}
            </v-card-title>

            <v-carousel
                height="400"
                show-arrows="hover"
                cover
                cycle
                hide-delimiters
                progress
            >
                <v-carousel-item
                    v-for="image in props.extension.gallery"
                    :src="image"
                ></v-carousel-item>
            </v-carousel>

            <v-card-text
                v-html="props.extension.description"
                class="mt-6 extension-description"
            ></v-card-text>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import type { Extension, ExtensionData } from "@interfaces/Extension"
import useComponentState from "@composables/useComponentState"

const props = defineProps<{
    extension: Extension & ExtensionData
}>()

const {dialog} = useComponentState()
</script>
