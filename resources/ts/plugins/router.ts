import {
    createRouter,
    createWebHashHistory,
    type RouteRecordRaw,
} from "vue-router"

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
                path: "",
                component: ExtensionsStoreComponent,
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
                redirect: (to) => window.location.href = "/payment/extensions/" + to.params.id,
            },
        ]),
    /*
    {
        path: "/getting-started",
        component: () => import("@components/GettingStartedComponent.vue"),
    },*/


    /*{
        path: "/charts/:id",
        component: () => import("@components/charts/ChartComponent.vue"),
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
