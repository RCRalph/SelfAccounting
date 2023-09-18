<template>
    <v-dialog v-model="dialog" max-width="350">
        <template v-slot:activator="{ props }: any">
            <v-btn
                v-bind="props"
                :block="true"
                variant="plain"
                color="primary"
                class="mb-3"
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

                        <div>
                            <span class="font-weight-bold">Usage:</span> {{ item.usage }}
                        </div>

                        <div>
                            <span class="font-weight-bold">Example:</span> {{ item.example }}
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
import CardTitleWithButtons from "@components/common/CardTitleWithButtons.vue"
import { useDialogSettings } from "@composables/useDialogSettings"

const iconSets = ref([
    {
        name: "Material Design Icons",
        website: "https://pictogrammers.com/library/mdi",
        usage: "mdi-[icon name]",
        example: "mdi-bike-fast",
    },
    {
        name: "Font Awesome 5",
        website: "https://fontawesome.com/v5/search",
        usage: "fas/far/fab fa-[icon name]",
        example: "fas fa-fighter-jet",
    },
])

const {dialog} = useDialogSettings()
</script>