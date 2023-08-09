// Stylesheets
import "vuetify/styles"
import "@sass/app.scss"

// Font Awesome
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome"
import {library} from "@fortawesome/fontawesome-svg-core"
import {fas} from "@fortawesome/free-solid-svg-icons"
import {far} from "@fortawesome/free-regular-svg-icons"
import {fab} from "@fortawesome/free-brands-svg-icons"

library.add(fas, far, fab)

// App
import {createApp} from "vue"
import AppComponent from "@components/AppComponent.vue"

// Plugins
import vuetify from "@plugins/vuetify"
import router from "@plugins/router"
import {createPinia} from "pinia"

const app = createApp(AppComponent)

app.component("font-awesome-icon", FontAwesomeIcon)

app.use(vuetify)
app.use(router)
app.use(createPinia())

app.mount("#app")
