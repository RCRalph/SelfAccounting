<template>
    <v-dialog v-model="dialog" width="unset">
        <template v-slot:activator="{ on: dialogOn, attrs: dialogAttrs }">
            <v-tooltip bottom >
                <template v-slot:activator="{ on: tooltipOn, attrs: tooltipAttrs }">
                    <v-icon class="mx-1 cursor-pointer" v-bind="{ ...dialogAttrs, ...tooltipAttrs }" v-on="{ ...dialogOn, ...tooltipOn }">mdi-delete</v-icon>
                </template>

                <span>Delete {{ thing }}</span>
            </v-tooltip>
        </template>

        <v-card>
            <v-card-title>Delete {{ thing }}</v-card-title>

            <v-card-text>
                <h3>Are you sure you want to delete this {{ thing }}?</h3>
            </v-card-text>

            <v-card-actions class="d-flex justify-space-around">
                <v-btn outlined @click="dialog = false" :disabled="loading" class="mx-1">
                    No
                </v-btn>

                <v-btn color="error" outlined @click="remove" :loading="loading" class="mx-1">
                    Yes
                </v-btn>
            </v-card-actions>
        </v-card>

        <ErrorSnackbarComponent v-model="error"></ErrorSnackbarComponent>
    </v-dialog>
</template>

<script>
import ErrorSnackbarComponent from "@/ErrorSnackbarComponent.vue";

export default {
    components: {
        ErrorSnackbarComponent
    },
    props: {
        thing: {
            required: true,
            type: String
        },
        url: {
            required: true,
            type: String
        }
    },
    data() {
        return {
            dialog: false,
            error: false,
            loading: false
        }
    },
    methods: {
        remove() {
            this.loading = true;

            axios
                .delete(`/web-api/${this.url}`)
                .then(() => {
                    this.$emit("deleted");
                    this.dialog = false;
                    this.loading = false;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.loading = false, 2000);
                })
        }
    }
}
</script>
