<template>
    <v-dialog
        v-model="dialog"
        max-width="800"
        persistent
    >
        <v-card>
            <v-card-text
                v-html="tutorialHTML"
                class="tutorial-text"
            ></v-card-text>

            <v-card-actions
                class="d-flex justify-space-around flex-wrap flex-md-row flex-column-reverse"
            >
                <v-btn
                    :block="display.smAndDown.value"
                    :loading="loading.hideAllTutorials"
                    variant="outlined"
                    class="mx-0 my-1"
                    width="190"
                    color="error"
                    @click="hideAllTutorials"
                >
                    Hide all tutorials
                </v-btn>

                <v-btn
                    :block="display.smAndDown.value"
                    :loading="loading.dontShowAgain"
                    variant="outlined"
                    class="mx-0 my-1"
                    width="190"
                    color="primary"
                    @click="dontShowAgain"
                >
                    Don't show again
                </v-btn>

                <v-btn
                    :block="display.smAndDown.value"
                    variant="outlined"
                    class="mx-0 my-1"
                    width="190"
                    @click="close"
                >
                    Close
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { ref, watch, onMounted } from "vue"
import { useStatusStore } from "@stores/status"
import { useDisplay } from "vuetify"
import { useRoute } from "vue-router"

const props = defineProps<{
    hideAllTutorials: boolean
    disabledTutorials: string[]
    tutorialPaths: string[]
}>()

const emit = defineEmits<{
    "update:disabledTutorials": [payload: string[]]
    "update:hideAllTutorials": [payload: boolean]
}>()

const status = useStatusStore()
const display = useDisplay()
const route = useRoute()

const dialog = ref(false)
const tutorialHTML = ref("")

function useTutorialButtonActions() {
    const loading = ref({
        hideAllTutorials: false,
        dontShowAgain: false,
    })

    function hideAllTutorials() {
        loading.value.hideAllTutorials = true

        axios.post("/web-api/tutorials/hide-all")
            .then(() => {
                dialog.value = false
                emit("update:hideAllTutorials", true)
                loading.value.hideAllTutorials = false
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.hideAllTutorials = false, 2000)
            })
    }

    function dontShowAgain() {
        loading.value.dontShowAgain = true

        axios.post("/web-api/tutorials/hide", {route: route.path})
            .then(() => {
                dialog.value = false
                emit("update:disabledTutorials", props.disabledTutorials.filter(item => item != route.path))
                loading.value.dontShowAgain = false
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.dontShowAgain = false, 2000)
            })
    }

    function close() {
        dialog.value = false
    }

    return {close, dontShowAgain, hideAllTutorials, loading}
}

function getTutorial() {
    dialog.value = false

    if (!props.disabledTutorials.includes(route.path) && props.tutorialPaths.includes(route.path)) {
        axios.get("/web-api/tutorials", {params: {route: route.path}})
            .then(response => {
                const data = response.data

                tutorialHTML.value = data.tutorial

                dialog.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
            })
    }
}

watch(() => route.path, getTutorial)
const {close, dontShowAgain, hideAllTutorials, loading} = useTutorialButtonActions()

onMounted(getTutorial)
</script>
