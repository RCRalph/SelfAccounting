<template>
    <v-dialog v-model="dialog" width="unset">
        <template v-slot:activator="{ on, attrs }">
            <v-btn
                outlined
                :class="$vuetify.breakpoint.xs && 'mt-2'"
                v-bind="attrs" v-on="on"
            >
                Convert to {{ type == 'expences' ? 'income' : 'expence' }}
            </v-btn>
        </template>

        <v-card>
            <v-card-title>Convert to {{ type == 'expences' ? 'income' : 'expence' }}</v-card-title>

            <v-card-text>
                <h3>Are you sure you want to convert this {{ type == 'expences' ? 'expence' : 'income' }} to {{ type == 'expences' ? 'income' : 'expence' }}?</h3>
            </v-card-text>

            <v-card-actions class="d-flex justify-space-around">
                <v-btn outlined @click="dialog = false" :disabled="loading" class="mx-1">
                    No
                </v-btn>

                <v-btn color="success" outlined @click="remove" :disabled="loading" :loading="loading" class="mx-1">
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
                .post(`/web-api/${this.type}/${this.id}/convert`)
                .then(() => {
                    this.$emit("converted");
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
