import Vue from "vue";
import VueRouter from "vue-router";

import DashboardComponent from "../components/new/dashboard/DashboardComponent.vue";

Vue.use(VueRouter)

const routes = [
    { path: "/", component: DashboardComponent }
]

export default new VueRouter({ routes/*, mode: "history"*/ })
