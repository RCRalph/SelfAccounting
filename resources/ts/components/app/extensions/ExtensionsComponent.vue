<template>
    <div class="tabs">
        <v-tabs
            v-model="currentTab"
            align-tabs="center"
            center-active
            show-arrows
            stacked
        >
            <v-tab
                v-for="item in tabs"
                :value="item.value"
                :prepend-icon="item.icon"
                :to="item.to"
            >
                {{ item.title }}
            </v-tab>
        </v-tabs>

        <router-view></router-view>
    </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from "vue"
import { useExtensionsStore } from "@stores/extensions"
import { useRoute } from "vue-router"

const extensions = useExtensionsStore()
const route = useRoute()

const tabs = computed(() => [
    {
        value: "store",
        icon: "mdi-shopping",
        to: "/extensions/store",
        title: "Store",
    },
    ...extensions.enabledExtensions.map(item => ({
        value: item.code,
        icon: item.icon,
        to: `/extensions/${item.directory}`,
        title: item.title,
    })),
])

const currentTab = ref("store")

onMounted(() => {
    for (const item of tabs.value) {
        if (route.path.startsWith(item.to)) {
            currentTab.value = item.value
            break
        }
    }
})
</script>
