<template>
    <v-card class="loading-height">
        <v-card-title class="justify-sm-space-between justify-center text-h5 text-capitalize pb-lg-0 flex-sm-row flex-column align-center">
            <div class="mb-sm-0 mb-3">Means of payment</div>

            <AddMeanDialogComponent
                @added="added"
            ></AddMeanDialogComponent>
        </v-card-title>

        <v-card-text>
            <v-data-table
                hide-default-footer
                :headers="headers"
                :items="means"
                :mobile-breakpoint="0"
                :loading="tableLoading"
                disable-filtering
                disable-sort
                disable-pagination
            >
                <template v-slot:[`item.name`]="{ item }">
                    <span style="white-space: nowrap">{{ item.name }}</span>
                </template>

                <template v-slot:[`item.income_mean`]="{ item }">
                    <v-simple-checkbox v-model="item.income_mean" disabled off-icon="mdi-close"></v-simple-checkbox>
                </template>

                <template v-slot:[`item.outcome_mean`]="{ item }">
                    <v-simple-checkbox v-model="item.outcome_mean" disabled off-icon="mdi-close"></v-simple-checkbox>
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
                            <EditMeanDialogComponent
                                :id="item.id"
                                @updated="updated"
                            ></EditMeanDialogComponent>

                            <DeleteDialogComponent
                                thing="mean of payment"
                                :url="`settings/means/${item.id}`"
                                @deleted="deleted"
                            ></DeleteDialogComponent>
                        </div>
                    </td>
                </template>
            </v-data-table>
        </v-card-text>

        <SuccessSnackbarComponent v-model="success" :thing="thing"></SuccessSnackbarComponent>
    </v-card>
</template>

<script>
import { useCurrenciesStore } from "&/stores/currencies";
import main from "&/mixins/main";

import AddMeanDialogComponent from "@/settings/AddMeanDialogComponent.vue";
import EditMeanDialogComponent from "@/settings/EditMeanDialogComponent.vue";
import DeleteDialogComponent from "@/DeleteDialogComponent.vue";
import SuccessSnackbarComponent from "@/SuccessSnackbarComponent.vue";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [main],
    components: {
        AddMeanDialogComponent,
        EditMeanDialogComponent,
        DeleteDialogComponent,
        SuccessSnackbarComponent
    },
    props: {
        startData: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            headers: [
                { text: "Name", align: "center", value: "name" },
                { text: "Show in income", align: "center", value: "income_mean" },
                { text: "Show in outcome", align: "center", value: "outcome_mean" },
                { text: "Show on charts", align: "center", value: "show_on_charts" },
                { text: "Count to summary", align: "center", value: "count_to_summary" },
                { text: "Actions", align: "center", value: "actions" }
            ],
            means: [],

            tableLoading: false,
            success: false,
            thing: ""
        }
    },
    methods: {
        getData() {
            this.tableLoading = true;

            axios
                .get(`/web-api/settings/${this.currencies.usedCurrency}/means`)
                .then(response => {
                    const data = response.data;

                    this.means = data;

                    this.tableLoading = false;
                })
        },
        added() {
            this.thing = `added mean of payment`;
            this.success = true;
            this.getData();
        },
        updated() {
            this.thing = `updated mean of payment`;
            this.success = true;
            this.getData();
        },
        deleted() {
            this.thing = `deleted mean of payment`;
            this.success = true;
            this.getData();
        }
    },
    mounted() {
        this.means = this.startData;
    }
}
</script>
