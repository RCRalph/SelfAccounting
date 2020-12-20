Vue.component('user-list-component', require('../components/admin/users/UserListComponent.vue').default);
Vue.component('pagination', require('laravel-vue-pagination'));

const app = new Vue({
    el: '#app'
});
