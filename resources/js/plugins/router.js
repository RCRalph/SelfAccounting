import Vue from "vue";
import VueRouter from "vue-router";

import DashboardComponent from "@/dashboard/DashboardComponent.vue";
import IOComponent from "@/income-outcome/IOComponent.vue";
import SettingsComponent from "@/settings/SettingsComponent.vue";
import ProfileComponent from "@/profile/ProfileComponent.vue";
import ExtensionsComponent from "@/extensions/ExtensionsComponent.vue";
import GettingStartedComponent from "@/GettingStartedComponent.vue";

import CashComponent from "@/extensions/cash/CashComponent.vue";

Vue.use(VueRouter)

const routes = [
    { path: "/", component: DashboardComponent },
    { path: "/income", component: IOComponent, props: { type: "income" } },
    { path: "/outcome", component: IOComponent, props: { type: "outcome" } },
    { path: "/settings", component: SettingsComponent },
    { path: "/profile", component: ProfileComponent },
    { path: "/getting-started", component: GettingStartedComponent },

    { path: "/extensions/store", component: ExtensionsComponent },
    { path: "/extensions/cash", component: CashComponent }
]

export default new VueRouter({ routes })
