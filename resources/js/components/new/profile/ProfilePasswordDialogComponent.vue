<template>
    <v-dialog v-model="dialog" max-width="800">
        <template v-slot:activator="{ on, attrs }">
            <v-btn outlined class="mx-3 my-2" width="145" :block="$vuetify.breakpoint.xs" v-on="on" v-bind="attrs">Password</v-btn>
        </template>

        <v-card>
            <v-card-title>Password change</v-card-title>

            <v-card-text>
                <v-form v-model="canSubmit" ref="form">
                    <v-row>
                        <v-col cols="12" md="6" offset-md="3">
                            <v-text-field
                                label="Current password"
                                v-model="data.current_password"
                                :rules="[
                                    validation.password(),
                                    () => currentPasswordMatch || `Password doesn't match our records`
                                ]"
                                :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="show1 ? 'text' : 'password'"
                                @click:append="show1 = !show1"
                                counter
                                @blur="validate"
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" md="6">
                            <v-text-field
                                label="New password"
                                v-model="data.new_password"
                                :rules="[
                                    validation.password(),
                                    password => password != data.current_password || `New password can't be the same as old password`
                                ]"
                                :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="show2 ? 'text' : 'password'"
                                @click:append="show2 = !show2"
                                counter
                                @blur="validate"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-text-field
                                label="Confirm password"
                                v-model="data.new_password_confirmation"
                                :rules="[
                                    validation.password(),
                                    password => password == data.new_password || `Password don't match`
                                ]"
                                :append-icon="show3 ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="show3 ? 'text' : 'password'"
                                @click:append="show3 = !show3"
                                counter
                                @blur="validate"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <v-card-actions class="d-flex justify-space-around">
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

import validation from "&/mixins/validation";

export default {
    mixins: [validation],
    components: {
        ErrorSnackbarComponent
    },
    data() {
        return {
            dialog: false,
            startData: {
                current_password: "",
                new_password: "",
                new_password_confirmation: ""
            },
            data: {},
            show1: false,
            show2: false,
            show3: false,

            currentPasswordMatch: true,
            error: false,
            loading: false,
            canSubmit: false
        }
    },
    watch: {
        'data.current_password'() {
            this.currentPasswordMatch = true;
        }
    },
    methods: {
        update() {
            this.loading = true;

            axios
                .post("/web-api/profile/password", this.data)
                .then(() => {
                    this.data = _.cloneDeep(this.startData);
                    this.dialog = false;
                    this.loading = false;
                })
                .catch(err => {
                    if (err.response.status == 422 && err.response.data.errors.current_password.includes("Current password doesn't match.")) {
                        this.currentPasswordMatch = false;
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
        toggleShow(index) {
            this.show[index] = !this.show[index];
        },
        validate() {
            this.$refs.form.validate();
        }
    },
    mounted() {
        this.data = _.cloneDeep(this.startData);
    }
}
</script>
