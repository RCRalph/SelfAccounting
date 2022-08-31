<template>
    <v-app>
        <v-app-bar fixed height="64">
            <a href="/" class="text-decoration-none white--text d-flex flex-nowrap align-center ml-0 ml-sm-3">
                <v-avatar size="40">
                    <v-img src="/storage/SelfAccounting.svg"></v-img>
                </v-avatar>

                <v-toolbar-title class="text-h5 ml-3">SelfAccounting</v-toolbar-title>
            </a>

            <v-spacer></v-spacer>

            <v-menu
                v-if="$vuetify.breakpoint.xs"
                rounded bottom
                transition="slide-y-transition"
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-btn icon v-bind="attrs" v-on="on">
                        <v-icon>mdi-menu</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item link href="/login">
                        <v-list-item-title>Login</v-list-item-title>
                    </v-list-item>

                    <v-list-item link href="/register">
                        <v-list-item-title>Register</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>

            <div v-else>
                <v-btn depressed large rounded href="/login">Login</v-btn>

                <v-btn depressed large rounded href="/register">Register</v-btn>
            </div>
        </v-app-bar>

        <v-main>
            <section>
                <v-parallax src="/storage/welcome/welcome.jpg" :height="parallaxHeight">
                    <v-layout column align-center justify-center class="white--text">
                        <h1
                            class="white--text mb-2 text-center font-weight-black"
                            style="text-shadow: 3px 3px #000000" :class="headerFontSize"
                        >
                            SelfAccounting
                        </h1>

                        <div
                            class="white--text mb-3 text-center font-weight-black"
                            style="font-weight: 900; text-shadow: 2px 2px #000000"
                            :class="subheaderFontSize"
                        >
                            Your personal accounting&nbsp;platform
                        </div>

                        <v-btn class="mt-5" dark x-large rounded href="/register">
                            Register now
                        </v-btn>
                    </v-layout>
                </v-parallax>
            </section>

            <section>
                <v-layout column wrap class="my-5" align-center>
                    <v-container>
                        <v-row>
                            <v-col cols="12" xl="3" md="6">
                                <v-card class="elevation-0 transparent">
                                    <v-card-text class="text-center">
                                        <v-icon style="font-size: 75px">fa-exchange</v-icon>
                                    </v-card-text>

                                    <v-card-title class="d-flex justify-center headline">Monitor your transactions</v-card-title>

                                    <v-card-text class="text-center">See how much money you earn and spend</v-card-text>
                                </v-card>
                            </v-col>

                            <v-col cols="12" xl="3" md="6">
                                <v-card class="elevation-0 transparent">
                                    <v-card-text class="text-center">
                                        <v-icon style="font-size: 75px">mdi-chart-bar</v-icon>
                                    </v-card-text>

                                    <v-card-title class="d-flex justify-center headline">Track your balance</v-card-title>

                                    <v-card-text class="text-center">Always be up-to-date with how much you have</v-card-text>
                                </v-card>
                            </v-col>

                            <v-col cols="12" xl="3" md="6">
                                <v-card class="elevation-0 transparent">
                                    <v-card-text class="text-center">
                                        <v-icon style="font-size: 75px">mdi-calendar-clock</v-icon>
                                    </v-card-text>

                                    <v-card-title class="d-flex justify-center headline">Plan ahead</v-card-title>

                                    <v-card-text class="text-center">Budget and organize your financial life</v-card-text>
                                </v-card>
                            </v-col>

                            <v-col cols="12" xl="3" md="6">
                                <v-card class="elevation-0 transparent">
                                    <v-card-text class="text-center">
                                        <v-icon style="font-size: 75px">mdi-chart-line</v-icon>
                                    </v-card-text>

                                    <v-card-title class="d-flex justify-center headline">Grow your savings</v-card-title>

                                    <v-card-text class="text-center">Analyze your expenses and start saving</v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-layout>
            </section>
        </v-main>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            parallaxHeight: self.innerHeight
        }
    },
    computed: {
        headerFontSize() {
            if (this.$vuetify.breakpoint.xs) {
                return "text-h3";
            }
            else if (this.$vuetify.breakpoint.sm) {
                return "text-h2"
            }

            return "text-h1";
        },
        subheaderFontSize() {
            if (this.$vuetify.breakpoint.xs) {
                return "text-h5";
            }
            else if (this.$vuetify.breakpoint.sm) {
                return "text-h4"
            }

            return "text-h4";
        }
    },
    mounted() {
        this.$nextTick(() => {
            window.addEventListener('resize', () => this.parallaxHeight = self.innerHeight);
        })
    }
};
</script>
