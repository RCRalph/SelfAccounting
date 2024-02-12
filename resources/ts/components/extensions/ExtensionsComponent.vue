<template>
    <div class="tabs">
        <v-tabs
            align-tabs="center"
            center-active
            show-arrows
            stacked
        >
            <v-tab
                value="store"
                prepend-icon="mdi-shopping"
                to="/extensions/store"
            >
                Store
            </v-tab>

            <v-tab
                v-for="item in extensions.enabledExtensions"
                :value="item.code"
                :prepend-icon="item.icon"
                :to="`/extensions/${item.directory}`"
            >
                {{ item.title }}
            </v-tab>
        </v-tabs>

        <router-view></router-view>
    </div>
</template>

<script setup lang="ts">
import { useExtensionsStore } from "@stores/extensions"

const extensions = useExtensionsStore()
</script>
