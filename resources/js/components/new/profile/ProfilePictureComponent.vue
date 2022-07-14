<template>
    <v-dialog v-model="dialog" max-width="600">
        <template v-slot:activator="{ on, attrs }">
            <v-hover v-slot="{ hover }">
                <v-avatar :size="$vuetify.breakpoint.smAndUp ? 200 : 100" v-bind="attrs" v-on="on">
                    <img :src="value">

                    <div class="d-flex align-center justify-center" style="position: absolute">
                        <v-icon large class="hover-icon" :style="{ opacity: hover ? 1 : 0}">mdi-camera</v-icon>
                    </div>
                </v-avatar>
            </v-hover>
        </template>

        <v-card>
            <v-card-title>Change profile picture</v-card-title>

            <v-card-text>
                <v-file-input
                    v-model="picture"
                    accept="image/png, image/jpeg, image/bmp, image/jpg"
                    show-size
                    prepend-icon="mdi-camera"
                    placeholder="Choose profile picture"
                ></v-file-input>

                <div class="d-flex justify-center" v-if="picture">
                    <div class="div-img-rounded" :style="{
                        backgroundImage: `url(${imageURL})`,
                        width: $vuetify.breakpoint.smAndUp ? '200px' : '100px',
                        height: $vuetify.breakpoint.smAndUp ? '200px' : '100px'
                    }"></div>
                </div>
            </v-card-text>

            <v-card-actions class="d-flex justify-space-around">
                <v-btn color="success" outlined :disabled="!picture || loading" @click="submit" :loading="loading">
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
            type: String
        }
    },
    data() {
        return {
            dialog: false,
            picture: null,
            loading: false,
            error: false
        }
    },
    computed: {
        imageURL() {
            if (this.picture == null) {
                return null;
            }

            return URL.createObjectURL(this.picture);
        }
    },
    methods: {
        submit() {
            this.loading = true;

            let formData = new FormData();
            formData.append("picture", this.picture);

            axios
                .post("/web-api/profile/picture", formData, { headers: { "Content-type": "multipart/form-data" } })
                .then(response => {
                    this.$emit("input", response.data.picture);
                    this.dialog = false;
                    this.loading = false;
                    this.picture = null;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                })
        }
    }
}
</script>
