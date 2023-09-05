interface MenuItem {
    title: string
    icon: string
    link: string
}

interface Loading {
    submit?: boolean
    table?: boolean
    title?: boolean
}

export type {
    MenuItem,
    Loading,
}
