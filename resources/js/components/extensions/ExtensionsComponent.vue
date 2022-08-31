<template>
    <v-row v-if="ready">
        <v-col v-for="item in extensionsData" :key="item.code" xl="3" lg="4" md="6" cols="12">
            <ExtensionCardComponent
                :extension="item"
                :ownedExtensions="ownedExtensions"
                :premiumExtensions="premiumExtensions"
                :isPremium="isPremium"
                @togglePremiumExtension="togglePremiumExtension"
                @updated="updateExtensions"
            ></ExtensionCardComponent>
        </v-col>
    </v-row>

    <v-overlay v-else :value="true" opacity="1">
        <v-progress-circular
            indeterminate
            size="128"
        ></v-progress-circular>
    </v-overlay>
</template>

<script>
import { useExtensionsStore } from "&/stores/extensions";
import ExtensionCardComponent from "@/extensions/ExtensionCardComponent.vue";

export default {
    setup() {
        const extensions = useExtensionsStore();

        return { extensions };
    },
    components: {
        ExtensionCardComponent
    },
    data() {
        return {
            extensionsData: [],
            ownedExtensions: {},
            premiumExtensions: [],
            isPremium: false,

            ready: false
        }
    },
    methods: {
        togglePremiumExtension(code) {
            if (this.premiumExtensions.includes(code)) {
                this.premiumExtensions = this.premiumExtensions.filter(item => item != code);
            }
            else {
                this.premiumExtensions.push(code);
            }
        },
        updateExtensions(data) {
            this.extensions.ownedExtensions = data
        }
    },
    mounted() {
        this.ready = false;

        axios.get("/web-api/extensions")
            .then(response => {
                const data = response.data;

                this.extensionsData = data.extensions;
                this.ownedExtensions = data.ownedExtensions;
                this.premiumExtensions = data.premiumExtensions;
                this.isPremium = data.isPremium;

                this.ready = true;
            })
    }
}
</script>
