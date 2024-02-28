import { defineStore } from "pinia"
import type { Extension } from "@interfaces/Extension"

interface State {
    extensions: Extension[],
    extensionMap: Record<string, Extension>
}

export const useExtensionsStore = defineStore("extensions", {
    state: (): State => ({
        extensions: [],
        extensionMap: {},
    }),
    getters: {
        enabledExtensions(state: State) {
            return state.extensions.filter(item => item.enabled)
        },
        hasExtension(state: State): (code: string) => boolean {
            return code => state.extensions
                .filter(item => item.enabled)
                .map(item => item.code)
                .includes(code)
        },
        extensionCodes(state: State) {
            return Object.keys(state.extensionMap)
        },
    },
    actions: {
        setExtensions(extensions: Extension[]) {
            if (new Set(extensions.map(item => item.code)).size != extensions.length) {
                throw new Error("Extensions have duplicate code values")
            }

            this.extensions = extensions
            for (let item of this.extensions) {
                this.extensionMap[item.code] = item
            }
        },
        toggleExtensionEnabled(code: string) {
            this.extensionMap[code].enabled = !this.extensionMap[code].enabled
        },
    },
})
