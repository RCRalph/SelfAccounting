import { defineStore } from "pinia";

export const useBundlesStore = defineStore("bundles", {
    state: () => ({
        bundles: [],
        ownedBundles: []
    }),
    getters: {
        ownedBundlesObjects: state => state.bundles.filter(item => state.ownedBundles.includes(item.code))
    },
    actions: {
        setOwnedBundles(ownedBundles) {
            this.ownedBundles = ownedBundles;
        },
        setBundles(bundles) {
            this.bundles = bundles;
        }
    }
})
