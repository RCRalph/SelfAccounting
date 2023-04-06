<template>
    <div>
        <div v-if="ready">
            <v-row class="pagination-fixed-margin">
                <v-col xl="9" cols="12" order="last" order-xl="first">
                    <v-card class="sticky-panel">
                        <v-card-title class="justify-center text-h5">{{ information.title }}</v-card-title>

                        <v-card-text>
                            <v-data-table
                                class="table-bordered"
                                hide-default-footer
                                :headers="headers"
                                :items="tableData.data"
                                :mobile-breakpoint="0"
                                :server-items-length="tableData.data.length"
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
                                                v-if="canEdit"
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
                                        <td
                                            v-if="showColumn('date') && item.date.span"
                                            :rowspan="item.date.span"
                                            :class="tableData.isRowHighlighted(index, item.date.span) && 'table-hover-background'"
                                            @mouseover="tableData.setHoveredRows(index, item.date.span)"
                                            @mouseleave="tableData.resetHoveredRows()"
                                        >{{ item.date.value }}</td>

                                        <td
                                            v-if="showColumn('title') && item.title.span"
                                            :rowspan="item.title.span"
                                            :class="tableData.isRowHighlighted(index, item.title.span) && 'table-hover-background'"
                                            @mouseover="tableData.setHoveredRows(index, item.title.span)"
                                            @mouseleave="tableData.resetHoveredRows()"
                                        >{{ item.title.value }}</td>

                                        <td
                                            v-if="showColumn('amount') && item.amount.span"
                                            :rowspan="item.amount.span"
                                            :class="tableData.isRowHighlighted(index, item.amount.span) && 'table-hover-background'"
                                            @mouseover="tableData.setHoveredRows(index, item.amount.span)"
                                            @mouseleave="tableData.resetHoveredRows()"
                                        >{{ item.amount.value }}</td>

                                        <td
                                            v-if="showColumn('price') && item.price.span"
                                            :rowspan="item.price.span"
                                            :class="tableData.isRowHighlighted(index, item.price.span) && 'table-hover-background'"
                                            @mouseover="tableData.setHoveredRows(index, item.price.span)"
                                            @mouseleave="tableData.resetHoveredRows()"
                                        >{{ item.price.value | addSpaces }}&nbsp;{{ currencies.findCurrency(item.currency_id.value).ISO }}</td>

                                        <td
                                            v-if="showColumn('value') && item.value.span"
                                            :rowspan="item.value.span"
                                            :class="tableData.isRowHighlighted(index, item.value.span) && 'table-hover-background'"
                                            @mouseover="tableData.setHoveredRows(index, item.value.span)"
                                            @mouseleave="tableData.resetHoveredRows()"
                                        >{{ item.value.value | addSpaces }}&nbsp;{{ currencies.findCurrency(item.currency_id.value).ISO }}</td>

                                        <td
                                            v-if="showColumn('category') && item.category.span"
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
                                            v-if="showColumn('account') && item.account.span"
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
                                            <v-btn outlined class="mx-2 my-1" @click="exportToCSV" width="172">Export to .csv</v-btn>

                                            <v-btn outlined class="mx-2 my-1" @click="exportToXLSX" width="172" color="success">Export to .xlsx</v-btn>
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </div>
                </v-col>
            </v-row>

            <div class="pagination-fixed">
                <v-card class="pa-1">
                    <v-pagination
                        v-model="currentReport"
                        :length="reports.length"
                    ></v-pagination>
                </v-card>
            </div>
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
import TableDataMerger from "&/classes/TableDataMerger.js";
import main from "&/mixins/main";
import validation from "&/mixins/validation";

import EditReportDialogComponent from "@/extensions/reports/EditReportDialogComponent.vue";
import SuccessSnackbarComponent from "@/SuccessSnackbarComponent.vue";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [main, validation],
    components: {
        EditReportDialogComponent,
        SuccessSnackbarComponent
    },
    data() {
        return {
            information: {},
            content: [],
            tableData: new TableDataMerger(["date"], ["id", "value"]),
            options: {},
            titleSearch: "",
            filteredData: {
                date: []
            },
            lastChange: new Date(),
            reports: [],
            currentReport: null,
            canEdit: false,

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
            if (!this.tableData.data.length) {
                return [];
            }

            return Object.keys(this.tableData.data[0]);
        },
        headers() {
            const headers = [
                { text: "Date", align: "center", value: "date" },
                { text: "Title", align: "center", value: "title" },
                { text: "Amount", align: "center", value: "amount" },
                { text: "Price", align: "center", value: "price" },
                { text: "Value", align: "center", value: "value" },
                { text: "Category", align: "center", value: "category" },
                { text: "Account", align: "center", value: "account" }
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
                    this.reports = data.reports;
                    this.currentReport = this.reports.indexOf(Number(this.$route.params.id)) + 1;
                    this.canEdit = data.canEdit;

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

            this.tableData.tabulatedData.forEach(item => {
                file.push(this.headers.map(item1 => {
                    if (["price", "value"].includes(item1.value)) {
                        return item[item1.value] + " " + this.currencies.findCurrency(item.currency_id).ISO;
                    } else if (["category", "account"].includes(item1.value)) {
                        return `"${item[item1.value].name}"`;
                    } else {
                        return `"${item[item1.value]}"`
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
            const workbook = XLSX.utils.book_new(), data = [this.headers.map(item => item.text)];

            this.tableData.tabulatedData.forEach(item => {
                data.push(this.headers.map(item1 => {
                    if (["category", "account"].includes(item1.value)) {
                        return item[item1.value].name;
                    } else if (item1.value == "date") {
                        let result = new Date(item[item1.value]);
                        return new Date(result.getUTCFullYear(), result.getUTCMonth(), result.getUTCDate());
                    }

                    return item[item1.value];
                }));
            });

            const sheet = XLSX.utils.aoa_to_sheet(data, {cellDates: true});

            if (data[0].includes("Date")) {
                const range = XLSX.utils.decode_range(sheet["!ref"]);
                const columnIndex = data[0].findIndex(item => item == "Date");

                for (let i = range.s.r + 1; i <= range.e.r; i++) {
                    sheet[XLSX.utils.encode_cell({ r: i, c: columnIndex })].t = "d";
                }
            }

            ["Price", "Value"].forEach(column => {
                if (data[0].includes(column)) {
                    const range = XLSX.utils.decode_range(sheet["!ref"]);
                    const columnIndex = data[0].findIndex(item => item == column);

                    for (let i = range.s.r + 1; i <= range.e.r; i++) {
                        sheet[XLSX.utils.encode_cell({ r: i, c: columnIndex })].z = `0.00 ${this.currencies.findCurrency(this.tableData.tabulatedData[i - 1].currency_id).ISO}`;
                    }
                }
            });

            XLSX.utils.book_append_sheet(workbook, sheet, this.information.title);
            XLSX.writeFile(workbook, `${this.information.title}.xlsx`);
        },
        updated() {
            this.thing = `updated report`;
            this.success = true;
            this.getData();
        }
    },
    watch: {
        currentReport() {
            if (this.currentReport && this.$route.params.id != this.reports[this.currentReport - 1]) {
                this.$router.push(`/extensions/reports/${this.reports[this.currentReport - 1]}`);
                this.options = {};
                this.titleSearch = "";
                this.filteredData = { date: [] };
                this.getData();
            }
        },
        items() {
            this.tableData.resetData();
            this.tableData.appendData(this.items);
        }
    },
    mounted() {
        this.getData();
    }
}
</script>
