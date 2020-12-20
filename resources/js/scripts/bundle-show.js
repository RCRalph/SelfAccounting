Vue.component('bundle-toggle', require('../components/bundle-show/BundleToggle.vue').default);
Vue.component('premium-bundle-toggle', require('../components/bundle-show/PremiumBundleToggle.vue').default);

const app = new Vue({
    el: '#app'
});
