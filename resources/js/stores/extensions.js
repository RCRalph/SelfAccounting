import { defineStore } from "pinia";

export const useExtensionsStore = defineStore("extensions", {
    state: () => ({
        extensions: [],
        ownedExtensions: [],
        isUserPremium: false,
        premiumExtensions: []
    }),
    getters: {
        ownedExtensionsObjects: state => state.extensions.filter(item => state.ownedExtensions.includes(item.code)),
        hasExtension: state => {
            return extensionCode => state.ownedExtensions.includes(extensionCode);
        },
        isExtensionPremium: state => {
            return extensionCode => state.premiumExtensions.includes(extensionCode);
        }
    },
    actions: {
        setOwnedExtensions(ownedExtensions) {
            this.ownedExtensions = ownedExtensions;
        },
        setExtensions(extension) {
            this.extensions = extension;
        },
        setPremium(premium) {
            this.isUserPremium = premium;
        },
        setPremiumExtensions(premiumExtension) {
            this.premiumExtensions = premiumExtension;
        }
    }
})
