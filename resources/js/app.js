require('./bootstrap');

import Vue from "vue";
import Vuetify from 'vuetify';
window.Vue = Vue;

const vuetify = require("./plugins/vuetify").default,
    router = require("./plugins/router").default;

Vue.use(vuetify);
Vue.use(router);

Vue.component("app-component", require("./components/new/App.vue").default);

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    router
});
