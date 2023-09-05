import { ref } from "vue"
import type { Ref } from "vue"
import type { Loading } from "@interfaces/App"

function useDialogSettings() {
    const dialog = ref(false)
    const ready = ref(true)
    const canSubmit: Ref<null | boolean> = ref(null)
    const loading: Ref<Loading> = ref({
        submit: false,
        table: false,
        title: false,
    })

    return {canSubmit, dialog, loading, ready}
}

export {
    useDialogSettings,
}