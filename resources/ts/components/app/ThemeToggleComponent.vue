<template>
    <v-btn
        :icon="true"
        variant="outlined"
        :loading="loading"
        :disabled="loading"
        @click="toggleTheme"
        width="48"
        class="ml-4"
    >
        <v-icon>mdi-theme-light-dark</v-icon>
    </v-btn>

    <ErrorSnackbarComponent v-model="error"></ErrorSnackbarComponent>
</template>

<script setup lang="ts">
import axios from "axios"
import {ref} from "vue"
import useThemeSettings from "@composables/useThemeSettings"
import {useUserStore} from "@stores/user"
import ErrorSnackbarComponent from "@components/common/ErrorSnackbarComponent.vue";

const {themeIsDark, setTheme} = useThemeSettings()
const user = useUserStore()
const loading = ref(false)
const error = ref(false)

function toggleTheme() {
    loading.value = true
    setTheme(!themeIsDark.value)

    axios.post("/web-api/profile/darkmode")
        .then(response => {
            user.updateTheme(response.data.darkmode ? "dark" : "light")
            setTimeout(() => loading.value = false, 500)
        })
        .catch(err => {
            console.error(err)
            setTimeout(() => error.value = true, 1000)
            setTimeout(() => loading.value = false, 2000)
        })
}
</script>
