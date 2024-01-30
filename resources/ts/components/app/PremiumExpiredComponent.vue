<template>
    <v-dialog
        :model-value="props.modelValue"
        max-width="550"
        persistent
    >
        <v-card>
            <v-card-title class="justify-center text-h5 mt-2 font-weight-bold">
                <div style="word-break: break-word" class="text-center">
                    Your SelfAccounting Premium has expired
                </div>
            </v-card-title>

            <v-card-text class="text-center py-2">
                To renew your Premium subscription, click the button below:
            </v-card-text>

            <v-card-actions class="d-flex justify-space-around flex-wrap flex-md-row flex-column-reverse">
                <v-btn
                    :block="display.smAndDown.value"
                    variant="outlined"
                    class="mx-0 my-1"
                    width="80"
                    @click="emit('update:modelValue', false)"
                >
                    Close
                </v-btn>

                <v-btn
                    :block="display.smAndDown.value"
                    variant="outlined"
                    class="mx-0 my-1"
                    width="80"
                    color="success"
                    disabled
                    @click="emit('update:modelValue', false)"
                >
                    Renew
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { useDisplay } from "vuetify"

const display = useDisplay()

const props = defineProps<{
    modelValue: boolean
}>()

const emit = defineEmits<{
    "update:modelValue": [payload: boolean]
}>()
</script>
