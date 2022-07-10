<template>
    <v-app v-if="ready">
        <v-navigation-drawer v-model="drawer" permanent :mini-variant.sync="mini"
            :expand-on-hover="$vuetify.breakpoint.mdAndUp && !menuClicked" fixed>
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
                <v-list-item v-for="item in items" :key="item.title" link :to="item.link">
                    <v-list-item-icon>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
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
                                    :src="user.profile_picture">
                                </v-img>
                            </v-list-item-avatar>

                            <v-list-item-title class="font-weight-black h5 m-0">{{ user.username }}</v-list-item-title>

                            <v-btn icon>
                                <v-icon>mdi-chevron-up</v-icon>
                            </v-btn>
                        </v-list-item>
                    </template>

                    <v-list>
                        <v-list-item v-for="(item, index) in profileItems" :key="index" link>
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
                    <router-view></router-view>
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
import { useCurrenciesStore } from '&/stores/currencies';

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    data() {
        return {
            drawer: true,
            items: [
                { title: "Dashboard", icon: "mdi-view-dashboard", link: "/" },
                { title: "Income", icon: "fas fa-sign-in-alt", link: "/income" },
                { title: "Outcome", icon: "fas fa-sign-out-alt", link: "/outcome" },
                { title: "Settings", icon: "fas fa-cog", link: "/settings" },
                { title: "Getting started", icon: "mdi-school", link: "/getting-started" },
                { title: "Bundles", icon: "mdi-package-variant", link: "/bundles" },
                { title: "Admin", icon: "mdi-lock", link: "/admin" },
            ],
            profileItems: [
                { title: "View profile", icon: "mdi-account", link: "/profile" }
            ],
            mini: true,
            menuClicked: false,
            user: {},
            ready: false
        }
    },
    methods: {
        logout() {
            document.getElementById("logout-form").submit();
        }
    },
    mounted() {
        this.ready = false;

        axios.get("/web-api/app")
            .then(response => {
                const data = response.data;

                this.user = data.user;
                this.$vuetify.theme.dark = data.user.darkmode;

                this.currencies.currencies = data.currencies;
                this.currencies.usedCurrency = data.currencies[0].id;

                this.ready = true;
            })
    }
}
</script>
