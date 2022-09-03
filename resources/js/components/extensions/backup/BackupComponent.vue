<template>
    <v-row>
        <v-col xl="4" offset-xl="4" lg="6" offset-lg="3" md="10" offset-md="1" cols="12">
            <v-card v-if="ready">
                <v-card-title class="justify-center text-capitalize pb-lg-0 text-h5">Backup</v-card-title>

                <v-card-actions class="d-flex justify-space-around flex-wrap px-3">
                    <v-tooltip bottom :disabled="!data.backup.tooltip">
                        <template v-slot:activator="{ on, attrs }">
                            <div v-on="on" v-bind="attrs">
                                <v-btn
                                    outlined width="185"
                                    class="ma-1" large
                                    :disabled="loading || !!data.backup.tooltip"
                                    :block="$vuetify.breakpoint.xs"
                                    :loading="loading"
                                    @click="create"
                                >
                                    Create backup
                                </v-btn>
                            </div>
                        </template>

                        <span>{{ data.backup.tooltip }}</span>
                    </v-tooltip>

                    <RestoreBackupDialogComponent
                        :tooltip="data.restore.tooltip"
                        @restored="restored"
                    ></RestoreBackupDialogComponent>
                </v-card-actions>

                <v-card-text class="pt-0">
                    <v-simple-table>
                        <template v-slot:default>
                            <tbody>
                                <tr v-if="data.backup.last">
                                    <td class="text-right text-h6" style="width: 50%">Last backup</td>

                                    <td class="text-h6" style="width: 50%">{{ dateWithCurrentTimeZone(data.backup.last, true) }}</td>
                                </tr>

                                <tr v-if="data.restore.last">
                                    <td class="text-right text-h6" style="width: 50%">Last restoration</td>

                                    <td class="text-h6" style="width: 50%">{{ dateWithCurrentTimeZone(data.restore.last, true) }}</td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </v-card-text>
            </v-card>

            <v-card v-else>
                <v-card-title>Backup data</v-card-title>

                <v-card-text class="d-flex justify-center">
                    <v-progress-circular
                        indeterminate
                        size="96"
                    ></v-progress-circular>
                </v-card-text>
            </v-card>
        </v-col>

        <SuccessSnackbarComponent v-model="success" :thing="thing"></SuccessSnackbarComponent>

        <ErrorSnackbarComponent v-model="error"></ErrorSnackbarComponent>
    </v-row>
</template>

<script>
import RestoreBackupDialogComponent from "@/extensions/backup/RestoreBackupDialogComponent.vue";
import SuccessSnackbarComponent from "@/SuccessSnackbarComponent.vue";
import ErrorSnackbarComponent from "@/ErrorSnackbarComponent.vue";

import { useCurrenciesStore } from "&/stores/currencies";
import { useExtensionsStore } from "&/stores/extensions";
import validation from "&/mixins/validation";
import main from "&/mixins/main";

export default {
    setup() {
        const currencies = useCurrenciesStore();
        const extensions = useExtensionsStore();

        return { currencies, extensions };
    },
    mixins: [validation, main],
    components: {
        RestoreBackupDialogComponent,
        SuccessSnackbarComponent,
        ErrorSnackbarComponent
    },
    data() {
        return {
            data: {},
            loading: false,

            ready: false,
            success: false,
            thing: "",
            error: false
        }
    },
    methods: {
        getData() {
            this.ready = false;

            axios.get(`/web-api/extensions/backup`)
                .then(response => {
                    const data = response.data;

                    this.data = data.data;

                    this.ready = true;
                })
        },
        create() {
            this.loading = true;

            axios.get(`/web-api/extensions/backup/create`)
                .then(response => {
                    const data = response.data;

                    const download = document.createElement("a");
                    download.style.display = "none";
                    download.href = `data:application/octet-stream;charset:utf-8,${encodeURIComponent(JSON.stringify(data))}`;
                    download.download = `${new Date().toISOString().split("T")[0]}.selfacc2`;

                    document.body.appendChild(download);
                    download.click();
                    document.body.removeChild(download);

                    this.thing = "created backup";
                    this.success = true;
                    this.loading = false;
                    this.getData();
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.loading = false, 2000);
                })
        },
        restored() {
            this.thing = "restored the backup";
            this.success = true;
            this.getData();
        }
    },
    mounted() {
        this.getData();
    }
}
</script>
