<template>
    <v-card class="sticky-panel loading-height">
        <v-card-title class="justify-sm-space-between justify-center text-h5 text-capitalize pb-lg-0 flex-sm-row flex-column align-center mb-4">
            <div class="mb-sm-0 mb-3">Transfers</div>

            <AddTransferDialogComponent
                @added="added"
            ></AddTransferDialogComponent>
        </v-card-title>

        <v-card-text>
            <v-data-table
                class="table-bordered"
                hide-default-footer
                :headers="headers"
                :items="mergedCells"
                :mobile-breakpoint="0"
                :loading="tableLoading"
                :server-items-length="pagination.perPage"
                :options.sync="options"
                multi-sort
            >
                <template v-slot:[`header.date`]="{ header }">
                    {{ header.text }}

                    <v-menu offset-y :close-on-content-click="false">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn small icon v-bind="attrs" v-on="on">
                                <v-icon small :color="filteredData.dates.length ? 'primary' : ''">
                                    mdi-filter
                                </v-icon>
                            </v-btn>
                        </template>

                        <v-date-picker
                            v-model="filteredData.dates"
                            min="1970-01-01"
                            multiple
                            color="primary"
                            prev-icon="mdi-skip-previous"
                            next-icon="mdi-skip-next"
                        ></v-date-picker>
                    </v-menu>
                </template>

                <template v-slot:[`header.source_account_name`]="{ header }">
                    {{ header.text }}
                    <v-menu offset-y :close-on-content-click="false">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn small icon v-bind="attrs" v-on="on">
                                <v-icon small :color="filteredData.source_accounts.length ? 'primary' : ''">
                                    mdi-filter
                                </v-icon>
                            </v-btn>
                        </template>

                        <v-card max-width="350">
                            <v-card-text>
                                <v-select
                                    v-model="filteredData.source_accounts"
                                    :items="accounts"
                                    item-text="name"
                                    item-value="id"
                                    multiple
                                    hide-details
                                    filled
                                >
                                    <template v-slot:selection="{ item, index }">
                                        <v-chip v-if="index === 0" small>
                                            <span>{{ item.name }}</span>
                                        </v-chip>

                                        <span
                                            v-if="index === 1"
                                            class="grey--text text-caption"
                                        >(+{{ filteredData.source_accounts.length - 1 }} other{{ filteredData.source_accounts.length > 2 ? "s" : "" }})</span>
                                    </template>
                                </v-select>
                            </v-card-text>
                        </v-card>
                    </v-menu>
                </template>

                <template v-slot:[`header.target_account_name`]="{ header }">
                    {{ header.text }}
                    <v-menu offset-y :close-on-content-click="false">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn small icon v-bind="attrs" v-on="on">
                                <v-icon small :color="filteredData.target_accounts.length ? 'primary' : ''">
                                    mdi-filter
                                </v-icon>
                            </v-btn>
                        </template>

                        <v-card max-width="350">
                            <v-card-text>
                                <v-select
                                    v-model="filteredData.target_accounts"
                                    :items="accounts"
                                    item-text="name"
                                    item-value="id"
                                    multiple
                                    hide-details
                                    filled
                                >
                                    <template v-slot:selection="{ item, index }">
                                        <v-chip v-if="index === 0" small>
                                            <span>{{ item.name }}</span>
                                        </v-chip>

                                        <span
                                            v-if="index === 1"
                                            class="grey--text text-caption"
                                        >(+{{ filteredData.target_accounts.length - 1 }} other{{ filteredData.target_accounts.length > 2 ? "s" : "" }})</span>
                                    </template>
                                </v-select>
                            </v-card-text>
                        </v-card>
                    </v-menu>
                </template>

                <template v-slot:item="{item, index}">
                    <tr class="text-center">
                        <td v-if="item.date.span" :rowspan="item.date.span" @mouseover="setRowsToHighlight(index, item.date.span)" @mouseleave="resetRowsToHighlight()"
                            :class="isRowHighlighted(index, item.date.span) && 'table-hover-background'"
                        >{{ item.date.value }}</td>

                        <td v-if="item.source_value.span" :rowspan="item.source_value.span" @mouseover="setRowsToHighlight(index, item.source_value.span)" @mouseleave="resetRowsToHighlight()"
                            :class="isRowHighlighted(index, item.source_value.span) && 'table-hover-background'"
                        >{{ item.source_value.value | addSpaces }}&nbsp;{{ item.source_account_currency.value }}</td>

                        <td v-if="item.source_account_name.span" :rowspan="item.source_account_name.span"  @mouseover="setRowsToHighlight(index, item.source_account_name.span)" @mouseleave="resetRowsToHighlight()"
                            :class="isRowHighlighted(index, item.source_account_name.span) && 'table-hover-background'"
                        >{{ item.source_account_name.value }}</td>

                        <td v-if="item.target_value.span" :rowspan="item.target_value.span" @mouseover="setRowsToHighlight(index, item.target_value.span)" @mouseleave="resetRowsToHighlight()"
                            :class="isRowHighlighted(index, item.target_value.span) && 'table-hover-background'"
                        >{{ item.target_value.value | addSpaces }}&nbsp;{{ item.target_account_currency.value }}</td>

                        <td v-if="item.target_account_name.span" :rowspan="item.target_account_name.span"  @mouseover="setRowsToHighlight(index, item.target_account_name.span)" @mouseleave="resetRowsToHighlight()"
                            :class="isRowHighlighted(index, item.target_account_name.span) && 'table-hover-background'"
                        >{{ item.target_account_name.value }}</td>

                        <td @mouseover="setRowsToHighlight(index, 1)" @mouseleave="resetRowsToHighlight()"
                            :class="isRowHighlighted(index, 1) && 'table-hover-background'"
                        >
                            <div class="d-flex flex-nowrap justify-center align-center">
                                <EditTransferDialogComponent
                                    :id="item.id.value"
                                    @updated="updated"
                                ></EditTransferDialogComponent>

                                <DeleteDialogComponent
                                    thing="transfer"
                                    :url="`transfers/${item.id.value}`"
                                    @deleted="deleted"
                                ></DeleteDialogComponent>
                            </div>
                        </td>
                    </tr>
                </template>
            </v-data-table>

            <InfiniteLoading
                v-if="!tableLoading"
                :force-use-infinite-wrapper="true"
                @infinite="getData"
                :key="currencies.usedCurrency && tableLoading"
            >
                <div slot="no-more"></div>
            </InfiniteLoading>
        </v-card-text>

        <SuccessSnackbarComponent v-model="success" :thing="thing"></SuccessSnackbarComponent>
    </v-card>
</template>

<script>
import { useCurrenciesStore } from "&/stores/currencies";
import main from "&/mixins/main";
import validation from "&/mixins/validation";
import customTableMerged from "&/mixins/customTableMerged";

import AddTransferDialogComponent from "@/transfers/AddTransferDialogComponent.vue";
import EditTransferDialogComponent from "@/transfers/EditTransferDialogComponent.vue";
import DeleteDialogComponent from "@/DeleteDialogComponent.vue";
import InfiniteLoading from "vue-infinite-loading";
import SuccessSnackbarComponent from "@/SuccessSnackbarComponent.vue";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [main, customTableMerged, validation],
    components: {
        AddTransferDialogComponent,
        EditTransferDialogComponent,
        DeleteDialogComponent,
        InfiniteLoading,
        SuccessSnackbarComponent
    },
    props: {
        accounts: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            headers: [
                { text: "Date", align: "center", value: "date" },
                { text: "Source value", align: "center", value: "source_value" },
                { text: "Source account", align: "center", value: "source_account_name", sortable: false },
                { text: "Target value", align: "center", value: "target_value" },
                { text: "Target account", align: "center", value: "target_account_name", sortable: false },
                { text: "Actions", align: "center", value: "", sortable: false }
            ],
            items: [],
            ready: false,
            tableLoading: false,
            pagination: {
                page: 1,
                last: null,
                perPage: null
            },
            options: {},
            filteredData: {
                dates: [],
                source_accounts: [],
                target_accounts: []
            },
            success: false,
            thing: ""
        }
    },
    computed: {
        dataQuery() {
            let query = {};

            if (Object.keys(this.options).length) {
                if (this.options.sortBy.length) {
                    query.orderFields = [];
                    query.orderDirections = [];

                    this.options.sortBy.forEach((item, index) => {
                        query.orderFields.push(item)
                        query.orderDirections.push(this.options.sortDesc[index] ? "desc" : "asc");
                    });
                }

                Object.keys(this.filteredData).forEach(key => {
                    if (this.filteredData[key]) {
                        query[key] = this.filteredData[key];
                    }
                })
            }

            return query;
        }
    },
    methods: {
        getData($state) {
            if (!$state) {
                this.tableLoading = true;
                this.pagination.page = 1;
                this.pagination.last = null;
            }

            if (this.pagination.page <= this.pagination.last || this.pagination.last == null) {
                axios
                    .get(`/web-api/transfers/currency/${this.currencies.usedCurrency}/list`, {
                        params: { page: this.pagination.page, ...this.dataQuery }
                    })
                    .then(response => {
                        const data = response.data;

                        this.items = this.pagination.page == 1 ?
                            data.items.data :
                            this.items.concat(data.items.data);

                        this.pagination.last = data.items.last_page;
                        this.pagination.perPage = data.items.per_page;

                        if (!$state) {
                            this.tableLoading = false;
                        }

                        if ($state) {
                            if (data.items.current_page == data.items.last_page) {
                                $state.complete();
                            }
                            $state.loaded();
                        }

                        this.pagination.page++;
                    });
            }
            else if ($state) {
                $state.complete();
                $state.loaded();
            }
        },
        updateWithOffset() {
            const timeOffset = 250;
            this.lastChange = new Date();

            setTimeout(() => {
                if (new Date() - this.lastChange >= timeOffset) {
                    this.getData();
                }
            }, timeOffset + 1);
        },
        added() {
            this.thing = `added transfer`;
            this.success = true;
            this.getData();
        },
        updated() {
            this.thing = `updated transfer`;
            this.success = true;
            this.getData();
        },
        deleted() {
            this.thing = `deleted transfer`;
            this.success = true;
            this.getData();
        }
    },
    watch: {
        options(newOptions, oldOptions) {
            if (Object.keys(oldOptions).length) {
                this.getData();
            }
        },
        filteredData: {
            handler: "updateWithOffset",
            deep: true
        }
    }
}
</script>
