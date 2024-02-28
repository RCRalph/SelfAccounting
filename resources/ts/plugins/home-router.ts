import { createRouter, createWebHistory } from "vue-router"

import HomeComponent from "@components/index/HomeComponent.vue"
import LoginComponent from "@components/index/LoginComponent.vue"
import RegisterComponent from "@components/index/RegisterComponent.vue"
import PasswordEmailComponent from "@components/index/PasswordEmailComponent.vue"
import PasswordResetComponent from "@components/index/PasswordResetComponent.vue"

export default createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            component: HomeComponent,
        },
        {
            path: "/login",
            component: LoginComponent,
        },
        {
            path: "/register",
            component: RegisterComponent,
        },
        {
            path: "/password/reset",
            component: PasswordEmailComponent,
        },
        {
            path: "/password/reset/:token",
            component: PasswordResetComponent,
        },
    ],
})
