<template>
    <v-dialog v-model="dialog" width="unset">
        <template v-slot:activator="{ on, attrs }">
            <v-icon class="mr-2 cursor-pointer" v-bind="attrs" v-on="on">mdi-delete</v-icon>
        </template>

        <v-card>
            <v-card-title>Delete {{ type }}</v-card-title>

            <v-card-text>
                <h3>Are you sure you want to delete this {{ type }}?</h3>
            </v-card-text>

            <v-card-actions class="d-flex justify-space-around">
                <v-btn outlined @click="dialog = false" :disabled="loading">
                    No
                </v-btn>

                <v-btn color="error" outlined @click="remove" :loading="loading">
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
        type: String,
        id: Number
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
                .delete(`/web-api/${this.type}/${this.id}`)
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
