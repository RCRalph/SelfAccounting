interface User {
    account_type: string
    admin: boolean
    darkmode: boolean
    hide_all_tutorials: boolean
    id: number
    profile_picture_link: string
    username: string
}

interface UserData extends User {
    since: string
    expiration: string
    email: string
}

interface Password {
    value: string,
    show: boolean,
}

interface UpdatePassword {
    current: Password,
    new: Password,
    confirm: Password
}

export type {
    User,
    UserData,
    UpdatePassword,
}