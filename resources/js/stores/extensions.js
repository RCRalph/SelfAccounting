import { defineStore } from "pinia";

export const useExtensionsStore = defineStore("extensions", {
    state: () => ({
        extensions: [],
        ownedExtensions: []
    }),
    getters: {
        ownedExtensionsObjects: state => state.extensions.filter(item => state.ownedExtensions.includes(item.code)),
        hasExtension: state => {
            return extensionCode => state.ownedExtensions.includes(extensionCode);
        }
    },
    actions: {
        setOwnedExtensions(ownedExtensions) {
            this.ownedExtensions = ownedExtensions;
        },
        setExtensions(extension) {
            this.extensions = extension;
        }
    }
})
