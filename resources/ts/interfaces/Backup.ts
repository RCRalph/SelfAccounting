interface BackupInformation {
    last: string | null
    tooltip: string | boolean
}

interface BackupAndRestore {
    backup: BackupInformation
    restore: BackupInformation
}

export type {
    BackupAndRestore,
}