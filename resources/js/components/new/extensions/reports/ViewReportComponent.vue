<template>
    <div v-if="ready">
        <v-row>
            <v-col xl="8" cols="12" order="last" order-xl="first">
                <v-card class="sticky-panel">
                    <v-card-title class="font-weight-bold justify-center text-h5">Report content</v-card-title>

                    <v-card-text>
                        <v-data-table
                            class="table-bordered"
                            hide-default-footer
                            :headers="headers"
                            :items="mergedCells"
                            :mobile-breakpoint="0"
                            :server-items-length="items.length"
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
                                </v-row>
                            </template>

                            <template v-slot:[`header.date`]="{ header }">
                                {{ header.text }}

                                <v-menu offset-y :close-on-content-click="false">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn small icon v-bind="attrs" v-on="on">
                                            <v-icon small :color="filteredData.date.length ? 'primary' : ''">
                                                mdi-filter
                                            </v-icon>
                                        </v-btn>
                                    </template>

                                    <v-date-picker
                                        v-model="filteredData.date"
                                        min="1970-01-01"
                                        multiple
                                        color="primary"
                                        prev-icon="mdi-skip-previous"
                                        next-icon="mdi-skip-next"
                                    ></v-date-picker>
                                </v-menu>
                            </template>

                            <template v-slot:item="{item, index}">
                                <tr class="text-center">
                                    <td v-if="showColumn('date') && item.date.span" :rowspan="item.date.span" @mouseover="setRowsToHighlight(index, item.date.span)" @mouseleave="resetRowsToHighlight()"
                                        :class="isRowHighlighted(index, item.date.span) && 'table-hover-background'"
                                    >{{ item.date.value }}</td>

                                    <td v-if="showColumn('title') && item.title.span" :rowspan="item.title.span"  @mouseover="setRowsToHighlight(index, item.title.span)" @mouseleave="resetRowsToHighlight()"
                                        :class="isRowHighlighted(index, item.title.span) && 'table-hover-background'"
                                    >{{ item.title.value }}</td>

                                    <td v-if="showColumn('amount') && item.amount.span" :rowspan="item.amount.span" @mouseover="setRowsToHighlight(index, item.amount.span)" @mouseleave="resetRowsToHighlight()"
                                        :class="isRowHighlighted(index, item.amount.span) && 'table-hover-background'"
                                    >{{ item.amount.value }}</td>

                                    <td v-if="showColumn('price') && item.price.span" :rowspan="item.price.span" @mouseover="setRowsToHighlight(index, item.price.span)" @mouseleave="resetRowsToHighlight()"
                                        :class="isRowHighlighted(index, item.price.span) && 'table-hover-background'"
                                    >{{ item.price.value | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}</td>

                                    <td v-if="showColumn('value') && item.value.span" :rowspan="item.value.span" @mouseover="setRowsToHighlight(index, item.value.span)" @mouseleave="resetRowsToHighlight()"
                                        :class="isRowHighlighted(index, item.value.span) && 'table-hover-background'"
                                    >{{ item.value.value | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}</td>

                                    <td v-if="showColumn('category') && item.category.span" :rowspan="item.category.span" @mouseover="setRowsToHighlight(index, item.category.span)" @mouseleave="resetRowsToHighlight()"
                                        :class="isRowHighlighted(index, item.category.span) && 'table-hover-background'"
                                    >{{ item.category.value }}</td>

                                    <td v-if="showColumn('mean') && item.mean.span" :rowspan="item.mean.span" @mouseover="setRowsToHighlight(index, item.mean.span)" @mouseleave="resetRowsToHighlight()"
                                        :class="isRowHighlighted(index, item.mean.span) && 'table-hover-background'"
                                    >{{ item.mean.value }}</td>
                                </tr>
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col xl="4" cols="12" order-xl="last">
                <v-card class="mb-4 sticky-panel">
                    <v-card-title class="font-weight-bold justify-center text-h5">Information</v-card-title>

                    <v-card-text>

                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>

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
import customTableMerged from "&/mixins/customTableMerged";
import validation from "&/mixins/validation";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [main, customTableMerged, validation],
    data() {
        return {
            information: {},
            content: [],
            options: {},
            titleSearch: "",
            filteredData: {
                date: []
            },
            lastChange: new Date(),

            ready: false
        }
    },
    computed: {
        items() {
            if (!this.content.length || !Object.keys(this.options).length) {
                return this.content;
            }

            let items = _.cloneDeep(this.content);

            if (this.titleSearch != "") {
                const regex = new RegExp(this.titleSearch.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'));

                items = items.filter(item => regex.test(item.title));
            }

            Object.keys(this.filteredData).forEach(key => {
                if (this.filteredData[key].length) {
                    items = items.filter(item => this.filteredData[key].includes(item[key]));
                }
            })

            if (this.options.sortBy.length) {
                items.sort(this.sortUsingOptions(0));
            }

            return items;
        },
        columnsToShow() {
            if (!this.items.length) {
                return [];
            }

            return Object.keys(this.items[0]);
        },
        headers() {
            const headers = [
                { text: "Date", align: "center", value: "date" },
                { text: "Title", align: "center", value: "title" },
                { text: "Amount", align: "center", value: "amount" },
                { text: "Price", align: "center", value: "price" },
                { text: "Value", align: "center", value: "value" },
                { text: "Category", align: "center", value: "category" },
                { text: "Mean of payment", align: "center", value: "mean" }
            ];

            return headers.filter(item => this.columnsToShow.includes(item.value));
        }
    },
    methods: {
        getData() {
            this.ready = false;

            axios
                .get(`/web-api/extensions/reports/${this.$route.params.id}`)
                .then(response => {
                    const data = response.data;

                    this.information = data.information;
                    this.content = data.items;

                    this.ready = true;
                })
        },
        showColumn(column) {
            return this.columnsToShow.includes(column);
        }
    },
    mounted() {
        this.getData();
    }
}
</script>
