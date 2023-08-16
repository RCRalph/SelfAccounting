import {defineStore} from "pinia"

interface Status {
    value: boolean
    lastChange: Date
}

interface State {
    timeout: number
    error: Status
    success: Status
}

export const useStatusStore = defineStore("status", {
    state: (): State => ({
        timeout: 5000,
        error: {
            value: false,
            lastChange: new Date(),
        },
        success: {
            value: false,
            lastChange: new Date(),
        },
    }),
    actions: {
        showError() {
            this.error.value = true
            this.error.lastChange = new Date()

            setTimeout(
                () => {
                    const timeDifference = new Date().getTime() - this.error.lastChange.getTime()
                    if (timeDifference >= this.timeout) {
                        this.hideError()
                    }
                },
                this.timeout,
            )
        },
        hideError() {
            this.error.value = false
            this.error.lastChange = new Date()
        },
    },
})
