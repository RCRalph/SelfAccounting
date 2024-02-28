import {defineStore} from "pinia"

interface Status {
    value: boolean
    lastChange: Date
    timeout: number
    info?: string
}

interface State {
    error: Status
    success: Status
}

function timeDifference(status: Status) {
    return new Date().getTime() - status.lastChange.getTime()
}

function hideStatus(status: Status) {
    status.value = false
    status.lastChange = new Date()
}

function showStatus(status: Status) {
    status.value = true
    status.lastChange = new Date()

    setTimeout(
        () => {
            if (timeDifference(status) >= status.timeout) {
                hideStatus(status)
            }
        },
        status.timeout,
    )
}

export const useStatusStore = defineStore("status", {
    state: (): State => ({
        error: {
            value: false,
            lastChange: new Date(),
            timeout: 5000,
        },
        success: {
            value: false,
            lastChange: new Date(),
            timeout: 3000,
            info: "",
        },
    }),
    actions: {
        showError() {
            showStatus(this.error)
        },
        hideError() {
            hideStatus(this.error)
        },
        showSuccess(info: string) {
            this.success.info = info
            showStatus(this.success)
        },
        hideSuccess() {
            hideStatus(this.success)
        },
    },
})
