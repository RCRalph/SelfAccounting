<template>
    <div>
        <div v-if="ready">
            <v-row>
                <v-col xl="9" cols="12" order="last" order-xl="first">
                    <v-card class="sticky-panel">
                        <v-card-title class="font-weight-bold justify-center text-h5">{{ information.title }}</v-card-title>

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

                                        <v-col cols="12" sm="7" lg="8" :order="$vuetify.breakpoint.xsOnly ? 'first' : 'last'" class="d-flex" :class="$vuetify.breakpoint.xsOnly ? 'justify-center' : 'justify-end'">
                                            <EditReportDialogComponent
                                                :id="Number($route.params.id)"
                                                @updated="updated"
                                            ></EditReportDialogComponent>
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
                                        >{{ item.price.value | addSpaces }}&nbsp;{{ currencies.findCurrency(item.currency_id.value).ISO }}</td>

                                        <td v-if="showColumn('value') && item.value.span" :rowspan="item.value.span" @mouseover="setRowsToHighlight(index, item.value.span)" @mouseleave="resetRowsToHighlight()"
                                            :class="isRowHighlighted(index, item.value.span) && 'table-hover-background'"
                                        >{{ item.value.value | addSpaces }}&nbsp;{{ currencies.findCurrency(item.currency_id.value).ISO }}</td>

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

                <v-col xl="3" cols="12" order-xl="last">
                    <div :class="$vuetify.breakpoint.xl && 'sticky-panel'">
                        <v-row :no-gutters="$vuetify.breakpoint.xl">
                            <v-col xl="12" md="4" cols="12" class="mb-xl-4">
                                <v-card style="height: 100%;">
                                    <v-card-title class="font-weight-bold justify-center text-h5">Owner</v-card-title>

                                    <v-card-text class="d-flex align-center justify-center" style="height: calc(100% - 64px)">
                                        <div class="d-flex align-center justify-center">
                                            <v-avatar size="64">
                                                <v-img
                                                    :src="information.owner.profile_picture_link">
                                                </v-img>
                                            </v-avatar>

                                            <h2 class="ml-4">{{ information.owner.username }}</h2>
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </v-col>

                            <v-col xl="12" md="4" cols="12" class="mb-xl-4">
                                <v-card style="height: 100%;">
                                    <v-card-title class="font-weight-bold justify-center text-h5">Sum</v-card-title>

                                    <v-card-text v-if="!information.sum" class="d-flex align-center justify-center" style="height: calc(100% - 64px)">
                                        <h3 class="font-weight-regular">Sum not calculated</h3>
                                    </v-card-text>

                                    <v-card-text
                                        v-else-if="Object.keys(information.sum).length == 1"
                                        class="text-h4 text-center font-weight-regular mb-6"
                                        :class="$vuetify.theme.dark ? 'white--text' : 'black--text'"
                                    >
                                        {{ information.sum[Object.keys(information.sum)[0]] | addSpaces }}&nbsp;{{ currencies.findCurrency(Object.keys(information.sum)[0]).ISO }}
                                    </v-card-text>

                                    <v-card-text v-else>
                                        <v-simple-table class="mx-3">
                                            <template v-slot:default>
                                                <tbody>
                                                    <tr v-for="(item, i) in information.sum" :key="i">
                                                        <td class="text-h5 text-center font-weight-regular mb-6 py-2">
                                                            {{ item | addSpaces }}&nbsp;{{ currencies.findCurrency(i).ISO }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </template>
                                        </v-simple-table>
                                    </v-card-text>
                                </v-card>
                            </v-col>

                            <v-col xl="12" md="4" cols="12" class="mb-xl-4">
                                <v-card style="height: 100%;">
                                    <v-card-title class="font-weight-bold justify-center text-h5">Export</v-card-title>

                                    <v-card-text class="d-flex align-center justify-center" style="height: calc(100% - 64px)">
                                        <div class="d-flex justify-space-around flex-wrap">
                                            <v-btn outlined class="mx-2 my-1" @click="exportToCSV">Export to .csv</v-btn>

                                            <v-btn outlined class="mx-2 my-1" @click="exportToXLSX" color="success">Export to .xlsx</v-btn>
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </div>
                </v-col>
            </v-row>
        </div>

        <v-overlay v-else :value="true" opacity="1" absolute>
            <v-progress-circular
                indeterminate
                size="96"
            ></v-progress-circular>
        </v-overlay>

        <SuccessSnackbarComponent v-model="success" :thing="thing"></SuccessSnackbarComponent>
    </div>
</template>

<script>
import { useCurrenciesStore } from "&/stores/currencies";
import main from "&/mixins/main";
import customTableMerged from "&/mixins/customTableMerged";
import validation from "&/mixins/validation";

import EditReportDialogComponent from "@/extensions/reports/EditReportDialogComponent.vue";
import SuccessSnackbarComponent from "@/SuccessSnackbarComponent.vue";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [main, customTableMerged, validation],
    components: {
        EditReportDialogComponent,
        SuccessSnackbarComponent
    },
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

            ready: false,
            success: false,
            thing: ""
        }
    },
    computed: {
        items() {
            if (!this.content.length || !Object.keys(this.options).length) {
                return this.content;
            }

            let items = _.cloneDeep(this.content);

            if (this.titleSearch != "" && Object.keys(this.content[0]).includes("title")) {
                const regex = new RegExp(this.titleSearch.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'));

                items = items.filter(item => regex.test(item.title));
            }

            Object.keys(this.filteredData).forEach(key => {
                if (this.filteredData[key].length && Object.keys(this.content[0].includes(key))) {
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
        },
        exportToCSV() {
            let file = [
                this.headers.map(item => item.text).join(",")
            ];

            this.items.forEach(item => {
                file.push(this.headers.map(item1 => {
                    if (["Price", "Value"].includes(item1.text)) {
                        return item[item1.value] + " " + this.currencies.findCurrency(item.currency_id).ISO;
                    }
                    else {
                        return '"' + item[item1.value] + '"';
                    }
                }).join(","));
            });

            let result = file.join("%0A");

            const download = document.createElement("a");
            download.style.display = "none;"
            download.href = `data:text/csv;charset:utf-8,${result}`;
            download.download = `${this.information.title}.csv`

            document.body.appendChild(download);
            download.click();
            document.body.removeChild(download);
        },
        exportToXLSX() {
            const workbook = XLSX.utils.book_new();

            const data = [this.headers.map(item => item.text)];

            this.items.forEach(item => {
                data.push(this.headers.map(item1 => item[item1.value]));
            });

            const sheet = XLSX.utils.aoa_to_sheet(data);

            ["Price", "Value"].forEach(column => {
                if (data[0].includes(column)) {
                const range = XLSX.utils.decode_range(sheet["!ref"]);
                const columnIndex = data[0].findIndex(item => item == column);

                for (let i = range.s.r + 1; i <= range.e.r; i++) {
                    sheet[XLSX.utils.encode_cell({ r: i, c: columnIndex })].z = '0.00\" ' + this.currencies.findCurrency(this.items[i - 1].currency_id).ISO + '"';
                }
            }})

            XLSX.utils.book_append_sheet(workbook, sheet, this.information.title);
            XLSX.writeFile(workbook, `${this.information.title}.xlsx`);
        },
        updated() {
            this.thing = `updated report`;
            this.success = true;
            this.getData();
        },
    },
    mounted() {
        this.getData();
    }
}
</script>
