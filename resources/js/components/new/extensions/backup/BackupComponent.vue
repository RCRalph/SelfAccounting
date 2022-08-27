<template>
    <v-row>
        <v-col xl="4" offset-xl="4" lg="6" offset-lg="3" md="10" offset-md="1" cols="12">
            <v-card v-if="ready">
                <v-card-title class="justify-center text-capitalize pb-lg-0 text-h5">Backup</v-card-title>

                <v-card-actions class="d-flex justify-space-around flex-wrap px-3">
                    <v-btn outlined width="185" class="ma-1" large :block="$vuetify.breakpoint.xs">Create backup</v-btn>

                    <v-btn outlined width="185" class="ma-1" large :block="$vuetify.breakpoint.xs">Restore from file</v-btn>
                </v-card-actions>

                <v-card-text class="pt-0">
                    <v-simple-table>
                        <template v-slot:default>
                            <tbody>
                                <tr>
                                    <td class="text-right text-h6" style="width: 50%">Last backup</td>

                                    <td class="text-h6" style="width: 50%">{{ dateWithCurrentTimeZone(data.backup.last, true) }}</td>
                                </tr>

                                <tr>
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
        SuccessSnackbarComponent,
        ErrorSnackbarComponent
    },
    data() {
        return {
            data: {},

            ready: false,
            success: false,
            thing: "",
            error: false
        }
    },
    computed: {
        backupDate() {
            return this.dateWithCurrentTimeZone(this.data.backup.last, true);
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
        }
    },
    mounted() {
        this.getData();
    }
}
</script>
