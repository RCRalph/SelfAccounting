export default function useFormats() {
    const nbsp = String.fromCharCode(160)

    function numberWithCurrency(
        value: number,
        ISO: string,
        forceSign = false
    ): string {
        let valueString = Math.abs(value)
            .toLocaleString("en", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            })
            .replaceAll(",", nbsp)

        if (forceSign && value > 0) {
            valueString = "+" + valueString
        } else if (value < 0) {
            valueString = "-" + valueString
        }

        return valueString + nbsp + ISO
    }

    function textWithNBSP(text: string) {
        return text.replaceAll(" ", nbsp)
    }

    function iconName(icon: string | null) {
        if (typeof icon == "string") {
            if (icon.startsWith("mdi")) {
                return icon
            } else if (icon.startsWith("fa")) {
                return `fa:${icon}`
            }
        }

        return null
    }

    return {iconName, numberWithCurrency, textWithNBSP}
}
