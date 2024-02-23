<template>
    <div v-if="ready && userData">
        <v-row>
            <v-col
                cols="12"
                md="8"
                offset-md="2"
                lg="6"
                offset-lg="3"
                xl="4"
                offset-xl="4"
            >
                <v-card class="loading-height">
                    <div class="d-flex justify-center mt-8">
                        <ProfilePictureDialogComponent
                            v-model="userData.profile_picture_link"
                        ></ProfilePictureDialogComponent>
                    </div>

                    <CardTitleWithButtons
                        :title="userData.username"
                        large-font
                    ></CardTitleWithButtons>

                    <v-card-text>
                        <v-row>
                            <v-col
                                cols="12"
                                md="10"
                                offset-md="1"
                                xl="10"
                                offset-xl="1"
                            >
                                <v-table density="comfortable">
                                    <tbody class="table-two-columns-same-width">
                                        <tr>
                                            <td class="text-end text-h6">
                                                With us since
                                            </td>

                                            <td class="text-h6">
                                                {{ currentTimeZoneDate(userData.since) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-end text-h6">
                                                Account type
                                            </td>

                                            <td
                                                class="text-h6"
                                                :class="userTypeColor"
                                            >
                                                {{ userData.account_type }}
                                            </td>
                                        </tr>

                                        <tr v-if="userData.expiration">
                                            <td
                                                class="text-h6 text-center"
                                                colspan="2"
                                            >
                                                {{ userData.expiration }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </v-table>

                                <v-row class="mt-0">
                                    <v-col
                                        cols="12"
                                        md="4"
                                    >
                                        <ProfileInformationDialogComponent
                                            v-model="userData"
                                        ></ProfileInformationDialogComponent>
                                    </v-col>

                                    <v-col
                                        cols="12"
                                        md="4"
                                    >
                                        <ProfilePasswordDialogComponent></ProfilePasswordDialogComponent>
                                    </v-col>

                                    <v-col
                                        cols="12"
                                        md="4"
                                    >
                                        <ProfileSettingsDialogComponent
                                            v-model="userData"
                                        ></ProfileSettingsDialogComponent>
                                    </v-col>

                                    <v-col
                                        cols="12"
                                        md="6"
                                        offset-md="3"
                                    >
                                        <ProfileDeleteDialogComponent
                                            v-if="!user.data?.admin"
                                        ></ProfileDeleteDialogComponent>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script setup lang="ts">
import axios from "axios"
import { onMounted, ref, computed } from "vue"

import type { UserData } from "@interfaces/User"

import CardTitleWithButtons from "@components/global/card/CardTitleWithButtonsComponent.vue"
import ProfilePictureDialogComponent from "@components/profile/ProfilePictureDialogComponent.vue"
import ProfileInformationDialogComponent from "@components/profile/ProfileInformationDialogComponent.vue"
import ProfilePasswordDialogComponent from "@components/profile/ProfilePasswordDialogComponent.vue"
import ProfileSettingsDialogComponent from "@components/profile/ProfileSettingsDialogComponent.vue"
import ProfileDeleteDialogComponent from "@components/profile/ProfileDeleteDialogComponent.vue"

import { currentTimeZoneDate } from "@composables/useDates"
import { useUserStore } from "@stores/user"
import useComponentState from "@composables/useComponentState"

const user = useUserStore()

function useData() {
    const userData = ref<UserData>()

    const userTypeColor = computed(() => {
        switch (userData.value?.account_type) {
            case "Premium":
                return "text-amber"
            case "Admin":
                return "text-primary"
            default:
                return ""
        }
    })

    function getData() {
        ready.value = false

        axios.get(`/web-api/profile`)
            .then(response => {
                userData.value = response.data.data

                ready.value = true
            })
    }

    return {userTypeColor, getData, userData}
}

const {ready} = useComponentState()
const {userTypeColor, getData, userData} = useData()

onMounted(getData)
</script>
