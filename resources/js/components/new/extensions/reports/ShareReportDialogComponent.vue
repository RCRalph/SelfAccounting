<template>
    <v-dialog v-model="dialog" max-width="500" persistent>
        <template v-slot:activator="{ on, attrs }">
            <v-btn outlined v-bind="attrs" v-on="on">Share</v-btn>
        </template>

        <v-card>
            <v-card-title>Share</v-card-title>

            <v-card-text v-if="users.length" class="d-flex justify-center flex-wrap">
                <v-chip
                    v-for="(item, i) in users"
                    :key="i"
                    pill close outlined large
                    class="large-chip-avatar"
                    @click:close="removeUser(item.email)"
                >
                    <v-avatar left>
                        <v-img :src="item.profile_picture_link"></v-img>
                    </v-avatar>

                    {{ item.username }}
                </v-chip>
            </v-card-text>

            <v-card-text v-else class="text-center text-h6 pb-0">Add a user by entering an e-mail&nbsp;address&nbsp;below</v-card-text>

            <v-card-text>
                <v-row no-gutters>
                    <v-col cols="12" sm="6" offset-sm="2">
                        <v-form ref="form" v-model="canAdd">
                            <v-text-field
                                type="email"
                                label="E-Mail address"
                                v-model="email"
                                counter="64"
                                :rules="[
                                    validation.email(),
                                    () => emailExists || `This user doesn't exist`,
                                    email => !users.map(item => item.email).includes(email) || `This user is already added`
                                ]"
                            ></v-text-field>
                        </v-form>
                    </v-col>

                    <v-col cols="12" sm="4">
                        <div class="d-flex justify-sm-start justify-center align-center mx-3 my-1" style="height: 100%">
                            <v-btn outlined @click="addUser" :loading="loading" :disabled="!canAdd">Add</v-btn>
                        </div>
                    </v-col>
                </v-row>
            </v-card-text>

            <v-card-actions class="d-flex justify-center">
                <v-btn color="success" class="mx-1" outlined :disabled="loading" @click="update">
                    Submit
                </v-btn>
            </v-card-actions>
        </v-card>

        <ErrorSnackbarComponent v-model="error"></ErrorSnackbarComponent>
    </v-dialog>
</template>

<script>
import ErrorSnackbarComponent from "@/ErrorSnackbarComponent.vue";

import { useCurrenciesStore } from "&/stores/currencies";
import validation from "&/mixins/validation";
import main from "&/mixins/main";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [validation, main],
    components: {
        ErrorSnackbarComponent
    },
    props: {
        value: {
            required: true,
            type: Array
        }
    },
    data() {
        return {
            users: [],
            dialog: false,
            error: false,
            loading: false,
            email: null,
            emailExists: true,
            canAdd: true
        }
    },
    methods: {
        update() {
            this.$emit("input", this.users);
            this.dialog = false;
        },
        addUser() {
            this.loading = true;

            axios
                .post("/web-api/extensions/reports/user-info", { email: this.email })
                .then(response => {
                    const data = response.data;

                    this.$refs.form.reset();
                    this.users.push(data.user);

                    this.loading = false;
                })
                .catch(err => {
                    if (err.response.status == 422 && err.response.data.errors.email.includes("The selected email is invalid.")) {
                        this.emailExists = false;
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
        removeUser(email) {
            this.users = this.users.filter(item => item.email != email);
        }
    },
    mounted() {
        this.users = _.cloneDeep(this.value);
    }
}
</script>
