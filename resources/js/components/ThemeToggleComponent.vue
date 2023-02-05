<template>
    <v-btn
        icon outlined
        :loading="loading"
        :disabled="loading"
        @click="toggleTheme"
    >
        <v-icon>mdi-theme-light-dark</v-icon>
    </v-btn>
</template>

<script>
export default {
    data() {
        return {
            loading: false
        }
    },
    methods: {
        toggleTheme() {
            this.loading = true
            this.$vuetify.theme.dark = !this.$vuetify.theme.dark;

            axios.post("/web-api/profile/darkmode")
                .then(response => {
                    this.$emit("input", response.data.darkmode);
                    setTimeout(() => this.loading = false, 500);
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.loading = false, 2000);
                })
        }
    }
}
</script>
