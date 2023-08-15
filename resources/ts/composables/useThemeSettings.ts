import {computed} from "vue"
import {useTheme} from "vuetify"

interface ChartColors {
    fontColor?: string
    gridColor?: string
}

export default function useThemeSettings() {
    const theme = useTheme()

    const logoTextImage = computed(() => {
        return `/storage/Logo text ${theme.global.name.value}.svg`
    })

    function setTheme(darkmode: boolean) {
        theme.global.name.value = darkmode ? "dark" : "light"
        document.documentElement.style.colorScheme = theme.global.name.value
    }

    const themeName = computed(
        () => theme.global.name.value,
    )

    const themeIsDark = computed(
        () => theme.global.current.value.dark,
    )

    const chartColors = computed(() => {
        const result: ChartColors = {}

        if (themeIsDark.value) {
            result.gridColor = "rgba(255, 255, 255, 0.1)"
            result.fontColor = "rgba(255, 255, 255, 0.87)"
        } else {
            result.gridColor = "rgba(0, 0, 0, 0.1)"
            result.fontColor = "rgba(0, 0, 0, 0.87)"
        }

        return result
    })

    return {chartColors, logoTextImage, setTheme, themeIsDark, themeName}
}
