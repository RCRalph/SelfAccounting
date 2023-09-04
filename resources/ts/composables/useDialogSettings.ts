import { ref } from "vue"
import type { Ref } from "vue"

interface Loading {
    submit: boolean
    title: boolean
}

function useDialogSettings() {
    const dialog = ref(false)
    const ready = ref(true)
    const canSubmit: Ref<null | boolean> = ref(null)
    const loading: Ref<Loading> = ref({
        submit: false,
        title: false,
    })

    return {canSubmit, dialog, loading, ready}
}

export {
    useDialogSettings,
}

export type {
    Loading,
}