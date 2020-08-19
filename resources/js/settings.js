window.Vue = require('vue');

Vue.component('settings-component', require('./components/SettingsComponent.vue').default);

const app = new Vue({
    el: '#app'
});
