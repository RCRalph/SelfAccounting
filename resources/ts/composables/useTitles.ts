import axios from "axios"
import { ref } from "vue"
import type { Ref } from "vue"

import type { Loading } from "@interfaces/App"
import useUpdateWithOffset from "@composables/useUpdateWithOffset"
import { useStatusStore } from "@stores/status"

export default function useTitles(loading: Ref<Loading>, url: string) {
    const status = useStatusStore()
    const titles = ref<string[]>([])

    const {updateWithOffset: getTitles} = useUpdateWithOffset((args) => {
        if (!args) return

        loading.value.title = true

        axios.get(url, {
            params: {
                title: args,
            },
        })
            .then(response => {
                const data = response.data

                titles.value = data.titles

                loading.value.title = false
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.title = false, 2000)
            })
    })

    return {getTitles, titles}
}