<template>
    <v-dialog v-model="dialog" max-width="500">
        <template v-slot:activator="{ on, attrs }">
            <v-btn outlined class="mx-3 my-2" width="145" :block="$vuetify.breakpoint.xs" v-on="on" v-bind="attrs">Settings</v-btn>
        </template>

        <v-card>
            <v-card-title>Settings</v-card-title>

            <v-card-text>
                <v-form v-model="canSubmit" ref="form">
                    <v-row>
                        <v-col cols="6">
                            <v-switch :color="$vuetify.theme.dark ? 'white' : 'grey'" v-model="data.darkmode">
                                <template v-slot:label>
                                    Darkmode
                                </template>
                            </v-switch>
                        </v-col>

                        <v-col cols="6">
                            <v-switch :color="$vuetify.theme.dark ? 'white' : 'grey'" v-model="data.show_tutorials">
                                <template v-slot:label>
                                    Show tutorials
                                </template>
                            </v-switch>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <v-card-actions class="d-flex justify-space-around">
                <v-btn color="error" outlined @click="reset" :disabled="loading">
                    Reset
                </v-btn>

                <v-btn color="success" outlined :disabled="!canSubmit || loading" @click="update" :loading="loading">
                    Update
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
        value: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            dialog: false,
            data: {},

            error: false,
            loading: false,
            canSubmit: false
        }
    },
    methods: {
        reset() {
            this.data = {
                darkmode: this.value.darkmode,
                show_tutorials: !this.value.hide_all_tutorials
            };
        },
        update() {
            this.loading = true;

            axios
                .post("/web-api/profile/settings", this.data)
                .then(() => {
                    this.value.darkmode = this.data.darkmode;
                    this.value.hide_all_tutorials = !this.data.show_tutorials;

                    this.dialog = false;
                    this.loading = false;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.loading = false, 2000);
                })
        }
    },
    mounted() {
        this.reset();
    }
}
</script>
