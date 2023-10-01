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
                v-for="item in extensions"
                :value="item.code"
                :prepend-icon="item.icon"
            >
                {{ item.title }}
            </v-tab>
        </v-tabs>
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
interface Extension {
    code: string,
    title: string,
    icon: string,
    directory: string,
}

import axios from "axios"
import { onMounted, ref } from "vue"
import type { Ref } from "vue"
//import {useRoute} from "vue-router"

const extensions: Ref<Extension[]> = ref([{
    code: "store",
    title: "Store",
    icon: "mdi-shopping",
    directory: "store",
}])
const ready = ref(false)

onMounted(() => {
    ready.value = false

    axios.get("/web-api/extensions")
        .then(response => {
            const data = response.data

            extensions.value = extensions.value.concat(data.extensions)

            ready.value = true
        })
})
</script>
