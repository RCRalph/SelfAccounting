import {defineStore} from "pinia"

export interface User {
    admin: boolean
    darkmode: boolean
    hide_all_tutorials: boolean
    id: number
    profile_picture_link: string
    username: string
}

interface State {
    data?: User
}

export const useUserStore = defineStore("user", {
    state: (): State => ({
        data: undefined,
    }),
    getters: {
        username(state: State): string {
            if (state.data == undefined) return ""
            
            return state.data.username.replaceAll(" ", String.fromCharCode(160))
        },
    },
    actions: {
        update(newUser: User): User {
            if (this.data == undefined) {
                this.data = newUser
            } else {
                this.data = {...this.data, ...newUser}
            }

            return this.data
        },
        updateTheme(themeName: string) {
            if (this.data == undefined) {
                throw new Error("Cannot set theme - user is undefined")
            } else {
                this.data.darkmode = themeName == "dark"
            }
        },
    },
})
