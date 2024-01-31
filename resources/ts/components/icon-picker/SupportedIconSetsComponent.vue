<template>
    <v-dialog v-model="dialog" max-width="375">
        <template v-slot:activator="{ props }">
            <v-btn
                v-bind="props"
                variant="plain"
                color="primary"
                class="mb-3"
                block
            >
                Supported Icon Sets
            </v-btn>
        </template>

        <v-card>
            <CardTitleWithButtons title="Supported Icon Sets"></CardTitleWithButtons>

            <v-card-text class="pt-1">
                <v-row>
                    <v-col
                        v-for="item in iconSets"
                        cols="12"
                        class="text-center"
                    >
                        <div>
                            <a
                                :href="item.website"
                                target="_blank"
                                class="text-primary"
                            >
                                {{ item.name }}
                            </a>
                        </div>

                        <div class="mb-1">
                            <div class="font-weight-bold">
                                Usage:
                            </div>

                            {{ item.usage }}
                        </div>

                        <div>
                            <span class="font-weight-bold">Examples:</span>

                            <ul class="list-without-bullets">
                                <li v-for="example in item.examples">
                                    {{ example }}
                                </li>
                            </ul>
                        </div>
                    </v-col>
                </v-row>
            </v-card-text>

            <v-card-actions class="d-flex justify-center">
                <v-btn
                    variant="outlined"
                    @click="dialog = false"
                    text="Close"
                ></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { ref } from "vue"
import { useDialogSettings } from "@composables/useDialogSettings"

const iconSets = ref([
    {
        name: "Material Design Icons",
        website: "https://pictogrammers.com/library/mdi",
        usage: "mdi-[icon name]",
        examples: [
            "mdi-bike-fast",
            "mdi-train",
            "mdi-cash-multiple",
        ],
    },
    {
        name: "Font Awesome 6",
        website: "https://fontawesome.com/v6/search",
        usage: "fa-[solid/regular/brands] fa-[icon name]",
        examples: [
            "fa-solid fa-fighter-jet",
            "fa-regular fa-wand-magic-sparkles",
            "fa-brands fa-github",
        ],
    },
])

const {dialog} = useDialogSettings()
</script>