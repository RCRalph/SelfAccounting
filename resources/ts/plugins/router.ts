import { createRouter, createWebHashHistory } from "vue-router"
import type { RouteRecordRaw } from "vue-router"

// Components
import DashboardComponent from "@components/dashboard/DashboardComponent.vue"
import TransactionsComponent from "@components/transactions/TransactionsComponent.vue"
import TransfersComponent from "@components/transfers/TransfersComponent.vue"
import CategoriesComponent from "@components/categories/CategoriesComponent.vue"
import ChartsComponent from "@components/charts/ChartsComponent.vue"

// Extension components
import ExtensionsComponent from "@components/extensions/ExtensionsComponent.vue"

// Admin components
import AdminComponent from "@components/admin/AdminComponent.vue"
import AdminDashboardComponent from "@components/admin/AdminDashboardComponent.vue"

const routes: RouteRecordRaw[] = [
    {
        path: "/",
        component: DashboardComponent,
    },
    {
        path: "/income",
        component: TransactionsComponent,
        props: {
            type: "income",
        },
    },
    {
        path: "/expenses",
        component: TransactionsComponent,
        props: {
            type: "expenses",
        },
    },
    {
        path: "/transfers",
        component: TransfersComponent,
    },
    {
        path: "/categories",
        component: CategoriesComponent,
    },
    {
        path: "/charts",
        component: ChartsComponent,
    },
]

const extensionRoutes: RouteRecordRaw[] = [
    {
        path: "/extensions",
        component: ExtensionsComponent,
        children: [],
    },
]

const adminRoutes: RouteRecordRaw[] = [
    {
        path: "/admin",
        component: AdminComponent,
        children: [
            {
                path: "",
                component: AdminDashboardComponent,
            },
        ],
    },
]

export default createRouter({
    history: createWebHashHistory(),
    routes: routes
        .concat(extensionRoutes)
        .concat(adminRoutes)
        .concat([
            {
                path: "/payment/extensions/:id",
                redirect: (to) => window.location.href = "/payment/extensions/" + to.params.id,
            },
        ]),
    /*{
        path: "/transfers",
        component: () => import("@components/transfers/TransfersComponent.vue"),
    },
    {
        path: "/categories",
        component: () => import("@components/categories/CategoriesComponent.vue"),
    },
    {
        path: "/accounts",
        component: () => import("@components/accounts/AccountsComponent.vue"),
    },
    {
        path: "/profile",
        component: () => import("@components/profile/ProfileComponent.vue"),
    },
    {
        path: "/getting-started",
        component: () => import("@components/GettingStartedComponent.vue"),
    },*/


    /*{
        path: "/charts/:id",
        component: () => import("@components/charts/ChartComponent.vue"),
    },
    {
        path: "/extensions/store",
        component: () => import("@components/extensions/ExtensionsComponent.vue"),
    },
    {
        path: "/extensions/cash",
        component: () => import("@components/extensions/cash/CashComponent.vue"),
    },
    {
        path: "/extensions/reports",
        component: () => import("@components/extensions/reports/ReportsComponent.vue"),
    },
    {
        path: "/extensions/reports/:id",
        component: () => import("@components/extensions/reports/ViewReportComponent.vue"),
    },
    {
        path: "/extensions/backup",
        component: () => import("../components/extensions/backup/BackupComponent.vue"),
    },*/
})
