<template>
    <v-app-bar
        height="64"
        fixed
    >
        <template v-slot:prepend>
            <router-link
                to="/"
                class="text-decoration-none text-white d-flex flex-nowrap align-center ml-0 ml-sm-3"
            >
                <v-avatar
                    :size="48"
                    image="/storage/Logo.svg"
                    rounded="0"
                ></v-avatar>

                <v-img
                    :src="logoTextImage"
                    :width="logoTextWidth"
                    max-height="48"
                    class="ml-3"
                ></v-img>
            </router-link>
        </template>

        <template v-slot:append>
            <v-menu
                v-if="xs"
                transition="slide-y-transition"
                rounded
                bottom
            >
                <template v-slot:activator="{ props: menuProps }">
                    <v-btn
                        v-bind="menuProps"
                        icon="mdi-menu"
                    ></v-btn>
                </template>

                <v-list>
                    <v-list-item
                        to="/login"
                        title="Login"
                        link
                    ></v-list-item>

                    <v-list-item
                        to="/register"
                        title="Register"
                        link
                    ></v-list-item>
                </v-list>
            </v-menu>

            <div v-else>
                <v-btn
                    variant="text"
                    size="large"
                    to="/login"
                    text="Login"
                    class="mx-1"
                ></v-btn>

                <v-btn
                    variant="text"
                    size="large"
                    to="/register"
                    text="Register"
                    class="mx-1"
                ></v-btn>
            </div>
        </template>
    </v-app-bar>
</template>

<script setup lang="ts">
import { computed } from "vue"
import { useDisplay } from "vuetify"

import useThemeSettings from "@composables/useThemeSettings"

const {xs, sm} = useDisplay()

const logoTextWidth = computed(() => xs.value ? 200 : (sm.value ? 250 : 300))

const {logoTextImage} = useThemeSettings()
</script>
