<template>
    <v-row v-if="ready">
        <v-col v-for="item in extensions" :key="item.code" xl="3" lg="4" md="6" cols="12">
            <v-card>
                <v-img height="250" :src="item.thumbnail_link"></v-img>

                <v-card-title class="d-flex justify-center">{{ item.title }}</v-card-title>

                <v-card-text>
                    <div class="text-center">{{ item.short_description }}</div>
                </v-card-text>

                <v-card-actions>
                    <v-row v-if="ownsExtension(item.code)">
                        <v-col cols="12" sm="4" offset-sm="2">
                            <v-btn outlined block
                                :color="ownedExtensions[item.code] ? 'error' : 'success'"
                                @click="toggleOwnedExtension(item.code)"
                            >{{ ownedExtensions[item.code] ? 'Disable' : 'Enable' }}</v-btn>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <ExtensionDetailsDialogComponent
                                :extension="item"
                            ></ExtensionDetailsDialogComponent>
                        </v-col>
                    </v-row>

                    <v-row v-else>
                        <v-col cols="12" sm="4">
                            <v-btn color="primary" outlined block>
                                Buy
                            </v-btn>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <ExtensionDetailsDialogComponent
                                :extension="item"
                            ></ExtensionDetailsDialogComponent>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <v-tooltip bottom v-if="isPremium">
                                <template v-slot:activator="{ on, attrs }">
                                    <div v-on="on" v-bind="attrs">
                                        <v-btn color="amber" outlined block
                                            @click="togglePremiumExtension(item.code)"
                                        >{{ ownsExtensionPremium(item.code) ? 'Disable' : 'Enable' }}</v-btn>
                                    </div>
                                </template>

                                <span>{{ ownsExtensionPremium(item.code) ? 'Disable' : 'Enable' }} with Premium</span>
                            </v-tooltip>

                            <v-tooltip bottom v-else>
                                <template v-slot:activator="{ on, attrs }">
                                    <div v-on="on" v-bind="attrs">
                                        <v-btn color="amber" outlined block disabled>Enable</v-btn>
                                    </div>
                                </template>

                                <span>Premium account is required</span>
                            </v-tooltip>
                        </v-col>
                    </v-row>
                </v-card-actions>
            </v-card>
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

import ExtensionDetailsDialogComponent from "@/extensions/ExtensionDetailsDialogComponent.vue";

export default {
    setup() {
        const extensions = useExtensionsStore();

        return { extensions };
    },
    components: {
        ExtensionDetailsDialogComponent
    },
    data() {
        return {
            extensions: [],
            ownedExtensions: {},
            premiumExtensions: [],
            isPremium: false,

            ready: false
        }
    },
    methods: {
        ownsExtension(code) {
            return Object.keys(this.ownedExtensions).includes(code);
        },
        ownsExtensionPremium(code) {
            return this.premiumExtensions.includes(code);
        },
        toggleOwnedExtension(code) {
            this.ownedExtensions[code] = !this.ownedExtensions[code];
        },
        togglePremiumExtension(code) {
            if (this.ownsExtensionPremium(code)) {
                this.premiumExtensions = this.premiumExtensions.filter(item => item != code);
            }
            else {
                this.premiumExtensions.push(code);
            }
        }
    },
    mounted() {
        this.ready = false;

        axios.get("/web-api/extensions")
            .then(response => {
                const data = response.data;

                this.extensions = data.extensions;
                this.ownedExtensions = data.ownedExtensions;
                this.premiumExtensions = data.premiumExtensions;
                this.isPremium = data.isPremium;

                this.ready = true;
            })
    }
}
</script>
