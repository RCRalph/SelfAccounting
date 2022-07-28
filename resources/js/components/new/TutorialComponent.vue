<template>
    <v-dialog v-model="dialog" max-width="800" persistent>
        <v-card>
            <v-card-text v-html="tutorial" class="tutorial-text"></v-card-text>

            <v-card-actions class="d-flex justify-space-around flex-wrap flex-md-row flex-column-reverse">
                <v-btn
                    outlined
                    class="mx-0 my-1"
                    :block="$vuetify.breakpoint.smAndDown"
                    width="190"
                    color="error"
                    :loading="loading.hide"
                    @click="hideAllTutorials"
                >Hide all tutorials</v-btn>

                <v-btn
                    outlined
                    class="mx-0 my-1"
                    :block="$vuetify.breakpoint.smAndDown"
                    width="190"
                    color="primary"
                    :loading="loading.show"
                    @click="dontShowAgain"
                >Don't show again</v-btn>

                <v-btn
                    outlined
                    class="mx-0 my-1"
                    :block="$vuetify.breakpoint.smAndDown"
                    width="190"
                    @click="close"
                >Close</v-btn>
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
            type: Array
        }
    },
    data() {
        return {
            dialog: false,
            tutorial: "",
            loading: {
                hide: false,
                show: false
            },
            error: false
        }
    },
    watch: {
        '$route.path'() {
            this.getTutorial();
        }
    },
    methods: {
        getTutorial() {
            this.dialog = false;

            if (!this.value.includes(this.$route.path)) {
                axios.get("/web-api/tutorials", { params: { route: this.$route.path } })
                    .then(response => {
                        const data = response.data;
                        this.tutorial = data.tutorial;
                        this.dialog = true;
                    })
                    .catch(err => {
                        if (!(err.response.status == 422 && err.response.data.message == "The selected route is invalid.")) {
                            console.error(err);
                        }
                    });
            }
        },
        hideAllTutorials() {
            this.loading.hide = true;

            axios.post("/web-api/tutorials/hide-all")
                .then(() => {
                    this.dialog = false;
                    this.$emit("hideAll", { hide_all_tutorials: true });
                    this.loading.hide = false;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.loading.hide = false, 2000);
                })
        },
        dontShowAgain() {
            this.loading.show = true;

            axios.post("/web-api/tutorials/hide", { route: this.$route.path })
                .then(() => {
                    this.dialog = false;
                    this.$emit("input", this.value.filter(item => item != this.$route.path));
                    this.loading.show = false;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.loading.show = false, 2000);
                })
        },
        close() {
            this.dialog = false;
        }
    },
    mounted() {
        this.getTutorial();
    }
}
</script>
