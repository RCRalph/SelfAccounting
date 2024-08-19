import { createVuetify } from "vuetify"
import { fa } from "vuetify/iconsets/fa"
import { mdi } from "vuetify/iconsets/mdi"

export default createVuetify({
    theme: {
        themes: {
            light: {
                colors: {
                    success: "#4caf50",
                    primary: "#2196f3",
                    surface: "#fcfcfc",
                },
            },
            dark: {
                colors: {
                    success: "#4caf50",
                    primary: "#2196f3",
                    surface: "#1e1e1e",
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
            zIndex: 999,
        },
        VCardTitle: {
            class: ["py-4"],
        },
        VTabs: {
            bgColor: "rgb(var(--v-theme-surface))",
            style: "color: rgb(var(--theme-on-surface))",
        },
        VCard: {
            style: {
                padding: "2.5px",
            },
        },
    },
})
