<template>
    <v-row v-if="ready && typeof backupInformation != 'undefined'">
        <v-col
            cols="12"
            md="10"
            offset-md="1"
            lg="6"
            offset-lg="3"
            xl="4"
            offset-xl="4"
            class="pa-0"
        >
            <v-card>
                <CardTitleWithButtons
                    title="Backup"
                    large-font
                ></CardTitleWithButtons>

                <v-card-actions class="d-flex justify-space-around flex-wrap px-3">
                    <v-tooltip
                        :disabled="typeof backupInformation.backup.tooltip != 'string' || loading.submit"
                        :text="backupInformation.backup.tooltip.toString()"
                        location="bottom"
                    >
                        <template v-slot:activator="{ props }">
                            <!-- Binding has to be on button's parent element, because disabled button will not trigger the tooltip -->
                            <div v-bind="props">
                                <v-btn
                                    :disabled="typeof backupInformation.backup.tooltip == 'string' || loading.submit"
                                    text="Create backup"
                                    variant="outlined"
                                    width="200"
                                    class="ma-1"
                                    size="large"
                                    @click="onCreate"
                                ></v-btn>
                            </div>
                        </template>
                    </v-tooltip>

                    <RestoreBackupDialogComponent
                        :tooltip="backupInformation.restore.tooltip"
                        @restore="getData"
                    ></RestoreBackupDialogComponent>
                </v-card-actions>

                <v-card-text v-if="showTable">
                    <v-table>
                        <tbody>
                            <tr v-if="typeof backupInformation.backup.last == 'string'">
                                <td class="text-right text-h6" style="width: 50%">
                                    Last backup
                                </td>

                                <td class="text-h6" style="width: 50%">
                                    {{ formatDate(new Date(backupInformation.backup.last), true) }}
                                </td>
                            </tr>

                            <tr v-if="typeof backupInformation.restore.last == 'string'">
                                <td class="text-right text-h6" style="width: 50%">
                                    Last restoration
                                </td>

                                <td class="text-h6" style="width: 50%">
                                    {{ formatDate(new Date(backupInformation.restore.last), true) }}
                                </td>
                            </tr>
                        </tbody>
                    </v-table>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>

    <v-overlay
        v-else
        :model-value="true"
        contained
    >
        <v-progress-circular
            indeterminate
            size="128"
        ></v-progress-circular>
    </v-overlay>
</template>

<script setup lang="ts">
import axios from "axios"
import { computed, onMounted, ref } from "vue"

import type { BackupAndRestore } from "@interfaces/Backup"
import type { Loading } from "@interfaces/App"

import { useStatusStore } from "@stores/status"
import useComponentState from "@composables/useComponentState"
import { formatDate } from "@composables/useDates"
import { downloadJSON } from "@composables/useDownload"

import CardTitleWithButtons from "@components/global/card/CardTitleWithButtonsComponent.vue"
import RestoreBackupDialogComponent from "@components/app/extensions/backup/RestoreBackupDialogComponent.vue"

const status = useStatusStore()

function useData() {
    const backupInformation = ref<BackupAndRestore>()

    function getData() {
        ready.value = false

        axios.get("/web-api/extensions/backup")
            .then(response => {
                const data = response.data

                backupInformation.value = data.backup_information

                ready.value = true
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
            })
    }

    return {getData, backupInformation}
}

function useBackup() {
    const loading = ref<Loading>({
        submit: false,
    })

    const showTable = computed(() => {
        return typeof backupInformation.value?.backup.last == "string" ||
            typeof backupInformation.value?.restore.last == "string"
    })

    function onCreate() {
        loading.value.submit = true

        axios.get(`/web-api/extensions/backup/create`)
            .then(response => {
                const data = response.data

                downloadJSON(
                    `${new Date().toISOString().split("T")[0]}.selfacc2`,
                    data,
                )

                status.showSuccess("created backup")
                getData()
                loading.value.submit = false
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.submit = false, 2000)
            })
    }

    return {loading, showTable, onCreate}
}

const {ready} = useComponentState()
const {getData, backupInformation} = useData()
const {loading, showTable, onCreate} = useBackup()

onMounted(getData)
</script>
