<template>
    <v-row v-if="ready">
        <v-col xl="8" offset-xl="2" lg="10" offset-lg="1" cols="12">
            <v-card class="loading-height">
                <v-card-title class="justify-sm-space-between justify-center text-h5 text-capitalize pb-lg-0 flex-sm-row flex-column align-center">
                    <div class="mb-sm-0 mb-3">Accounts</div>

                    <AddAccountDialogComponent
                        @added="added"
                    ></AddAccountDialogComponent>
                </v-card-title>

                <v-card-text>
                    <v-data-table
                        hide-default-footer
                        :headers="headers"
                        :items="accounts"
                        :mobile-breakpoint="0"
                        :loading="tableLoading"
                        disable-filtering
                        disable-sort
                        disable-pagination
                    >
                        <template v-slot:[`item.icon`]="{ item }">
                            <v-icon v-if="item.icon">{{ item.icon }}</v-icon>

                            <span v-else>N/A</span>
                        </template>

                        <template v-slot:[`item.name`]="{ item }">
                            <span style="white-space: nowrap">{{ item.name }}</span>
                        </template>

                        <template v-slot:[`item.used_in_income`]="{ item }">
                            <v-simple-checkbox v-model="item.used_in_income" disabled off-icon="mdi-close"></v-simple-checkbox>
                        </template>

                        <template v-slot:[`item.used_in_expences`]="{ item }">
                            <v-simple-checkbox v-model="item.used_in_expences" disabled off-icon="mdi-close"></v-simple-checkbox>
                        </template>

                        <template v-slot:[`item.show_on_charts`]="{ item }">
                            <v-simple-checkbox v-model="item.show_on_charts" disabled off-icon="mdi-close"></v-simple-checkbox>
                        </template>

                        <template v-slot:[`item.count_to_summary`]="{ item }">
                            <v-simple-checkbox v-model="item.count_to_summary" disabled off-icon="mdi-close"></v-simple-checkbox>
                        </template>

                        <template v-slot:[`item.actions`]="{ item }">
                            <td>
                                <div class="d-flex flex-nowrap justify-center align-center">
                                    <EditAccountDialogComponent
                                        :id="item.id"
                                        @updated="updated"
                                    ></EditAccountDialogComponent>

                                    <DeleteDialogComponent
                                        thing="account"
                                        :url="`accounts/account/${item.id}`"
                                        @deleted="deleted"
                                    ></DeleteDialogComponent>
                                </div>
                            </td>
                        </template>
                    </v-data-table>
                </v-card-text>

                <SuccessSnackbarComponent v-model="success" :thing="thing"></SuccessSnackbarComponent>
            </v-card>
        </v-col>
    </v-row>

    <v-overlay v-else :value="true" opacity="1" absolute>
        <v-progress-circular
            indeterminate
            size="96"
        ></v-progress-circular>
    </v-overlay>
</template>

<script>
import { useCurrenciesStore } from "&/stores/currencies";
import main from "&/mixins/main";

import AddAccountDialogComponent from "@/accounts/AddAccountDialogComponent.vue";
import EditAccountDialogComponent from "@/accounts/EditAccountDialogComponent.vue";
import DeleteDialogComponent from "@/DeleteDialogComponent.vue";
import SuccessSnackbarComponent from "@/SuccessSnackbarComponent.vue";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [main],
    components: {
        AddAccountDialogComponent,
        EditAccountDialogComponent,
        DeleteDialogComponent,
        SuccessSnackbarComponent
    },
    data() {
        return {
            headers: [
                { text: "Icon", align: "center", value: "icon" },
                { text: "Name", align: "center", value: "name" },
                { text: "Show in income", align: "center", value: "used_in_income" },
                { text: "Show in expences", align: "center", value: "used_in_expences" },
                { text: "Show on charts", align: "center", value: "show_on_charts" },
                { text: "Count to summary", align: "center", value: "count_to_summary" },
                { text: "Actions", align: "center", value: "actions" }
            ],
            accounts: [],

            ready: false,
            tableLoading: false,
            success: false,
            thing: ""
        }
    },
    methods: {
        getData() {
            this.tableLoading = true;

            axios
                .get(`/web-api/accounts/${this.currencies.usedCurrency}`)
                .then(response => {
                    const data = response.data;

                    this.accounts = data;

                    this.tableLoading = false;
                    this.ready = true;
                })
        },
        added() {
            this.thing = `added account`;
            this.success = true;
            this.getData();
        },
        updated() {
            this.thing = `updated account`;
            this.success = true;
            this.getData();
        },
        deleted() {
            this.thing = `deleted account`;
            this.success = true;
            this.getData();
        }
    },
    mounted() {
        this.getData();
        this.currencies.$subscribe(() => this.getData());
    }
}
</script>
