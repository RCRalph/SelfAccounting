import {createVuetify} from "vuetify"
import {fa} from "vuetify/iconsets/fa"
import {mdi} from "vuetify/iconsets/mdi"

export default createVuetify({
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
            class: ["py-4"]
        },
        VTabs: {
            bgColor: "rgb(var(--v-theme-surface))"
        }
    },
})
