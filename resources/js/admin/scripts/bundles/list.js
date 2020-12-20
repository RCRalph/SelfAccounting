Vue.component('bundle-list-component', require('../../components/bundles/BundleListComponent.vue').default);
Vue.component('pagination', require('laravel-vue-pagination'));

const app = new Vue({
    el: '#app'
});
