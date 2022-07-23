<template>
    <v-dialog v-model="dialog" max-width="800">
        <template v-slot:activator="{ on, attrs }">
            <v-btn outlined block v-on="on" v-bind="attrs">Details</v-btn>
        </template>

        <v-card v-if="ready">

        </v-card>

        <v-card v-else>
            <v-card-title>Extension details</v-card-title>

            <v-card-text class="d-flex justify-center">
                <v-progress-circular
                    indeterminate
                    size="96"
                ></v-progress-circular>
            </v-card-text>
        </v-card>

        <ErrorSnackbarComponent v-model="error"></ErrorSnackbarComponent>
    </v-dialog>
</template>

<script>
import ErrorSnackbarComponent from "@/ErrorSnackbarComponent.vue";

import { useExtensionsStore } from "&/stores/extensions";

export default {
    setup() {
        const extensions = useExtensionsStore();

        return { extensions };
    },
    components: {
        ErrorSnackbarComponent
    },
    data() {
        return {
            dialog: false,
            error: false,
            ready: false
        }
    }
}
</script>
