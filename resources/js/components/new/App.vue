<template>
    <v-app>
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
                <v-list-item v-for="item in items" :key="item.title" link>
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
                        <v-select v-model="currentCurrency" :items="currencies" label="Currency" item-text="name"
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
                                    src="https://s3.eu-de.cloud-object-storage.appdomain.cloud/selfaccountingprod/profile_pictures/DrQ8cTRjZNcjtmINLb4g3xaKaWhLr6V50dynZQEzbSUrMQpBoe.jpg">
                                </v-img>
                            </v-list-item-avatar>

                            <v-list-item-title class="font-weight-black h5 m-0">Louna</v-list-item-title>

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
</template>

<script>
export default {
    data() {
        return {
            drawer: true,
            items: [
                { title: "Dashboard", icon: "mdi-view-dashboard", link: "/" },
                { title: "Income", icon: "fas fa-sign-in-alt", link: "/income" },
                { title: "Outcome", icon: "fas fa-sign-out-alt", link: "/outcome" },
                { title: "Bundles", icon: "mdi-package-variant", link: "/bundles" },
                { title: "Settings", icon: "fas fa-cog", link: "/settings" },
                { title: "Admin", icon: "mdi-lock", link: "/admin" },
                { title: "Getting started", icon: "mdi-school", link: "/getting-started" },
            ],
            profileItems: [
                { title: "View profile", icon: "mdi-account", link: "/profile" }
            ],
            currencies: [
                {
                    id: 1,
                    name: "USD"
                },
                {
                    id: 2,
                    name: "EUR"
                }
            ],
            mini: true,
            menuClicked: false,
            currentCurrency: 1
        }
    },
    methods: {
        logout() {
            document.getElementById("logout-form").submit();
        }
    }
}
</script>

<style>
</style>
