<template>
    <div>
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

                    <v-col cols="12" sm="7" lg="8" :order="$vuetify.breakpoint.xsOnly ? 'first' : 'last'" class="d-flex" :class="$vuetify.breakpoint.xsOnly ? 'justify-center' : 'justify-end'">
                        <ExchangeIODialogComponent
                            @exchanged="exchanged"
                        ></ExchangeIODialogComponent>

                        <AddIODialogComponent
                            :type="type"
                            @added="added"
                        ></AddIODialogComponent>
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

                    <v-card max-width="350">
                        <v-card-text>
                            <v-select
                                v-model="filteredData.categories"
                                :items="categories"
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
                                    >(+{{ filteredData.categories.length - 1 }} other{{ filteredData.categories.length > 2 ? "s" : "" }})</span>
                                </template>
                            </v-select>
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

                    <v-card max-width="350">
                        <v-card-text>
                            <v-select
                                v-model="filteredData.accounts"
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
                                    >(+{{ filteredData.accounts.length - 1 }} other{{ filteredData.accounts.length > 2 ? "s" : "" }})</span>
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

                    <td v-if="item.title.span" :rowspan="item.title.span"  @mouseover="setRowsToHighlight(index, item.title.span)" @mouseleave="resetRowsToHighlight()"
                        :class="isRowHighlighted(index, item.title.span) && 'table-hover-background'"
                    >{{ item.title.value }}</td>

                    <td v-if="item.amount.span" :rowspan="item.amount.span" @mouseover="setRowsToHighlight(index, item.amount.span)" @mouseleave="resetRowsToHighlight()"
                        :class="isRowHighlighted(index, item.amount.span) && 'table-hover-background'"
                    >{{ item.amount.value }}</td>

                    <td v-if="item.price.span" :rowspan="item.price.span" @mouseover="setRowsToHighlight(index, item.price.span)" @mouseleave="resetRowsToHighlight()"
                        :class="isRowHighlighted(index, item.price.span) && 'table-hover-background'"
                    >{{ item.price.value | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}</td>

                    <td v-if="item.value.span" :rowspan="item.value.span" @mouseover="setRowsToHighlight(index, item.value.span)" @mouseleave="resetRowsToHighlight()"
                        :class="isRowHighlighted(index, item.value.span) && 'table-hover-background'"
                    >{{ item.value.value | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}</td>

                    <td v-if="item.category.span" :rowspan="item.category.span" @mouseover="setRowsToHighlight(index, item.category.span)" @mouseleave="resetRowsToHighlight()"
                        :class="isRowHighlighted(index, item.category.span) && 'table-hover-background'"
                    >{{ item.category.value }}</td>

                    <td v-if="item.account.span" :rowspan="item.account.span" @mouseover="setRowsToHighlight(index, item.account.span)" @mouseleave="resetRowsToHighlight()"
                        :class="isRowHighlighted(index, item.account.span) && 'table-hover-background'"
                    >{{ item.account.value }}</td>

                    <td @mouseover="setRowsToHighlight(index, 1)" @mouseleave="resetRowsToHighlight()"
                        :class="isRowHighlighted(index, 1) && 'table-hover-background'"
                    >
                        <div class="d-flex flex-nowrap justify-center align-center">
                            <EditIODialogComponent
                                :type="type"
                                :id="item.id.value"
                                @updated="updated"
                            ></EditIODialogComponent>

                            <DeleteDialogComponent
                                :thing="type"
                                :url="`${type}/${item.id.value}`"
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

        <SuccessSnackbarComponent v-model="success" :thing="thing"></SuccessSnackbarComponent>
    </div>
</template>

<script>
import { useCurrenciesStore } from "&/stores/currencies";
import main from "&/mixins/main";
import validation from "&/mixins/validation";
import customTableMerged from "&/mixins/customTableMerged";

import AddIODialogComponent from "@/income-outcome/AddIODialogComponent.vue";
import EditIODialogComponent from "@/income-outcome/EditIODialogComponent.vue";
import DeleteDialogComponent from "@/DeleteDialogComponent.vue";
import ExchangeIODialogComponent from "@/income-outcome/ExchangeIODialogComponent.vue";
import InfiniteLoading from "vue-infinite-loading";
import SuccessSnackbarComponent from "@/SuccessSnackbarComponent.vue";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [main, customTableMerged, validation],
    components: {
        AddIODialogComponent,
        EditIODialogComponent,
        DeleteDialogComponent,
        ExchangeIODialogComponent,
        InfiniteLoading,
        SuccessSnackbarComponent
    },
    props: {
        type: {
            type: String,
            required: true,
            validator: value => ["income", "outcome"].includes(value)
        },
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
            items: [],
            ready: false,
            tableLoading: false,
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
            success: false,
            thing: ""
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
            }

            if (this.pagination.page <= this.pagination.last || this.pagination.last == null) {
                axios
                    .get(`/web-api/${this.type}/currency/${this.currencies.usedCurrency}/list`, {
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
        exchanged() {
            this.thing = `exchanged`;
            this.success = true;
            this.getData();
        },
        added() {
            this.thing = `added ${this.type}`;
            this.success = true;
            this.getData();
        },
        updated() {
            this.thing = `updated ${this.type}`;
            this.success = true;
            this.getData();
        },
        deleted() {
            this.thing = `deleted ${this.type}`;
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
    }
}
</script>
