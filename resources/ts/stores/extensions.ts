import {defineStore} from "pinia"

export interface Extension {
    code: string,
    title: string,
    icon: string,
    directory: string
}

interface State {
    extensions: Extension[],
    ownedExtensions: string[]
}

export const useExtensionsStore = defineStore("extensions", {
    state: (): State => ({
        extensions: [],
        ownedExtensions: [],
    }),
    getters: {
        ownedExtensionsArray(state: State): Extension[] {
            return state.extensions.filter(item => state.ownedExtensions.includes(item.code))
        },
        hasExtension(state: State): (extensionCode: string) => boolean {
            return code => state.ownedExtensions.includes(code)
        },
    },
    actions: {
        setExtensions(extensions: Extension[]) {
            if (new Set(extensions.map(item => item.code)).size != extensions.length) {
                throw new Error("Extensions have duplicate code values")
            }

            this.extensions = extensions
            return this
        },
        setOwnedExtensions(ownedExtensions: string[]) {
            if (new Set(ownedExtensions).size != ownedExtensions.length) {
                throw new Error("Owned extensions have duplicate values")
            }

            this.ownedExtensions = ownedExtensions
            return this
        },
    },
})
