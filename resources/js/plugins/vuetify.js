import Vue from 'vue';
import Vuetify from 'vuetify';
import '@mdi/font/css/materialdesignicons.css'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { fas } from '@fortawesome/free-solid-svg-icons'

Vue.use(Vuetify)
Vue.component('font-awesome-icon', FontAwesomeIcon)
library.add(fas)

export default new Vuetify({
    theme: {
        themes: {
            light: { success: "#4caf50" },
            dark: { success: "#4caf50" }
        },
    }
})
