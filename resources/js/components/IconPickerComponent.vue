<template>
    <v-dialog v-model="dialog" max-width="350">
        <!--<template v-slot:activator="{ on, attrs }">
            <v-btn
                outlined
                v-bind="attrs" v-on="on"
            >
                Choose icon
            </v-btn>
        </template>-->

        <v-card v-if="ready">
            <v-card-title class="d-flex justify-center">Choose icon</v-card-title>

            <v-card-text>
                <v-row class="mb-0">
                    <v-col cols="3" class="d-flex justify-center"
                        v-for="item, i in icons"
                        :key="i"
                    >
                        <v-btn icon
                            :color="icon == item ? 'primary' : undefined"
                            @click="icon = item"
                        >
                            <v-icon>{{ item }}</v-icon>
                        </v-btn>
                    </v-col>
                </v-row>

                <p class="mb-0 text-center">Supported icon set:
                    <a href="https://pictogrammers.com/library/mdi/" target="_blank">MDI</a>
                </p>

                <v-text-field
                    label="Icon name"
                    hide-details
                    v-model="icon"
                    :prepend-icon="icon"
                >
                    <template v-slot:prepend>
                        <v-icon v-on="on" class="ml-1" style="min-width: 28px">
                            {{ icon }}
                        </v-icon>
                    </template>
                </v-text-field>
            </v-card-text>

            <v-card-actions class="d-flex justify-center">
                <v-btn color="success" outlined>
                    Submit
                </v-btn>
            </v-card-actions>
        </v-card>

        <v-card v-else>
            <v-card-title class="d-flex justify-center">Choose icon</v-card-title>

            <v-card-text class="d-flex justify-center">
                <v-progress-circular
                    indeterminate
                    size="96"
                ></v-progress-circular>
            </v-card-text>
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
        icon: {
            required: false,
            type: String,
            default: ""
        }
    },
    data() {
        return {
            dialog: true,
            icons: [],
            canSubmit: false,
            ready: false,
            error: false,
        }
    },
    /*watch: {
        dialog() {
            if (!this.dialog) return;
            this.ready = false;

            axios
                .get(`/web-api/categories/icons`)
                .then(response => {
                    const data = response.data;

                    this.icons = data.icons;

                    this.ready = true;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                })
        }
    },*/
    mounted() {
        if (!this.dialog) return;
            this.ready = false;

            axios
                .get(`/web-api/categories/icons`)
                .then(response => {
                    const data = response.data;

                    this.icons = data.icons;

                    this.ready = true;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                })
    }
}
</script>
