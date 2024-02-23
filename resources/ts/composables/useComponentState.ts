import { ref } from "vue"
import type { Loading } from "@interfaces/App"

export default function useComponentState() {
    const dialog = ref(false)

    const ready = ref(false)

    const canSubmit = ref<boolean | null>(null)

    const loading = ref<Loading>({
        submit: false,
        table: false,
        title: false,
    })

    return {canSubmit, dialog, loading, ready}
}