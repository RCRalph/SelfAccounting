<template>
    <v-app>
        <NavbarComponent></NavbarComponent>

        <v-main>
            <vue-particles
                id="particles"
                :options="particleConfig"
                style="margin: 0"
            ></vue-particles>

            <router-view></router-view>
        </v-main>

        <ErrorSnackbarComponent></ErrorSnackbarComponent>

        <SuccessSnackbarComponent></SuccessSnackbarComponent>
    </v-app>
</template>

<script setup lang="ts">
import { computed, onMounted } from "vue"
import { useTheme } from "vuetify"

import useThemeSettings from "@composables/useThemeSettings"

import NavbarComponent from "@components/index/NavbarComponent.vue"
import SuccessSnackbarComponent from "@components/app/SuccessSnackbarComponent.vue"
import ErrorSnackbarComponent from "@components/app/ErrorSnackbarComponent.vue"

const theme = useTheme()

const {setTheme} = useThemeSettings()

const particleConfig = computed(() => ({
    background: {
        color: {
            value: theme.current.value.colors.background,
        },
    },
    fpsLimit: 120,
    particles: {
        color: {
            value: theme.current.value.colors.success,
        },
        links: {
            color: theme.current.value.colors.success,
            distance: 150,
            enable: true,
            opacity: 0.5,
            width: 1,
        },
        move: {
            direction: "none",
            enable: true,
            outModes: "bounce",
            random: true,
            speed: 2,
            straight: false,
        },
        number: {
            density: {
                enable: true,
            },
            value: 100,
        },
        opacity: {
            value: 0.5,
        },
        shape: {
            type: "circle",
        },
        size: {
            value: {min: 1, max: 5},
        },
    },
    detectRetina: true,
}))

onMounted(() => setTheme(window.matchMedia("(prefers-color-scheme: dark)").matches))
</script>
