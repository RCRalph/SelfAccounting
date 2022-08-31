require('./bootstrap');

import Vue from "vue";
import Vuetify from 'vuetify';
import { createPinia, PiniaVuePlugin } from 'pinia';
window.Vue = Vue;

const vuetify = require("./plugins/vuetify").default,
    router = require("./plugins/router").default,
    pinia = createPinia();

Vue.use(vuetify);
Vue.use(router);
Vue.use(PiniaVuePlugin)

Vue.component("app-component", require("@/AppComponent.vue").default);

new Vue({ el: '#app', vuetify: new Vuetify(), router, pinia });
