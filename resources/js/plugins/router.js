import Vue from "vue";
import VueRouter from "vue-router";

import DashboardComponent from "../components/new/dashboard/DashboardComponent.vue";
import IOComponent from "../components/new/income-outcome/IOComponent.vue";

Vue.use(VueRouter)

const routes = [
    { path: "/", component: DashboardComponent },
    { path: "/income", component: IOComponent, props: { type: "income" } },
    { path: "/outcome", component: IOComponent, props: { type: "outcome" } }
]

export default new VueRouter({ routes/*, mode: "history"*/ })
