import {createRouter, createWebHashHistory} from "vue-router"

import TestComponent from "@components/TestComponent.vue"
import ChartsComponent from "@components/charts/ChartsComponent.vue"
import ExtensionsComponent from "@components/extensions/ExtensionsComponent.vue"

// Admin components
import AdminComponent from "@components/admin/AdminComponent.vue"
import AdminDashboardComponent from "@components/admin/AdminDashboardComponent.vue"

export default createRouter({
    history: createWebHashHistory(),
    routes: [
        {
            path: "/",
            component: TestComponent,
        },
        {
            path: "/income",
            component: TestComponent,
            props: {type: "income"},
        },
        {
            path: "/expenses",
            component: TestComponent,
            props: {type: "expenses"},
        },
        {
            path: "/transfers",
            component: TestComponent,
        },
        {
            path: "/categories",
            component: TestComponent,
        },
        {
            path: "/accounts",
            component: TestComponent,
        },
        {
            path: "/profile",
            component: TestComponent,
        },
        {
            path: "/getting-started",
            component: TestComponent,
        },
        {
            path: "/charts",
            component: ChartsComponent,
        },
        {
            path: "/extensions",
            component: ExtensionsComponent,
        },
        {
            path: "/extensions/store",
            component: TestComponent,
        },
        {
            path: "/extensions/cash",
            component: TestComponent,
        },
        {
            path: "/extensions/reports",
            component: TestComponent,
        },
        {
            path: "/extensions/reports/:id",
            component: TestComponent,
        },
        {
            path: "/extensions/backup",
            component: TestComponent,
        },
        {
            path: "/admin",
            component: AdminComponent,
            children: [
                {
                    path: "",
                    component: AdminDashboardComponent,
                },
            ],
        },/*
        {
            path: "/income",
            component: () => import("@components/transactions/TransactionsComponent.vue"),
            props: {type: "income"},
        },
        {
            path: "/expenses",
            component: () => import("@components/transactions/TransactionsComponent.vue"),
            props: {type: "expenses"},
        },
        {
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
        },
        {
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
        {
            path: "/admin/telescope",
            redirect: () => window.location.href = "/telescope",
        },
        {
            path: "/payment/extensions/:id",
            redirect: (to) => window.location.href = "/payment/extensions/" + to.params.id,
        },
    ],
})
