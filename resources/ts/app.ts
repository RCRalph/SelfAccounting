// Stylesheets
import "vuetify/styles"
import "@sass/app.scss"

// Font Awesome
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { library } from "@fortawesome/fontawesome-svg-core"
import { fas } from "@fortawesome/free-solid-svg-icons"
import { far } from "@fortawesome/free-regular-svg-icons"
import { fab } from "@fortawesome/free-brands-svg-icons"

library.add(fas, far, fab)

// Settings
import { createApp } from "vue"
import vuetify from "@plugins/vuetify"
import router from "@plugins/router"
import { createPinia } from "pinia"

// Root component
import AppComponent from "@components/app/AppComponent.vue"

// Global components
import DeleteDialogComponent from "@components/global/DeleteDialogComponent.vue"
import DuplicateDialogComponent from "@components/global/DuplicateDialogComponent.vue"
import ValueFieldComponent from "@components/global/ValueFieldComponent.vue"

// Card Components
import CardLoadingComponent from "@components/global/card/CardLoadingComponent.vue"
import CardTitleWithButtons from "@components/global/card/CardTitleWithButtonsComponent.vue"
import CardActionsNavigationComponent from "@components/global/card/CardActionsNavigationComponent.vue"
import CardActionsNoYesComponent from "@components/global/card/CardActionsNoYesComponent.vue"
import CardActionsResetUpdateComponent from "@components/global/card/CardActionsResetUpdateComponent.vue"
import CardActionsSubmitComponent from "@components/global/card/CardActionsSubmitComponent.vue"

const app = createApp(AppComponent)

// Register global components
app.component("font-awesome-icon", FontAwesomeIcon)
    .component("DeleteDialogComponent", DeleteDialogComponent)
    .component("DuplicateDialogComponent", DuplicateDialogComponent)
    .component("ValueFieldComponent", ValueFieldComponent)
    .component("CardLoadingComponent", CardLoadingComponent)
    .component("CardTitleWithButtons", CardTitleWithButtons)
    .component("CardActionsNavigationComponent", CardActionsNavigationComponent)
    .component("CardActionsNoYesComponent", CardActionsNoYesComponent)
    .component("CardActionsResetUpdateComponent", CardActionsResetUpdateComponent)
    .component("CardActionsSubmitComponent", CardActionsSubmitComponent)

app.use(vuetify)
app.use(router)
app.use(createPinia())

app.mount("#app")
