import { createRouter, createWebHashHistory, type RouteRecordRaw } from "vue-router"

// Components
import DashboardComponent from "@components/dashboard/DashboardComponent.vue"
import TransactionsComponent from "@components/transactions/TransactionsComponent.vue"
import TransfersComponent from "@components/transfers/TransfersComponent.vue"
import CategoriesComponent from "@components/categories/CategoriesComponent.vue"
import AccountsComponent from "@components/accounts/AccountsComponent.vue"
import ChartsComponent from "@components/charts/ChartsComponent.vue"
import ProfileComponent from "@components/profile/ProfileComponent.vue"

// Extension components
import ExtensionsComponent from "@components/extensions/ExtensionsComponent.vue"
import ExtensionsStoreComponent from "@components/extensions/store/ExtensionsStoreComponent.vue"
import BackupComponent from "@components/extensions/backup/BackupComponent.vue"
import CashComponent from "@components/extensions/cash/CashComponent.vue"
import ReportsComponent from "@components/extensions/reports/ReportsComponent.vue"
import ViewReportComponent from "@components/extensions/reports/ViewReportComponent.vue"

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
        path: "/accounts",
        component: AccountsComponent,
    },
    {
        path: "/charts",
        component: ChartsComponent,
    },
    {
        path: "/profile",
        component: ProfileComponent,
    },
]

const extensionRoutes: RouteRecordRaw[] = [
    {
        path: "/extensions",
        component: ExtensionsComponent,
        children: [
            {
                path: "store",
                component: ExtensionsStoreComponent,
            },
            {
                path: "backup",
                component: BackupComponent,
            },
            {
                path: "cash",
                component: CashComponent,
            },
            {
                path: "reports",
                component: ReportsComponent,
            },
            {
                path: "reports/:id",
                component: ViewReportComponent,
            },
        ],
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
                redirect: to => window.location.href = "/payment/extensions/" + to.params.id,
            },
        ]),
    /*
    {
        path: "/getting-started",
        component: () => import("@components/GettingStartedComponent.vue"),
    },*/
})
