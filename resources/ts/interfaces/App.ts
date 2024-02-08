interface MenuItem {
    title: string
    icon: string
    link: string
}

interface Loading {
    submit?: boolean
    table?: boolean
    title?: boolean
    enabled?: boolean
}

interface Icon {
    name: string
    icon: string | null
}

export type {
    MenuItem,
    Loading,
    Icon,
}
