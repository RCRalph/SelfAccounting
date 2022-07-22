<template>
    <v-app v-if="ready">
        <v-navigation-drawer v-model="drawer" permanent :mini-variant.sync="mini"
            :expand-on-hover="$vuetify.breakpoint.mdAndUp && !menuClicked" fixed
        >
            <v-list-item class="pa-2">
                <v-list-item-avatar>
                    <v-img src="/storage/SelfAccounting.svg"></v-img>
                </v-list-item-avatar>

                <v-list-item-title class="font-weight-black h5 m-0">SelfAccounting</v-list-item-title>

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
                            <v-list-item-icon>
                                <v-icon>{{ sublink.icon }}</v-icon>
                            </v-list-item-icon>

                            <v-list-item-content>
                                <v-list-item-title v-text="sublink.text"></v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-group>
                </div>
            </v-list>

            <v-divider class="my-1"></v-divider>

            <v-list>
                <v-list-item>
                    <v-list-item-icon>
                        <v-icon>mdi-currency-usd</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-select v-model="currencies.usedCurrency" :items="currencies.currencies" label="Currency" item-text="ISO"
                            item-value="id" @click="menuClicked = true" @input="menuClicked = false" dense
                            @blur="menuClicked = false"></v-select>
                    </v-list-item-content>
                </v-list-item>
            </v-list>

            <template v-slot:append>
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

export default {
    setup() {
        const currencies = useCurrenciesStore();
        const extensions = useExtensionsStore();

        return { currencies, extensions };
    },
    data() {
        return {
            drawer: true,
            profileItems: [
                { title: "View profile", icon: "mdi-account", link: "/profile" }
            ],
            mini: true,
            menuClicked: false,
            user: {},
            ready: false
        }
    },
    computed: {
        items() {
            const retArr = [
                { title: "Dashboard", icon: "mdi-view-dashboard", link: "/" },
                { title: "Income", icon: "fas fa-sign-in-alt", link: "/income" },
                { title: "Outcome", icon: "fas fa-sign-out-alt", link: "/outcome" },
                { title: "Settings", icon: "fas fa-cog", link: "/settings" }
            ];

            if (this.extensions.ownedExtensionsObjects.length) {
                const links = [{
                    icon: "mdi-shopping",
                    text: "Marketplace",
                    link: "/extensions/"
                }];

                this.extensions.ownedExtensionsObjects.forEach(item => {
                    links.push({
                        icon: item.icon,
                        text: item.title,
                        link: `/extension/${item.directory}`
                    });
                });

                retArr.push({ title: "Extensions", icon: "mdi-package-variant", links });
            }
            else {
                retArr.push({ title: "Extensions", icon: "mdi-package-variant", link: "/extensions" });
            }

            if (!this.user.hide_all_tutorials) {
                retArr.push({ title: "Getting started", icon: "mdi-school", link: "/getting-started" });
            }

            if (this.user.admin) {
                retArr.push({ title: "Admin", icon: "mdi-lock", link: "/admin" });
            }

            return retArr;
        }
    },
    methods: {
        logout() {
            document.getElementById("logout-form").submit();
        },
        updateUser(newUser) {
            this.user = {...this.user, ...newUser}
            this.$vuetify.theme.dark = this.user.darkmode;
        }
    },
    mounted() {
        this.ready = false;

        axios.get("/web-api/app")
            .then(response => {
                const data = response.data;

                this.user = data.user;
                this.$vuetify.theme.dark = data.user.darkmode;

                this.currencies.setCurrencies(data.currencies);
                this.currencies.changeCurrency(data.currencies[0].id);

                this.extensions.setExtensions(data.extensions);
                this.extensions.setOwnedExtensions(data.ownedExtensions);

                this.ready = true;
            });
    }
}
</script>
