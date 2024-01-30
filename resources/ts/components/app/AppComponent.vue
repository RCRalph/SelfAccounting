<template>
    <v-app>
        <div v-if="ready && user.data != undefined">
            <v-navigation-drawer
                :rail="navigationRail"
                :expand-on-hover="!display.mobile.value"
                :permanent="!display.mobile.value"
                :width="288"
                :absolute="true"
                style="position: fixed"
                @click="navigationForceOpen = true"
            >
                <v-list-item class="pa-2">
                    <template v-slot:prepend>
                        <v-avatar image="/storage/Logo.svg" rounded="0" :size="40"></v-avatar>
                    </template>

                    <v-list-item-title class="m-0">
                        <v-img :src="logoTextImage"></v-img>
                    </v-list-item-title>

                    <template v-slot:append>
                        <v-btn
                            v-if="display.mobile.value"
                            class="ml-2"
                            density="comfortable"
                            icon="mdi-chevron-left"
                            variant="plain"
                            @click.stop="navigationForceOpen = false"
                        ></v-btn>
                    </template>
                </v-list-item>

                <v-divider class="my-1"></v-divider>

                <v-list :lines="false" :nav="true" density="comfortable">
                    <div v-for="item in drawerItems">
                        <v-list-item
                            :value="item.title"
                            rounded="xl"
                            :to="item.link"
                            :prepend-icon="item.icon"
                            :title="item.title"
                        ></v-list-item>
                    </div>
                </v-list>

                <v-divider class="my-1"></v-divider>

                <template v-slot:append>
                    <v-list-item prepend-icon="mdi-cog" :height="67">
                        <div class="d-flex justify-content-between align-center">
                            <v-select
                                v-model:model-value="currencies.usedCurrency"
                                v-model:focused="currencySelectFocused"
                                :items="currencies.currencies"
                                :hide-details="true"
                                item-title="ISO"
                                item-value="id"
                                variant="underlined"
                                label="Currency"
                                density="comfortable"
                                class="py-3"
                            ></v-select>

                            <ThemeToggleComponent></ThemeToggleComponent>
                        </div>
                    </v-list-item>

                    <v-menu
                        v-model="profileMenuFocused"
                        location="top"
                        transition="slide-x-transition"
                    >
                        <template v-slot:activator="{ props }: any">
                            <v-list-item v-bind="props" class="pa-2">
                                <template v-slot:prepend>
                                    <v-avatar :image="user.data.profile_picture_link" :size="40"></v-avatar>
                                </template>

                                <div class="d-flex align-center">
                                    <v-list-item-title
                                        class="font-weight-black h5 m-0"
                                        style="user-select: none"
                                    >
                                        {{ user.username }}
                                    </v-list-item-title>

                                    <v-btn
                                        class="ml-auto"
                                        density="comfortable"
                                        icon="mdi-chevron-up"
                                        variant="plain"
                                    ></v-btn>
                                </div>
                            </v-list-item>
                        </template>

                        <v-list>
                            <v-list-item
                                v-for="item in profileItems"
                                :to="item.link"
                                :prepend-icon="item.icon"
                            >
                                <v-list-item-title>{{ item.title }}</v-list-item-title>
                            </v-list-item>

                            <v-list-item @click="logout" prepend-icon="mdi-key">
                                <v-list-item-title>Logout</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </template>
            </v-navigation-drawer>

            <TutorialComponent
                v-if="typeof user.data == 'undefined' || !user.data.hide_all_tutorials"
                v-model:disabled-tutorials="disabledTutorials"
                v-model:hide-all-tutorials="user.data.hide_all_tutorials"
                :tutorial-paths="tutorialPaths"
            ></TutorialComponent>

            <PremiumExpiredComponent
                v-model="premiumExpired"
            ></PremiumExpiredComponent>

            <v-main :style="VMainStyle">
                <router-view></router-view>
            </v-main>
        </div>

        <v-overlay v-else :model-value="true" opacity="1">
            <v-progress-circular
                indeterminate
                size="128"
            ></v-progress-circular>
        </v-overlay>

        <ErrorSnackbarComponent></ErrorSnackbarComponent>

        <SuccessSnackbarComponent></SuccessSnackbarComponent>
    </v-app>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref, computed, onMounted } from "vue"
import type { Ref } from "vue"
import { useDisplay } from "vuetify"

import type { MenuItem } from "@interfaces/App"

import ThemeToggleComponent from "@components/app/ThemeToggleComponent.vue"
import TutorialComponent from "@components/app/TutorialComponent.vue"
import PremiumExpiredComponent from "@components/app/PremiumExpiredComponent.vue"
import ErrorSnackbarComponent from "@components/app/ErrorSnackbarComponent.vue"
import SuccessSnackbarComponent from "@components/app/SuccessSnackbarComponent.vue"

import useThemeSettings from "@composables/useThemeSettings"
import { useCurrenciesStore } from "@stores/currencies"
import { useExtensionsStore } from "@stores/extensions"
import { useUserStore } from "@stores/user"
import { useStatusStore } from "@stores/status"

const currencies = useCurrenciesStore()
const extensions = useExtensionsStore()
const user = useUserStore()
const status = useStatusStore()
const display = useDisplay()

function useAppSettings() {
    const ready = ref(false)
    const premiumExpired = ref(false)

    const VMainStyle = computed(() =>
        display.mobile.value ?
            "margin-left: 0" :
            "margin-left: 56px",
    )

    return {VMainStyle, premiumExpired, ready}
}

function useNavigationDrawer() {
    const navigationForceOpen = ref(false)
    const currencySelectFocused = ref(false)
    const profileMenuFocused = ref(false)

    const navigationRail = computed(() => {
        if (currencySelectFocused.value || profileMenuFocused.value) return false
        else if (!display.mobile.value) return true

        return !navigationForceOpen.value
    })

    return {currencySelectFocused, navigationForceOpen, navigationRail, profileMenuFocused}
}

function useTutorials() {
    const tutorialPaths: Ref<string[]> = ref([])
    const disabledTutorials: Ref<string[]> = ref([])

    return {disabledTutorials, tutorialPaths}
}

function useMenuItems() {
    const profileItems: Ref<MenuItem[]> = ref([{
        title: "View profile",
        icon: "mdi-account",
        link: "/profile",
    }])

    const drawerItems = computed(() => {
        const result: MenuItem[] = [
            {title: "Dashboard", icon: "mdi-view-dashboard", link: "/"},
            {title: "Income", icon: "mdi-trending-up", link: "/income"},
            {title: "Expenses", icon: "mdi-trending-down", link: "/expenses"},
            {title: "Transfers", icon: "mdi-swap-horizontal", link: "/transfers"},
            {title: "Categories", icon: "mdi-tag", link: "/categories"},
            {title: "Accounts", icon: "mdi-wallet", link: "/accounts"},
            {title: "Charts", icon: "mdi-chart-bar", link: "/charts"},
            {title: "Extensions", icon: "mdi-package-variant", link: "/extensions"},
        ]

        // Add user-specific entries
        if (typeof user.data != "undefined") {
            if (!user.data.hide_all_tutorials) {
                result.push({
                    title: "Getting started",
                    icon: "mdi-school",
                    link: "/getting-started",
                })
            }

            if (user.data.admin) {
                result.push({
                    title: "Admin",
                    icon: "mdi-lock",
                    link: "/admin",
                })
            }
        }

        return result
    })

    return {drawerItems, profileItems}
}

const {logoTextImage, setTheme} = useThemeSettings()
const {VMainStyle, premiumExpired, ready} = useAppSettings()
const {currencySelectFocused, navigationForceOpen, navigationRail, profileMenuFocused} = useNavigationDrawer()
const {disabledTutorials, tutorialPaths} = useTutorials()
const {drawerItems, profileItems} = useMenuItems()

function logout() {
    axios.post("/logout")
        .then(() => window.location.href = "/")
        .catch(err => {
            console.log(err)
            status.showError()
        })
}

onMounted(() => {
    ready.value = false
    setTheme(window.matchMedia("(prefers-color-scheme: dark)").matches)

    axios.get("/web-api/app")
        .then(response => {
            const data = response.data

            user.update(data.user)
            setTheme(data.user.darkmode)

            currencies.setCurrencies(data.currencies)

            extensions.setExtensions(data.extensions)
                .setOwnedExtensions(data.ownedExtensions)

            premiumExpired.value = data.premiumExpired

            tutorialPaths.value = data.tutorials
            disabledTutorials.value = data.disabledTutorials

            ready.value = true
        })
        .catch(err => {
            console.log(err)
            status.showError()
        })
})
</script>
