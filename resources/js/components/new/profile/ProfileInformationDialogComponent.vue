<template>
    <v-dialog v-model="dialog" max-width="800">
        <template v-slot:activator="{ on, attrs }">
            <v-btn outlined class="mx-3 my-2" width="145" :block="$vuetify.breakpoint.xs" v-on="on" v-bind="attrs">Information</v-btn>
        </template>

        <v-card>
            <v-card-title>Profile Information</v-card-title>

            <v-card-text>
                <v-form v-model="canSubmit" ref="form">
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-text-field
                                label="Username"
                                v-model="data.username"
                                counter="32"
                                :rules="[validation.username()]"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-text-field
                                type="email"
                                label="E-Mail address"
                                v-model="data.email"
                                counter="64"
                                :rules="[
                                    validation.email(),
                                    () => emailUnique || 'Email has already been taken'
                                ]"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <v-card-actions class="d-flex justify-space-around">
                <v-btn color="error" outlined @click="reset" :disabled="loading" class="mx-1" width="85">
                    Reset
                </v-btn>

                <v-btn color="success" outlined :disabled="!canSubmit || loading" @click="update" :loading="loading" class="mx-1" width="85">
                    Update
                </v-btn>
            </v-card-actions>
        </v-card>

        <ErrorSnackbarComponent v-model="error"></ErrorSnackbarComponent>
    </v-dialog>
</template>

<script>
import ErrorSnackbarComponent from "@/ErrorSnackbarComponent.vue";

import validation from "&/mixins/validation";

export default {
    mixins: [validation],
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

            emailUnique: true,
            error: false,
            loading: false,
            canSubmit: false
        }
    },
    watch: {
        'data.email'() {
            this.emailUnique = true;
        }
    },
    methods: {
        reset() {
            this.data = {
                username: this.value.username,
                email: this.value.email
            };
        },
        update() {
            this.loading = true;

            axios
                .post("/web-api/profile/information", this.data)
                .then(() => {
                    this.value.username = this.data.username;
                    this.value.email = this.data.email;

                    this.dialog = false;
                    this.loading = false;
                })
                .catch(err => {
                    if (err.response.status == 422 && err.response.data.errors.email.includes("The email has already been taken.")) {
                        this.emailUnique = false;
                        this.$refs.form.validate();
                        this.loading = false;
                    }
                    else {
                        console.error(err);
                        setTimeout(() => this.error = true, 1000);
                        setTimeout(() => this.loading = false, 2000);
                    }
                })
        }
    },
    mounted() {
        this.reset();
    }
}
</script>
