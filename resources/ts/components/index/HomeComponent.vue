<template>
    <section
        class="d-flex justify-center align-center"
        style="height: calc(100vh - 64px)"
    >
        <div
            class="d-flex justify-center align-center flex-column"
            style="position: absolute"
        >
            <v-img
                :src="logoTextImage"
                :width="logoImageWidth"
                class="mb-2"
            ></v-img>

            <div
                :class="subheaderFontClass"
                :style="{maxWidth: logoImageWidth + 'px'}"
                style="user-select: none"
                class="mb-3 text-center font-weight-black"
            >
                Your personal accounting&nbsp;platform
            </div>

            <v-btn
                variant="elevated"
                size="x-large"
                class="mt-5"
                text="Register now"
                to="/register"
            ></v-btn>
        </div>
    </section>

    <section>
        <v-layout class="my-5">
            <v-container>
                <v-row>
                    <v-col
                        cols="12"
                        md="6"
                        xl="3"
                    >
                        <v-card class="elevation-0 transparent text-center">
                            <v-card-text>
                                <v-icon
                                    style="font-size: 75px"
                                    icon="mdi-swap-horizontal"
                                ></v-icon>
                            </v-card-text>

                            <v-card-title class="text-h5">
                                Keep an eye on your transactions
                            </v-card-title>

                            <v-card-text>
                                Stay in the know about your earnings and expenses
                            </v-card-text>
                        </v-card>
                    </v-col>

                    <v-col
                        cols="12"
                        md="6"
                        xl="3"
                    >
                        <v-card class="elevation-0 transparent text-center">
                            <v-card-text>
                                <v-icon
                                    style="font-size: 75px"
                                    icon="mdi-chart-bar"
                                ></v-icon>
                            </v-card-text>

                            <v-card-title class="text-h5">
                                Stay on top of your finances
                            </v-card-title>

                            <v-card-text>
                                Ensure you always know your current financial standing
                            </v-card-text>
                        </v-card>
                    </v-col>

                    <v-col
                        cols="12"
                        md="6"
                        xl="3"
                    >
                        <v-card class="elevation-0 transparent text-center">
                            <v-card-text>
                                <v-icon
                                    style="font-size: 75px"
                                    icon="mdi-calendar-clock"
                                ></v-icon>
                            </v-card-text>

                            <v-card-title class="text-h5">
                                Prepare for the future
                            </v-card-title>

                            <v-card-text>
                                Organize finances with budgets for better preparation
                            </v-card-text>
                        </v-card>
                    </v-col>

                    <v-col
                        cols="12"
                        md="6"
                        xl="3"
                    >
                        <v-card class="elevation-0 transparent text-center">
                            <v-card-text>
                                <v-icon
                                    style="font-size: 75px"
                                    icon="mdi-chart-line"
                                ></v-icon>
                            </v-card-text>

                            <v-card-title class="text-h5">
                                Boost your savings
                            </v-card-title>

                            <v-card-text>
                                Evaluate spending habits and initiate saving strategies
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-layout>
    </section>
</template>

<script setup lang="ts">
import { computed } from "vue"
import { useDisplay } from "vuetify"

import useThemeSettings from "@composables/useThemeSettings"

const {mdAndUp, width, xs} = useDisplay()
const {themeIsDark} = useThemeSettings()
const {logoTextImage} = useThemeSettings()

const logoImageWidth = computed(() => mdAndUp.value ? 700 : width.value - 50)

const subheaderFontClass = computed(() => {
    const size = `text-h${xs.value ? 5 : 4}`
    const color = `text-grey text-${themeIsDark.value ? "lighten" : "darken"}-5`

    return `${size} ${color}`
})
</script>
