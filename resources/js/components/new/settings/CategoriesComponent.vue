<template>
    <v-card class="loading-height">
        <v-card-title class="font-weight-bold justify-sm-space-between justify-center text-h5 text-capitalize pb-lg-0 flex-sm-row flex-column align-center">
            <div class="mb-sm-0 mb-3">Categories</div>

            <AddCategoryDialogComponent
                @added="getData"
            ></AddCategoryDialogComponent>
        </v-card-title>

        <v-card-text>
            <v-data-table
                hide-default-footer
                :headers="headers"
                :items="categories"
                :mobile-breakpoint="0"
                :loading="tableLoading"
                disable-filtering
                disable-sort
                disable-pagination
            >
                <template v-slot:[`item.name`]="{ item }">
                    <span style="white-space: nowrap">{{ item.name }}</span>
                </template>

                <template v-slot:[`item.income_category`]="{ item }">
                    <v-simple-checkbox v-model="item.income_category" disabled off-icon="mdi-close"></v-simple-checkbox>
                </template>

                <template v-slot:[`item.outcome_category`]="{ item }">
                    <v-simple-checkbox v-model="item.outcome_category" disabled off-icon="mdi-close"></v-simple-checkbox>
                </template>

                <template v-slot:[`item.show_on_charts`]="{ item }">
                    <v-simple-checkbox v-model="item.show_on_charts" disabled off-icon="mdi-close"></v-simple-checkbox>
                </template>

                <template v-slot:[`item.count_to_summary`]="{ item }">
                    <v-simple-checkbox v-model="item.count_to_summary" disabled off-icon="mdi-close"></v-simple-checkbox>
                </template>

                <template v-slot:[`item.actions`]="{ item }">
                    <td class="d-flex flex-nowrap justify-center align-center">
                        <div>
                            <EditCategoryDialogComponent
                                :id="item.id"
                                @updated="getData"
                            ></EditCategoryDialogComponent>

                            <DeleteDialogComponent
                                thing="category"
                                :url="`settings/categories/${item.id}`"
                                @deleted="getData"
                            ></DeleteDialogComponent>
                        </div>
                    </td>
                </template>
            </v-data-table>
        </v-card-text>
    </v-card>
</template>

<script>
import { useCurrenciesStore } from "&/stores/currencies";
import main from "&/mixins/main";

import AddCategoryDialogComponent from "@/settings/AddCategoryDialogComponent.vue";
import EditCategoryDialogComponent from "@/settings/EditCategoryDialogComponent.vue";
import DeleteDialogComponent from "@/DeleteDialogComponent.vue";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [main],
    components: {
        AddCategoryDialogComponent,
        EditCategoryDialogComponent,
        DeleteDialogComponent
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
                { text: "Show in income", align: "center", value: "income_category" },
                { text: "Show in outcome", align: "center", value: "outcome_category" },
                { text: "Show on charts", align: "center", value: "show_on_charts" },
                { text: "Count to summary", align: "center", value: "count_to_summary" },
                { text: "Actions", align: "center", value: "actions" }
            ],
            categories: [],

            tableLoading: false
        }
    },
    methods: {
        getData() {
            this.tableLoading = true;

            axios
                .get(`/web-api/settings/${this.currencies.usedCurrency}/categories`)
                .then(response => {
                    const data = response.data;

                    this.categories = data;

                    this.tableLoading = false;
                })
        }
    },
    mounted() {
        this.categories = this.startData;
    }
}
</script>
