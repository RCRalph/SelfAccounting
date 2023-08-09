<template>
    <v-card>
        <router-link :to="`/extensions/${extension.directory}`" style="text-decoration: none; color: inherit;">
            <v-img height="250" :src="extension.thumbnail_link"></v-img>

            <v-card-title class="d-flex justify-center">{{ extension.title }}</v-card-title>

            <v-card-text>
                <div class="text-center">{{ extension.short_description }}</div>
            </v-card-text>
        </router-link>

        <v-card-actions>
            <v-row v-if="ownsExtension">
                <v-col cols="12" sm="4" offset-sm="2">
                    <v-btn outlined block
                        :color="ownedExtensions[extension.code] ? 'error' : 'success'"
                        @click="toggleOwnedExtension"
                        :loading="loading"
                    >{{ ownedExtensionEnabled ? 'Disable' : 'Enable' }}</v-btn>
                </v-col>

                <v-col cols="12" sm="4">
                    <ExtensionDetailsDialogComponent
                        :extension="extension"
                    ></ExtensionDetailsDialogComponent>
                </v-col>
            </v-row>

            <v-row v-else>
                <v-col cols="12" sm="4">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn color="primary" outlined block v-on="on" v-bind="attrs" :to="`/payment/extensions/${extension.id}`">
                                Buy
                            </v-btn>
                        </template>

                        <span>€{{ extension.price }}</span>
                    </v-tooltip>
                </v-col>

                <v-col cols="12" sm="4">
                    <ExtensionDetailsDialogComponent
                        :extension="extension"
                    ></ExtensionDetailsDialogComponent>
                </v-col>

                <v-col cols="12" sm="4">
                    <v-tooltip bottom v-if="isPremium">
                        <template v-slot:activator="{ on, attrs }">
                            <div v-on="on" v-bind="attrs">
                                <v-btn color="amber" outlined block
                                    @click="togglePremiumExtension"
                                    :loading="loading"
                                >{{ ownsExtensionPremium ? 'Disable' : 'Enable' }}</v-btn>
                            </div>
                        </template>

                        <span>{{ ownsExtensionPremium ? 'Disable' : 'Enable' }} with Premium</span>
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

        <ErrorSnackbarComponent v-model="error"></ErrorSnackbarComponent>
    </v-card>
</template>

<script>
import ExtensionDetailsDialogComponent from "@/extensions/ExtensionDetailsDialogComponent.vue";
import ErrorSnackbarComponent from "@/ErrorSnackbarComponent.vue";

export default {
    components: {
        ExtensionDetailsDialogComponent,
        ErrorSnackbarComponent
    },
    props: {
        extension: {
            required: true,
            type: Object
        },
        ownedExtensions: {
            required: true,
            type: Array
        },
        premiumExtensions: {
            required: true,
            type: Array
        },
        isPremium: {
            required: true,
            type: Boolean
        }
    },
    data() {
        return {
            loading: false,
            error: false
        }
    },
    computed: {
        ownsExtension() {
            return Object.keys(this.ownedExtensions).includes(this.extension.code);
        },
        ownsExtensionPremium() {
            return this.premiumExtensions.includes(this.extension.code);
        },
        ownedExtensionEnabled() {
            return this.ownedExtensions[this.extension.code];
        }
    },
    methods: {
        toggleOwnedExtension() {
            this.loading = true;

            axios.post(`/web-api/extensions/${this.extension.code}/toggle`)
                .then(response => {
                    this.ownedExtensions[this.extension.code] = !this.ownedExtensions[this.extension.code];
                    this.$emit("updated", response.data.extensions)

                    this.loading = false;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.loading = false, 2000);
                });
        },
        togglePremiumExtension() {
            this.loading = true;

            axios.post(`/web-api/extensions/${this.extension.code}/toggle-premium`)
                .then(response => {
                    this.$emit("togglePremiumExtension", this.extension.code);
                    this.$emit("updated", response.data.extensions)

                    this.loading = false;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.loading = false, 2000);
                });
        }
    }
}
</script>
