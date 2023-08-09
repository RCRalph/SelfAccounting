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

                <div class="text-center py-4 pb-0 text-h5">{{ data.username }}</div>

                <v-card-text>
                    <v-row>
                        <v-col xl="10" offset-xl="1" md="10" offset-md="1" cols="12">
                            <v-simple-table>
                                <template v-slot:default>
                                    <tbody>
                                        <tr>
                                            <td class="text-end text-h6" width="50%">With us since</td>
                                            <td class="text-h6">{{ dateWithCurrentTimeZone(data.since) }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-end text-h6">Account type</td>
                                            <td class="text-h6" :class="accountTypeColor">{{ data.account_type }}</td>
                                        </tr>

                                        <tr v-if="data.expiration">
                                            <td class="text-h6 text-center" colspan="2">{{ data.expiration }}</td>
                                        </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>

                            <v-divider></v-divider>
                        </v-col>
                    </v-row>

                    <div class="d-flex justify-center flex-wrap mt-3">
                        <ProfileInformationDialogComponent v-model="data"></ProfileInformationDialogComponent>

                        <ProfilePasswordDialogComponent></ProfilePasswordDialogComponent>

                        <ProfileSettingsDialogComponent v-model="data"></ProfileSettingsDialogComponent>
                    </div>

                    <div v-if="data.account_type != 'Admin'" class="d-flex justify-center flex-wrap mt-0">
                        <ProfileDeleteDialogComponent></ProfileDeleteDialogComponent>
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
import ProfileInformationDialogComponent from "@/profile/ProfileInformationDialogComponent.vue";
import ProfilePasswordDialogComponent from "@/profile/ProfilePasswordDialogComponent.vue";
import ProfileSettingsDialogComponent from "@/profile/ProfileSettingsDialogComponent.vue"
import ProfileDeleteDialogComponent from "@/profile/ProfileDeleteDialogComponent.vue";

import main from "&/mixins/main";

export default {
    components: {
        ProfilePictureDialogComponent,
        ProfileInformationDialogComponent,
        ProfilePasswordDialogComponent,
        ProfileSettingsDialogComponent,
        ProfileDeleteDialogComponent
    },
    mixins: [main],
    data() {
        return {
            data: {},
            ready: false
        }
    },
    watch: {
        'data.profile_picture_link'() {
            this.$emit("updatedUser", { profile_picture_link: this.data.profile_picture_link });
        },
        'data.username'() {
            this.$emit("updatedUser", { username: this.data.username });
        },
        'data.darkmode'() {
            this.$emit("updatedUser", { darkmode: this.data.darkmode });
        },
        'data.hide_all_tutorials'() {
            this.$emit("updatedUser", { hide_all_tutorials: this.data.hide_all_tutorials });
        }
    },
    computed: {
        accountTypeColor() {
            switch (this.data.account_type) {
                case "Premium":
                    return "amber--text";
                case "Admin":
                    return "primary--text";
                default:
                    return "";
            }
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
