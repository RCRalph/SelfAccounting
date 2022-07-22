import Vue from "vue";
import VueRouter from "vue-router";

import DashboardComponent from "@/dashboard/DashboardComponent.vue";
import IOComponent from "@/income-outcome/IOComponent.vue";
import SettingsComponent from "@/settings/SettingsComponent.vue";
import ProfileComponent from "@/profile/ProfileComponent.vue";
import BundlesComponent from "@/bundles/BundlesComponent.vue";

Vue.use(VueRouter)

const routes = [
    { path: "/", component: DashboardComponent },
    { path: "/income", component: IOComponent, props: { type: "income" } },
    { path: "/outcome", component: IOComponent, props: { type: "outcome" } },
    { path: "/settings", component: SettingsComponent },
    { path: "/profile", component: ProfileComponent },
    { path: "/bundles", component: BundlesComponent }
]

export default new VueRouter({ routes/*, mode: "history"*/ })
