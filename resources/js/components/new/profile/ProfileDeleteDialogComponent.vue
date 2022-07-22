<template>
    <v-dialog v-model="dialog" width="unset">
        <template v-slot:activator="{ on, attrs }">
            <v-btn outlined class="mx-3 my-2" :block="$vuetify.breakpoint.xs" color="error" v-bind="attrs" v-on="on">Delete account</v-btn>
        </template>

        <v-card>
            <v-card-title>Delete account</v-card-title>

            <v-card-text>
                <h3>Are you sure you want to delete your account?</h3>
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
                .delete(`/web-api/profile`)
                .then(() => {
                    window.location = "/"

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
