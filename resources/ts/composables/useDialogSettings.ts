import { ref } from "vue"
import type { Loading } from "@interfaces/App"

function useDialogSettings() {
    const dialog = ref(false)
    const ready = ref(true)
    const canSubmit = ref<boolean | null>(null)
    const loading = ref<Loading>({
        submit: false,
        table: false,
        title: false,
    })

    return {canSubmit, dialog, loading, ready}
}

export {
    useDialogSettings,
}