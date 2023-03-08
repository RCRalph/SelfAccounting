<template>
    <div>
        <v-data-table
            class="table-bordered"
            hide-default-footer
            :headers="headers"
            :items="tableData.data"
            :mobile-breakpoint="0"
            :loading="tableLoading"
            :server-items-length="pagination.perPage"
            :options.sync="options"
            multi-sort
        >
            <template v-slot:top>
                <v-row class="align-center mb-0">
                    <v-col cols="12" sm="5" lg="4">
                        <v-text-field
                            v-model="titleSearch"
                            append-icon="mdi-magnify"
                            label="Search"
                            dense
                            single-line
                            counter="64"
                            :rules="[validation.search(64)]"
                        ></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="7" lg="8" :order="$vuetify.breakpoint.xsOnly ? 'first' : 'last'" class="multi-button-table-top">
                        <AddIncomeExpencesDialogComponent
                            type="income"
                            @added="added"
                        ></AddIncomeExpencesDialogComponent>

                        <AddIncomeExpencesDialogComponent
                            type="expences"
                            @added="added"
                        ></AddIncomeExpencesDialogComponent>
                    </v-col>
                </v-row>
            </template>

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

            <template v-slot:[`header.category`]="{ header }">
                {{ header.text }}

                <v-menu offset-y :close-on-content-click="false">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn small icon v-bind="attrs" v-on="on">
                            <v-icon small :color="filteredData.categories.length ? 'primary' : ''">
                                mdi-filter
                            </v-icon>
                        </v-btn>
                    </template>

                    <v-card class="filtered-list">
                        <v-card-text>
                            <v-checkbox
                                v-model="filteredData.categories"
                                v-for="item, i in categories"
                                :value="item.id"
                                :key="item.id"
                                :class="i == 0 && 'mt-0 pt-0'"
                                hide-details
                            >
                                <template v-slot:label>
                                    <div>
                                        <v-icon v-if="item.icon">{{ item.icon }}</v-icon>
                                        {{ item.name }}
                                    </div>
                                </template>
                            </v-checkbox>
                        </v-card-text>
                    </v-card>
                </v-menu>
            </template>

            <template v-slot:[`header.account`]="{ header }">
                {{ header.text }}

                <v-menu offset-y :close-on-content-click="false">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn small icon v-bind="attrs" v-on="on">
                            <v-icon small :color="filteredData.accounts.length ? 'primary' : ''">
                                mdi-filter
                            </v-icon>
                        </v-btn>
                    </template>

                    <v-card class="filtered-list">
                        <v-card-text>
                            <v-checkbox
                                v-model="filteredData.accounts"
                                v-for="item, i in accounts"
                                :value="item.id"
                                :key="item.id"
                                :class="i == 0 && 'mt-0 pt-0'"
                                hide-details
                            >
                                <template v-slot:label>
                                    <div>
                                        <v-icon v-if="item.icon">{{ item.icon }}</v-icon>
                                        {{ item.name }}
                                    </div>
                                </template>
                            </v-checkbox>
                        </v-card-text>
                    </v-card>
                </v-menu>
            </template>

            <template v-slot:item="{item, index}">
                <tr class="text-center">
                    <td
                        v-if="item.date.span"
                        :rowspan="item.date.span"
                        :class="tableData.isRowHighlighted(index, item.date.span) && 'table-hover-background'"
                        @mouseover="tableData.setHoveredRows(index, item.date.span)"
                        @mouseleave="tableData.resetHoveredRows()"
                    >{{ item.date.value }}</td>

                    <td
                        v-if="item.title.span"
                        :rowspan="item.title.span"
                        :class="tableData.isRowHighlighted(index, item.title.span) && 'table-hover-background'"
                        @mouseover="tableData.setHoveredRows(index, item.title.span)"
                        @mouseleave="tableData.resetHoveredRows()"
                    >{{ item.title.value }}</td>

                    <td
                        v-if="item.amount.span"
                        :rowspan="item.amount.span"
                        :class="tableData.isRowHighlighted(index, item.amount.span) && 'table-hover-background'"
                        @mouseover="tableData.setHoveredRows(index, item.amount.span)"
                        @mouseleave="tableData.resetHoveredRows()"
                    >{{ item.amount.value }}</td>

                    <td
                        v-if="item.price.span"
                        :rowspan="item.price.span"
                        :class="tableData.isRowHighlighted(index, item.price.span) && 'table-hover-background'"
                        @mouseover="tableData.setHoveredRows(index, item.price.span)"
                        @mouseleave="tableData.resetHoveredRows()"
                    >{{ item.price.value | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}</td>

                    <td
                        v-if="item.value.span"
                        :rowspan="item.value.span"
                        :class="tableData.isRowHighlighted(index, item.value.span) && 'table-hover-background'"
                        @mouseover="tableData.setHoveredRows(index, item.value.span)"
                        @mouseleave="tableData.resetHoveredRows()"
                    >{{ item.value.value | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}</td>

                    <td
                        v-if="item.category.span"
                        :rowspan="item.category.span"
                        style="max-width: 200px"
                        :class="tableData.isRowHighlighted(index, item.category.span) && 'table-hover-background'"
                        @mouseover="tableData.setHoveredRows(index, item.category.span)"
                        @mouseleave="tableData.resetHoveredRows()"
                    >
                        <div class="d-flex justify-start align-center">
                            <div class="mr-2">
                                <v-icon style="min-width: 24px" v-if="item.category.value.icon">{{ item.category.value.icon }}</v-icon>
                            </div>

                            <div class="d-flex justify-center align-center" style="width: 100%">
                                {{ item.category.value.name }}
                            </div>
                        </div>
                    </td>

                    <td
                        v-if="item.account.span"
                        :rowspan="item.account.span"
                        style="max-width: 200px"
                        :class="tableData.isRowHighlighted(index, item.account.span) && 'table-hover-background'"
                        @mouseover="tableData.setHoveredRows(index, item.account.span)"
                        @mouseleave="tableData.resetHoveredRows()"
                    >
                        <div class="d-flex justify-start align-center">
                            <div class="mr-2">
                                <v-icon style="min-width: 24px" v-if="item.account.value.icon">{{ item.account.value.icon }}</v-icon>
                            </div>

                            <div class="d-flex justify-center align-center" style="width: 100%">
                                {{ item.account.value.name }}
                            </div>
                        </div>
                    </td>

                    <td
                        :class="tableData.isRowHighlighted(index, 1) && 'table-hover-background'"
                        @mouseover="tableData.setHoveredRows(index, 1)"
                        @mouseleave="tableData.resetHoveredRows()"
                    >
                        <div class="d-flex flex-nowrap justify-center align-center">
                            <EditIncomeExpencesDialogComponent
                                :type="item.value.value < 0 ? 'expences' : 'income'"
                                :id="item.id.value"
                                @updated="getData"
                            ></EditIncomeExpencesDialogComponent>

                            <DeleteDialogComponent
                                :thing="item.value.value < 0 ? 'expence' : 'income'"
                                :url="`${item.value.value < 0 ? 'expences' : 'income'}/${item.id.value}`"
                                @deleted="getData"
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

        <SuccessSnackbarComponent v-model="success" :thing="thing"></SuccessSnackbarComponent>
    </div>
</template>

<script>
import { useCurrenciesStore } from "&/stores/currencies";
import TableDataMerger from "&/classes/TableDataMerger.js";
import main from "&/mixins/main";
import validation from "&/mixins/validation";

import AddIncomeExpencesDialogComponent from "@/income-expences/AddIncomeExpencesDialogComponent.vue";
import EditIncomeExpencesDialogComponent from "@/income-expences/EditIncomeExpencesDialogComponent.vue";
import DeleteDialogComponent from "@/DeleteDialogComponent.vue";
import InfiniteLoading from "vue-infinite-loading";
import SuccessSnackbarComponent from "@/SuccessSnackbarComponent.vue";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [main, validation],
    components: {
        AddIncomeExpencesDialogComponent,
        EditIncomeExpencesDialogComponent,
        DeleteDialogComponent,
        InfiniteLoading,
        SuccessSnackbarComponent,
    },
    props: {
        categories: {
            type: Array,
            required: true
        },
        accounts: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            headers: [
                { text: "Date", align: "center", value: "date" },
                { text: "Title", align: "center", value: "title" },
                { text: "Amount", align: "center", value: "amount" },
                { text: "Price", align: "center", value: "price" },
                { text: "Value", align: "center", value: "value" },
                { text: "Category", align: "center", value: "category", sortable: false },
                { text: "Account", align: "center", value: "account", sortable: false },
                { text: "Actions", align: "center", value: "", sortable: false }
            ],
            tableData: new TableDataMerger(["date"], ["id", "value"]),
            pagination: {
                page: 1,
                last: null,
                perPage: null
            },
            options: {},
            titleSearch: "",
            filteredData: {
                dates: [],
                categories: [],
                accounts: []
            },
            lastChange: new Date(),

            ready: false,
            tableLoading: false,
            success: false,
            thing: "",
        }
    },
    computed: {
        dataQuery() {
            let query = {};

            if (Object.keys(this.options).length) {
                if (this.titleSearch != "") {
                    query.title = this.titleSearch;
                }

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
                this.tableData.resetData();
            }

            if (this.pagination.page <= this.pagination.last || this.pagination.last == null) {
                axios
                    .get(`/web-api/dashboard/${this.currencies.usedCurrency}/recent-transactions`, {
                        params: { page: this.pagination.page, ...this.dataQuery }
                    })
                    .then(response => {
                        const data = response.data;

                        this.tableData.appendData(data.items.data);

                        this.pagination.last = data.items.last_page;
                        this.pagination.perPage = data.items.per_page;

                        if (!$state) {
                            this.tableLoading = false;
                        } else {
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
            this.thing = `added ${this.type}`;
            this.success = true;
            this.getData();
        },
        updated() {
            this.thing = `updated ${this.type == 'expences' ? 'expence' : 'income'}`;
            this.success = true;
            this.getData();
        },
        deleted() {
            this.thing = `deleted ${this.type == 'expences' ? 'expence' : 'income'}`;
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
        titleSearch() {
            this.updateWithOffset();
        },
        filteredData: {
            handler: "updateWithOffset",
            deep: true
        }
    },
    mounted() {
        this.getData();
    }
}
</script>
