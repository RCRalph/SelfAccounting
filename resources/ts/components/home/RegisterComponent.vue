<template>
    <v-app>
        <NavbarComponent></NavbarComponent>

        <v-main style="margin-top: 64px">
            <v-row class="ma-4" no-gutters>
                <v-col cols="12" md="10" offset-md="1" lg="8" offset-lg="2" xl="6" offset-xl="3">
                    <v-card>
                        <v-form v-model="canSubmit" ref="form">
                            <v-card-title class="justify-center text-h5 pb-lg-0">Register</v-card-title>

                            <v-card-text>
                                <v-row>
                                    <v-col cols="12" md="6" lg="5" offset-lg="1">
                                        <v-text-field
                                            prepend-icon="mdi-account"
                                            label="Username"
                                            v-model="data.username"
                                            :rules="[validation.username()]"
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12" md="6" lg="5">
                                        <v-text-field
                                            prepend-icon="mdi-email"
                                            type="email"
                                            label="E-Mail Address"
                                            v-model="data.email"
                                            @input="validateCredentials"
                                            :rules="[
                                                validation.email(),
                                                () => uniqueEmail || 'E-Mail has already been taken'
                                            ]"
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12" md="6" lg="5" offset-lg="1">
                                        <v-text-field
                                            prepend-icon="mdi-lock"
                                            label="Password"
                                            v-model="data.password"
                                            :rules="[validation.password()]"
                                            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                                            :type="show1 ? 'text' : 'password'"
                                            counter="64"
                                            @click:append="show1 = !show1"
                                            @input="validateCredentials"
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12" md="6" lg="5">
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
                                            counter="64"
                                            @click:append="show2 = !show2"
                                            @input="validateCredentials"
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-card-text>

                            <v-card-actions class="d-flex justify-center">
                                <v-btn color="success" outlined :disabled="!canSubmit || loading" @click="submit" :loading="loading">
                                    Register
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
        redirect: {
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
            uniqueEmail: true,
            error: false
        }
    },
    methods: {
        submit() {
            this.loading = true;
            const darkmode = window.matchMedia ? window.matchMedia("(prefers-color-scheme: dark)").matches : false;

            axios
                .post("/register", { ...this.data, darkmode })
                .then(() => window.location.href = this.redirect)
                .catch(err => {
                    if (err.response.status == 422 && err.response.data.errors.email.includes("The email has already been taken.")) {
                        this.uniqueEmail = false;
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
            if (!this.uniqueEmail) {
                this.uniqueEmail = true;
                this.$refs.form.validate();
            }
        }
    }
};
</script>
