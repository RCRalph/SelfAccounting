window.Vue = require('vue');
Vue.use(require("vue-resource"));

Vue.component('income-outcome-component', require('./components/income-outcome/IncomeOutcomeComponent.vue').default);

const app = new Vue({
    el: '#app'
});
