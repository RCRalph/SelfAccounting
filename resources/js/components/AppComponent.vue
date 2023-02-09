<template>
    <v-app v-if="ready">
        <v-navigation-drawer v-model="drawer" permanent :mini-variant.sync="mini"
            :expand-on-hover="$vuetify.breakpoint.mdAndUp && !menuClicked" fixed
        >
            <v-list-item class="pa-2">
                <v-list-item-avatar tile>
                    <img src="/storage/Logo.svg">
                </v-list-item-avatar>

                <v-list-item-title class="m-0">
                    <v-img :src="logoTextSource"></v-img>
                </v-list-item-title>

                <v-btn icon @click.stop="mini = !mini" v-if="$vuetify.breakpoint.smAndDown">
                    <v-icon>mdi-chevron-left</v-icon>
                </v-btn>
            </v-list-item>

            <v-divider class="my-1"></v-divider>

            <v-list dense>
                <div v-for="item in items" :key="item.title">
                    <v-list-item v-if="item.link" link :to="item.link">
                        <v-list-item-icon>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-group v-else link no-action :prepend-icon="item.icon" color="unset">
                        <template v-slot:activator>
                            <v-list-item-content>
                                <v-list-item-title v-text="item.title"></v-list-item-title>
                            </v-list-item-content>
                        </template>

                        <v-list-item
                            v-for="sublink in item.links"
                            :key="sublink.text"
                            :to="sublink.link"
                        >
                            <v-list-item-icon v-if="sublink.icon">
                                <v-icon>{{ sublink.icon }}</v-icon>
                            </v-list-item-icon>

                            <v-list-item-content>
                                <v-list-item-title v-text="sublink.text" :style="!sublink.icon && 'padding-left: 18px !important'"></v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-group>
                </div>
            </v-list>

            <v-divider class="my-1"></v-divider>

            <template v-slot:append>
                <v-list-item style="height: 60px">
                    <v-list-item-icon>
                        <v-icon>mdi-settings</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-row dense class="py-0">
                            <v-col cols="9">
                                <v-select
                                    v-model="currencies.usedCurrency"
                                    :items="currencies.currencies"
                                    label="Currency" item-text="ISO"
                                    item-value="id" style="min-width: 70px"
                                    dense hide-details
                                    @click="menuClicked = true"
                                    @input="menuClicked = false"
                                    @blur="menuClicked = false"
                                ></v-select>
                            </v-col>

                            <v-col cols="3">
                                <ThemeToggleComponent v-model="user.darkmode"></ThemeToggleComponent>
                            </v-col>
                        </v-row>
                    </v-list-item-content>
                </v-list-item>

                <v-menu rounded="lg" offset-y top transition="scale-transition">
                    <template v-slot:activator="{ attrs, on }">
                        <v-list-item class="pa-2" v-bind="attrs" v-on="on">
                            <v-list-item-avatar>
                                <v-img
                                    :src="user.profile_picture_link">
                                </v-img>
                            </v-list-item-avatar>

                            <v-list-item-title class="font-weight-black h5 m-0">{{ user.username }}</v-list-item-title>

                            <v-btn icon>
                                <v-icon>mdi-chevron-up</v-icon>
                            </v-btn>
                        </v-list-item>
                    </template>

                    <v-list>
                        <v-list-item v-for="(item, index) in profileItems" :key="index" link :to="item.link">
                            <v-list-item-icon>
                                <v-icon>{{ item.icon }}</v-icon>
                            </v-list-item-icon>

                            <v-list-item-content>
                                <v-list-item-title>{{ item.title }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>

                        <v-list-item link @click="logout">
                            <v-list-item-icon>
                                <v-icon>mdi-key</v-icon>
                            </v-list-item-icon>

                            <v-list-item-content>
                                <v-list-item-title>Logout</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </template>
        </v-navigation-drawer>

        <TutorialComponent
            v-if="!user.hide_all_tutorials"
            v-model="disabledTutorials"
            :tutorials="tutorials"
            @hideAll="updateUser"
        ></TutorialComponent>

        <PremiumExpiredComponent
            v-model="premiumExpired"
        ></PremiumExpiredComponent>

        <v-main>
            <div class="menu-margin">
                <div class="ma-4">
                    <router-view @updatedUser="updateUser"></router-view>
                </div>
            </div>
        </v-main>
    </v-app>

    <v-overlay v-else :value="true" opacity="1">
        <v-progress-circular
            indeterminate
            size="128"
        ></v-progress-circular>
    </v-overlay>
</template>

<script>
import { useCurrenciesStore } from "&/stores/currencies";
import { useExtensionsStore } from "&/stores/extensions";

import TutorialComponent from "@/TutorialComponent.vue";
import PremiumExpiredComponent from "@/PremiumExpiredComponent.vue";
import ThemeToggleComponent from "@/ThemeToggleComponent.vue";

export default {
    setup() {
        const currencies = useCurrenciesStore();
        const extensions = useExtensionsStore();

        return { currencies, extensions };
    },
    components: {
        TutorialComponent,
        PremiumExpiredComponent,
        ThemeToggleComponent
    },
    data() {
        return {
            drawer: true,
            profileItems: [
                { title: "View profile", icon: "mdi-account", link: "/profile" }
            ],
            charts: [],
            tutorials: [],
            disabledTutorials: [],
            mini: true,
            menuClicked: false,
            user: {},
            premiumExpired: false,
            ready: false
        }
    },
    computed: {
        items() {
            const retArr = [
                { title: "Dashboard", icon: "mdi-view-dashboard", link: "/" },
                { title: "Income", icon: "fas fa-sign-in-alt", link: "/income" },
                { title: "Outcome", icon: "fas fa-sign-out-alt", link: "/outcome" },
                { title: "Settings", icon: "mdi-settings", link: "/settings" }
            ];

            if (this.charts.length) {
                const chartLinks = this.charts.map(item => ({ text: item.name, link: `/charts/${item.id}` }));

                retArr.push({
                    title: "Charts",
                    icon: "mdi-chart-bar",
                    links: chartLinks
                })
            }

            if (this.extensions.ownedExtensionsObjects.length) {
                const extensionLinks = [{
                    icon: "mdi-shopping",
                    text: "Store",
                    link: "/extensions/store"
                }];

                this.extensions.ownedExtensionsObjects.forEach(item => {
                    extensionLinks.push({
                        icon: item.icon,
                        text: item.title,
                        link: `/extensions/${item.directory}`
                    });
                });

                retArr.push({ title: "Extensions", icon: "mdi-package-variant", links: extensionLinks });
            }
            else {
                retArr.push({ title: "Extensions", icon: "mdi-package-variant", link: "/extensions/store" });
            }

            if (!this.user.hide_all_tutorials) {
                retArr.push({ title: "Getting started", icon: "mdi-school", link: "/getting-started" });
            }

            if (this.user.admin) {
                retArr.push({ title: "Admin", icon: "mdi-lock", links: [
                    {
                        icon: "fab fa-laravel",
                        text: "Laravel Telescope",
                        link: "/admin/telescope"
                    }
                ]});
            }

            return retArr;
        },
        logoTextSource() {
            return `/storage/Logo text ${this.$vuetify.theme.dark ? 'dark' : 'light'}.svg`;
        }
    },
    methods: {
        logout() {
            document.getElementById("logout-form").submit();
        },
        updateUser(newUser) {
            this.user = {...this.user, ...newUser};
        }
    },
    mounted() {
        this.ready = false;

        axios.get("/web-api/app")
            .then(response => {
                const data = response.data;

                this.user = data.user;
                this.$vuetify.theme.dark = data.user.darkmode;

                this.premiumExpired = data.premiumExpired;
                this.charts = data.charts;
                this.tutorials = data.tutorials;
                this.disabledTutorials = data.disabledTutorials;

                this.currencies.setCurrencies(data.currencies);
                this.currencies.changeCurrency(data.currencies[0].id);

                this.extensions.setExtensions(data.extensions);
                this.extensions.setOwnedExtensions(data.ownedExtensions);

                this.ready = true;
            });
    }
}
</script>
