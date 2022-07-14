<template>
    <v-row>
        <v-col xl="4" offset-xl="4" lg="6" offset-lg="3" md="8" offset-md="2" cols="12">
            <v-card v-if="ready">
                <v-card-title></v-card-title>

                <div class="d-flex justify-center">
                    <ProfilePictureDialogComponent
                        v-model="data.profile_picture_link"
                    ></ProfilePictureDialogComponent>
                </div>

                <div class="text-center py-4 pb-0 text-h5">Admin Username</div>

                <v-card-text>
                    <v-row>
                        <v-col xl="10" offset-xl="1" md="10" offset-md="1" cols="12">
                            <v-simple-table>
                                <template v-slot:default>
                                    <tbody>
                                        <tr>
                                            <td class="text-end text-h6" width="50%">With us since</td>
                                            <td class="text-h6">1970-01-01</td>
                                        </tr>

                                        <tr>
                                            <td class="text-end text-h6">Account type</td>
                                            <td class="text-h6 amber--text">Premium</td>
                                        </tr>

                                        <tr>
                                            <td class="text-h6 text-center" colspan="2">20 days until expiration</td>
                                        </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>

                            <v-divider></v-divider>
                        </v-col>
                    </v-row>

                    <div class="d-flex justify-center flex-wrap mt-3">
                        <v-btn outlined class="mx-3 my-2" width="145" :block="$vuetify.breakpoint.xs">Information</v-btn>
                        <v-btn outlined class="mx-3 my-2" width="145" :block="$vuetify.breakpoint.xs">Password</v-btn>
                        <v-btn outlined class="mx-3 my-2" width="145" :block="$vuetify.breakpoint.xs">Appearance</v-btn>
                    </div>
                </v-card-text>
            </v-card>

            <v-card v-else>
                <v-card-title></v-card-title>

                <v-card-text class="d-flex justify-center">
                    <v-progress-circular
                        indeterminate
                        size="96"
                    ></v-progress-circular>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import ProfilePictureDialogComponent from "@/profile/ProfilePictureComponent.vue";

export default {
    components: {
        ProfilePictureDialogComponent
    },
    data() {
        return {
            data: {
                profile_picture_link: ""
            },
            ready: false
        }
    },
    watch: {
        'data.profile_picture_link'() {
            this.$emit("updatedUser", { profile_picture_link: this.data.profile_picture_link });
        }
    },
    mounted() {
        this.ready = false;

        axios.get("/web-api/profile")
            .then(response => {
                const data = response.data;

                this.data = data.data;

                this.ready = true;
            })
    }
}
</script>
