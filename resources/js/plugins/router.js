import Vue from "vue";
import VueRouter from "vue-router";

import DashboardComponent from "../components/new/Dashboard.vue";

Vue.use(VueRouter)

const routes = [
    { path: "/", component: DashboardComponent }
]

export default new VueRouter({ routes/*, mode: "history"*/ })
