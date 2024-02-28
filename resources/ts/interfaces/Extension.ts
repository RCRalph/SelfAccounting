interface Extension {
    code: string
    title: string
    icon: string
    directory: string
    enabled: boolean
}

interface ExtensionData {
    id: number
    thumbnail: string
    short_description: string
    description: string
    bought: boolean
    price: number
    gallery: string[]
}

export type {
    Extension,
    ExtensionData,
}