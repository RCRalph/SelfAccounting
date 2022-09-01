<template>
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
</template>
