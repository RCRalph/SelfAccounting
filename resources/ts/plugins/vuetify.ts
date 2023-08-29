import {createVuetify} from "vuetify"
import {fa} from "vuetify/iconsets/fa"
import {mdi} from "vuetify/iconsets/mdi"

import * as components from "vuetify/components"
import * as labsComponents from "vuetify/labs/components"

export default createVuetify({
    components: {
        ...components,
        ...labsComponents,
    },
    theme: {
        themes: {
            light: {
                colors: {
                    success: "#4caf50",
                },
            },
            dark: {
                colors: {
                    success: "#4caf50",
                },
            },
        },
    },
    icons: {
        defaultSet: "mdi",
        sets: {mdi, fa},
    },
    defaults: {
        VOverlay: {
            persistent: true,
            class: ["justify-center", "align-center"],
            zIndex: 999,
        },
        VCardTitle: {
            class: ["py-4"],
        },
        VTabs: {
            bgColor: "rgb(var(--v-theme-surface))",
            style: "color: rgb(var(--theme-on-surface))",
        },
    },
})
