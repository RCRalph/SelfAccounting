interface BackupInformation {
    last: string | null
    tooltip: string | boolean
}

interface BackupAndRestore {
    backup: BackupInformation
    restore: BackupInformation
}

interface CategoryRestoreData {
    currency: string,
    name: string
}

interface AccountRestoreData extends CategoryRestoreData {
    start_date: Date
}

export type {
    BackupAndRestore,
    CategoryRestoreData,
    AccountRestoreData,
}