<template>
    <v-app>
        <NavbarComponent></NavbarComponent>

        <v-main style="margin-top: 64px">
            <v-row class="ma-4" no-gutters>
                <v-col cols="12" md="8" offset-md="2" lg="6" offset-lg="3" xl="4" offset-xl="4">
                    <v-card>
                        <v-form v-model="canSubmit" ref="form">
                            <v-card-title class="justify-center text-h5 pb-lg-0">Login</v-card-title>

                            <v-card-text>
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
                                                () => correctCredentials || 'These credentials do not match our records.'
                                            ]"
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12" sm="6" offset-sm="3">
                                        <v-text-field
                                            prepend-icon="mdi-lock"
                                            label="Password"
                                            v-model="data.password"
                                            :rules="[
                                                validation.password(),
                                                () => correctCredentials || 'These credentials do not match our records.'
                                            ]"
                                            :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                                            :type="show ? 'text' : 'password'"
                                            @click:append="show = !show"
                                            @input="validateCredentials"
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12" sm="6" offset-sm="3" class="d-flex justify-center align-center">
                                        <a href="/password/reset">Forgot your password?</a>
                                    </v-col>
                                </v-row>
                            </v-card-text>

                            <v-card-actions class="d-flex justify-space-between align-center mx-3">
                                <v-checkbox v-model="data.remember" label="Remember me" hide-details :color="$vuetify.theme.dark ? 'white' : 'grey'"></v-checkbox>

                                <v-btn color="success" outlined width="80" :disabled="!canSubmit || loading" @click="submit" :loading="loading">
                                    Login
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
        intended: {
            required: true,
            type: String
        }
    },
    data() {
        return {
            data: {},
            show: false,
            loading: false,
            canSubmit: true,
            correctCredentials: true,
            error: false
        }
    },
    methods: {
        submit() {
            this.loading = true;

            axios.post("/login", this.data)
                .then(() => window.location.href = this.intended)
                .catch(err => {
                    if (err.response.status == 422 && err.response.data.errors.email.includes("These credentials do not match our records.")) {
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

<style>
.v-input--selection-controls {
    margin-top: 0;
    padding-top: 0;
}
</style>
