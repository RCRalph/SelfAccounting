import Vue from "vue";
import Vuetify from 'vuetify';

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Vue = Vue;

const vuetify = require("./plugins/vuetify").default;

Vue.use(vuetify);

Vue.component("home-component", require("@/home/HomeComponent.vue").default);
Vue.component("login-component", require("@/home/LoginComponent.vue").default);

new Vue({ el: '#app', vuetify: new Vuetify() });
