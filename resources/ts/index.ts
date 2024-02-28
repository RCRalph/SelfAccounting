// Stylesheets
import "vuetify/styles"
import "@sass/app.scss"

// Settings
import { createApp } from "vue"
import vuetify from "@plugins/vuetify"
import router from "@plugins/home-router"
import { createPinia } from "pinia"

import Particles from "@tsparticles/vue3"
import { loadFull } from "tsparticles"

// Root components
import IndexComponent from "@components/index/IndexComponent.vue"

const app = createApp(IndexComponent)

app.use(vuetify)
app.use(router)
app.use(createPinia())
app.use(Particles, {
    init: async engine => await loadFull(engine),
})

app.mount("#app")
