<template>
    <v-app>
        <NavbarComponent></NavbarComponent>

        <v-main style="margin-top: 64px">
            <v-row class="ma-4" no-gutters>
                <v-col cols="12" md="8" offset-md="2" lg="6" offset-lg="3" xl="4" offset-xl="4">
                    <v-card>
                        <v-form v-model="canSubmit" ref="form">
                            <v-card-title class="justify-center text-h5 pb-lg-0">Reset password</v-card-title>

                            <v-card-text class="pb-2">
                                <v-row no-gutters>
                                    <v-col cols="12" sm="6" offset-sm="3">
                                        <v-text-field
                                            prepend-icon="mdi-email"
                                            type="email"
                                            label="E-Mail Address"
                                            v-model="data.email"
                                            @input="validateCredentials"
                                            :rules="[
                                                validation.email(),
                                                () => correctCredentials || `We can't find a user with that email address.`
                                            ]"
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-card-text>

                            <v-card-actions class="d-flex justify-center">
                                <v-btn color="success" outlined :disabled="!canSubmit || loading || disable" @click="submit" :loading="loading">
                                    Send reset password link
                                </v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-col>
            </v-row>
        </v-main>

        <v-snackbar v-model="success" color="success" bottom>
            We have emailed your password reset link!

            <template v-slot:action="{ attrs }">
                <v-btn
                    text
                    v-bind="attrs"
                    @click="success = false"
                >
                    Close
                </v-btn>
            </template>
        </v-snackbar>

        <ErrorSnackbarComponent v-model="error"></ErrorSnackbarComponent>
    </v-app>
</template>

<script>
import NavbarComponent from "@/home/NavbarComponent.vue";
import ErrorSnackbarComponent from "@/ErrorSnackbarComponent.vue";
import validation from "&/mixins/validation";

export default {
    mixins: [validation],
    components: {
        NavbarComponent,
        ErrorSnackbarComponent
    },
    data() {
        return {
            data: {},
            show: false,
            loading: false,
            canSubmit: true,
            correctCredentials: true,
            success: false,
            error: false,
            disable: false
        }
    },
    methods: {
        submit() {
            this.loading = true;

            axios.post("/password/email", this.data)
                .then(() => {
                    this.success = true;
                    this.loading = false;
                    this.disable = true;
                })
                .catch(err => {
                    if (err.response.status == 422 && err.response.data.errors.email.includes("We can't find a user with that email address.")) {
                        this.correctCredentials = false;
                        this.$refs.form.validate();
                        this.loading = false;
                    }
                    else {
                        console.error(err);
                        setTimeout(() => this.error = true, 1000);
                        setTimeout(() => this.loading = false, 2000);
                    }
                })
        },
        validateCredentials() {
            if (!this.correctCredentials) {
                this.correctCredentials = true;
                this.$refs.form.validate();
            }
        }
    }
};
</script>
