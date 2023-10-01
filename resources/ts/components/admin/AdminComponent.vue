<template>
    <div
        v-if="ready"
        class="tabs"
    >
        <v-tabs
            :height="60"
            :center-active="true"
            align-tabs="center"
            :show-arrows="true"
            :stacked="true"
        >
            <v-tab
                value="Dashboard"
                prepend-icon="mdi-gauge"
                to="/admin"
            >
                Dashboard
            </v-tab>

            <v-tab
                tag="a"
                href="/telescope"
                target="_blank"
                value="Laravel Telescope"
                prepend-icon="fa:fab fa-laravel"
            >
                <span class="d-flex align-start">
                    Laravel Telescope
                    <v-icon class="ml-1" size="x-small">mdi-open-in-new</v-icon>
                </span>
            </v-tab>
        </v-tabs>

        <router-view></router-view>
    </div>

    <v-overlay
        v-else
        :model-value="true"
        :contained="true"
    >
        <v-progress-circular
            indeterminate
            size="128"
        ></v-progress-circular>
    </v-overlay>
</template>

<script setup lang="ts">
import { ref } from "vue"

const ready = ref(true)
</script>
