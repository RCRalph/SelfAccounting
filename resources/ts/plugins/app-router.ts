import { createRouter, createWebHashHistory, type RouteRecordRaw } from "vue-router"

// Components
import DashboardComponent from "@components/app/dashboard/DashboardComponent.vue"
import TransactionsComponent from "@components/app/transactions/TransactionsComponent.vue"
import TransfersComponent from "@components/app/transfers/TransfersComponent.vue"
import CategoriesComponent from "@components/app/categories/CategoriesComponent.vue"
import AccountsComponent from "@components/app/accounts/AccountsComponent.vue"
import ChartsComponent from "@components/app/charts/ChartsComponent.vue"
import ProfileComponent from "@components/app/profile/ProfileComponent.vue"
import GettingStartedComponent from "@components/app/getting-started/GettingStartedComponent.vue"

// Extension components
import ExtensionsComponent from "@components/app/extensions/ExtensionsComponent.vue"
import ExtensionsStoreComponent from "@components/app/extensions/store/ExtensionsStoreComponent.vue"
import BackupComponent from "@components/app/extensions/backup/BackupComponent.vue"
import CashComponent from "@components/app/extensions/cash/CashComponent.vue"
import ReportsComponent from "@components/app/extensions/reports/ReportsComponent.vue"
import ViewReportComponent from "@components/app/extensions/reports/ViewReportComponent.vue"
import BudgetComponent from "@components/app/extensions/budgets/BudgetComponent.vue"

// Admin components
import AdminComponent from "@components/app/admin/AdminComponent.vue"
import AdminDashboardComponent from "@components/app/admin/AdminDashboardComponent.vue"

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
    {
        path: "/getting-started",
        component: GettingStartedComponent,
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
            {
                path: "budgets",
                component: BudgetComponent,
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
})
