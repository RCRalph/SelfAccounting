import Vue from 'vue';
import Vuetify from 'vuetify';
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { fas } from '@fortawesome/free-solid-svg-icons'

Vue.use(Vuetify)
Vue.component('font-awesome-icon', FontAwesomeIcon)
library.add(fas)

export default new Vuetify({
    icons: {
        iconfont: "faSvg",
        values: {
            checkboxOff: {
                component: FontAwesomeIcon,
                props: {
                    icon: ['fas', 'square'],
                }
            }
        }
    },
    theme: { dark: true }
})
