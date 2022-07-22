import { defineStore } from "pinia";

export const useBundlesStore = defineStore("bundles", {
    state: () => ({
        bundles: [],
        ownedBundles: [],
        isUserPremium: false,
        premiumBundles: []
    }),
    getters: {
        ownedBundlesObjects: state => state.bundles.filter(item => state.ownedBundles.includes(item.code)),
        hasBundle: state => {
            return bundleCode => state.ownedBundles.includes(bundleCode);
        },
        isBundlePremium: state => {
            return bundleCode => state.bundlesEnabledUsingPremium.includes(bundleCode);
        }
    },
    actions: {
        setOwnedBundles(ownedBundles) {
            this.ownedBundles = ownedBundles;
        },
        setBundles(bundles) {
            this.bundles = bundles;
        },
        setPremium(premium) {
            this.isUserPremium = premium;
        },
        setPremiumBundles(premiumBundles) {
            this.premiumBundles = premiumBundles;
        }
    }
})
