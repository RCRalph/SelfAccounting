import Vue from "vue";
import VueRouter from "vue-router";

import DashboardComponent from "@/dashboard/DashboardComponent.vue";
import IOComponent from "@/income-outcome/IOComponent.vue";
import CategoriesComponent from "@/categories/CategoriesComponent.vue";
import MeansComponent from "@/accounts/MeansComponent.vue";
import ProfileComponent from "@/profile/ProfileComponent.vue";
import ExtensionsComponent from "@/extensions/ExtensionsComponent.vue";
import GettingStartedComponent from "@/GettingStartedComponent.vue";
import ChartComponent from "@/charts/ChartComponent.vue";

import CashComponent from "@/extensions/cash/CashComponent.vue";
import ReportsComponent from "@/extensions/reports/ReportsComponent.vue";
import ViewReportComponent from "@/extensions/reports/ViewReportComponent.vue";
import BackupComponent from "@/extensions/backup/BackupComponent.vue";

Vue.use(VueRouter)

const routes = [
    { path: "/", component: DashboardComponent },
    { path: "/income", component: IOComponent, props: { type: "income" } },
    { path: "/outcome", component: IOComponent, props: { type: "outcome" } },
    { path: "/categories", component: CategoriesComponent },
    { path: "/accounts", component: MeansComponent },
    { path: "/profile", component: ProfileComponent },
    { path: "/getting-started", component: GettingStartedComponent },
    { path: "/charts/:id", component: ChartComponent },

    { path: "/extensions/store", component: ExtensionsComponent },
    { path: "/extensions/cash", component: CashComponent },
    { path: "/extensions/reports", component: ReportsComponent },
    { path: "/extensions/reports/:id", component: ViewReportComponent },
    { path: "/extensions/backup", component: BackupComponent },

    { path: "/admin/telescope", beforeEnter: () => window.location.href = "/telescope" },

    { path: "/payment/extensions/:id", beforeEnter: (to) => window.location.href = "/payment/extensions/" + to.params.id }
]

export default new VueRouter({ routes })
