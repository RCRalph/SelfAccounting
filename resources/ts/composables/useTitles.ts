import { ref } from "vue"
import type { Ref } from "vue"

import useUpdateWithOffset from "@composables/useUpdateWithOffset"
import { useStatusStore } from "@stores/status"
import axios from "axios"

export default function useTitles(
    loading: Ref<{ title: boolean }>,
    url: string,
) {
    const status = useStatusStore()
    const titles: Ref<string[]> = ref([])
    const titleMenuShow = ref(false)

    const {updateWithOffset: getTitles} = useUpdateWithOffset<string | undefined>((args) => {
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
                titleMenuShow.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.title = false, 2000)
            })
    })

    return {getTitles, titleMenuShow, titles}
}