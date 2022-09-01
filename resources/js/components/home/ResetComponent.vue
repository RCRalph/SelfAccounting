<template>
    <v-app>
        <NavbarComponent></NavbarComponent>

        <v-main style="margin-top: 64px">
            <v-row class="ma-4" no-gutters>
                <v-col cols="12" md="8" offset-md="2" lg="6" offset-lg="3" xl="4" offset-xl="4">
                    <v-card>
                        <v-form v-model="canSubmit" ref="form">
                            <v-card-title class="justify-center text-h5 pb-lg-0">Reset password</v-card-title>

                            <v-card-text>
                                <v-row no-gutters>
                                    <v-col cols="12" sm="6" offset-sm="3">
                                        <v-text-field
                                            prepend-icon="mdi-lock"
                                            label="Password"
                                            v-model="data.password"
                                            :rules="[validation.password()]"
                                            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                                            :type="show1 ? 'text' : 'password'"
                                            @click:append="show1 = !show1"
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12" sm="6" offset-sm="3">
                                        <v-text-field
                                            prepend-icon="mdi-lock"
                                            label="Confirm password"
                                            v-model="data.password_confirmation"
                                            :rules="[
                                                validation.password(),
                                                password => password == data.password || `Passwords don't match`
                                            ]"
                                            :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                                            :type="show2 ? 'text' : 'password'"
                                            @click:append="show2 = !show2"
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-card-text>

                            <v-card-actions class="d-flex justify-center">
                                <v-btn color="success" outlined :disabled="!canSubmit || loading" @click="submit" :loading="loading">
                                    Reset password
                                </v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-col>
            </v-row>
        </v-main>

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
    props: {
        token: {
            required: true,
            type: String
        },
        email: {
            required: true,
            type: String
        }
    },
    data() {
        return {
            data: {},
            show1: false,
            show2: false,
            loading: false,
            canSubmit: true,
            error: false
        }
    },
    methods: {
        submit() {
            this.loading = true;

            axios.post("/password/reset", { ...this.data, token: this.token, email: this.email })
                .then(() => window.location.href = "/app")
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.loading = false, 2000);
                })
        }
    }
};
</script>
