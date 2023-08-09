import {useTheme} from "vuetify"
import {computed} from "vue"

export default function useThemeSettings() {
    const theme = useTheme()

    const logoTextImage = computed(() => {
        return `/storage/Logo text ${theme.global.name.value}.svg`
    })

    function setTheme(darkmode: boolean) {
        theme.global.name.value = darkmode ? "dark" : "light"
        document.documentElement.style.colorScheme = theme.global.name.value
    }

    const themeString = computed(
        () => theme.global.name.value,
    )

    const themeIsLight = computed(
        () => theme.global.name.value == "light",
    )

    const themeIsDark = computed(
        () => theme.global.name.value == "dark",
    )

    return {logoTextImage, setTheme, themeIsDark, themeIsLight, themeString}
}
