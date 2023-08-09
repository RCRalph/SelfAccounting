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
            class: ["justify-center", "align-center"],
        },
    },
})
