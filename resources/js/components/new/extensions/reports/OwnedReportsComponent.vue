<template>
    <v-card v-if="ready" class="loading-height">
        <v-card-title class="justify-center text-capitalize pb-lg-0 text-h5">Owned reports</v-card-title>

        <v-card-text>
            <v-data-table
                :headers="headers"
                :items="reports"
                :mobile-breakpoint="0"
                :loading="tableLoading"
                :server-items-length="total"
                :options.sync="options"
                :footer-props="{
                    'items-per-page-options': [10, 15, 20, 25, 30]
                }"
            >
                <template v-slot:top>
                    <v-row class="align-center mb-0">
                        <v-col cols="12" sm="6" lg="5">
                            <v-form v-model="canRefresh">
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    dense
                                    single-line
                                    counter="64"
                                    :rules="[validation.search(64)]"
                                ></v-text-field>
                            </v-form>
                        </v-col>

                        <v-col cols="12" sm="6" lg="7" :order="$vuetify.breakpoint.xsOnly ? 'first' : 'last'" class="d-flex" :class="$vuetify.breakpoint.xsOnly ? 'justify-center' : 'justify-end'">
                            <CreateReportDialogComponent></CreateReportDialogComponent>
                        </v-col>
                    </v-row>
                </template>


                <template v-slot:[`item.title`]="{ item }">
                    <span style="white-space: nowrap">{{ item.title }}</span>
                </template>

                <template v-slot:[`item.actions`]="{ item }">
                    <td>
                        <div class="d-flex flex-nowrap justify-center align-center">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <router-link :to="`/extensions/reports/${item.id}`">
                                        <v-icon class="mx-1 cursor-pointer" v-on="on" v-bind="attrs">mdi-open-in-app</v-icon>
                                    </router-link>
                                </template>

                                <span>View report</span>
                            </v-tooltip>

                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-icon class="mx-1 cursor-pointer" v-on="on" v-bind="attrs" @click="share(item.id)">mdi-share</v-icon>
                                </template>

                                <span>Share report</span>
                            </v-tooltip>

                            <EditReportDialogComponent
                                :id="item.id"
                                icon
                                @updated="updated"
                            ></EditReportDialogComponent>

                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-icon class="mx-1 cursor-pointer" v-on="on" v-bind="attrs" @click="duplicate(item.id)">mdi-content-duplicate</v-icon>
                                </template>

                                <span>{{ duplicateLoading == item.id ? "Duplicating..." : "Duplicate report" }}</span>
                            </v-tooltip>

                            <DeleteDialogComponent
                                thing="report"
                                :url="`extensions/reports/${item.id}`"
                                @deleted="deleted"
                            ></DeleteDialogComponent>
                        </div>
                    </td>
                </template>
            </v-data-table>
        </v-card-text>

        <SuccessSnackbarComponent v-model="success" :thing="thing"></SuccessSnackbarComponent>

        <ErrorSnackbarComponent v-model="error"></ErrorSnackbarComponent>
    </v-card>

    <v-card v-else>
        <v-card-title class="justify-center text-capitalize pb-lg-3">Owned reports</v-card-title>

        <v-card-text class="d-flex justify-center">
            <v-progress-circular
                indeterminate
                size="96"
            ></v-progress-circular>
        </v-card-text>
    </v-card>
</template>

<script>
import { useCurrenciesStore } from "&/stores/currencies";
import main from "&/mixins/main";
import validation from "&/mixins/validation";

import CreateReportDialogComponent from "@/extensions/reports/CreateReportDialogComponent.vue";
import EditReportDialogComponent from "@/extensions/reports/EditReportDialogComponent.vue";
import DeleteDialogComponent from "@/DeleteDialogComponent.vue";
import SuccessSnackbarComponent from "@/SuccessSnackbarComponent.vue";
import ErrorSnackbarComponent from "@/ErrorSnackbarComponent.vue";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [main, validation],
    components: {
        CreateReportDialogComponent,
        EditReportDialogComponent,
        DeleteDialogComponent,
        SuccessSnackbarComponent,
        ErrorSnackbarComponent
    },
    data() {
        return {
            headers: [
                { text: "ID", value: "id", align: "center" },
                { text: "Title", value: "title", align: "center" },
                { text: "Actions", value: "actions", align: "center", sortable: false }
            ],
            search: "",
            reports: [],
            options: {},
            total: null,
            lastChange: new Date(),

            canRefresh: true,
            tableLoading: false,
            duplicateLoading: null,
            ready: false,
            success: false,
            error: false,
            thing: ""
        }
    },
    computed: {
        dataQuery() {
            if (!Object.keys(this.options).length) {
                return {
                    page: 1,
                    items: 10
                };
            }

            let query = {
                page: this.options.page,
                items: this.options.itemsPerPage
            }

            if (this.search != "") {
                query.search = this.search;
            }

            if (this.options.sortBy.length) {
                query.orderFields = [];
                query.orderDirections = [];

                this.options.sortBy.forEach((item, index) => {
                    query.orderFields.push(item)
                    query.orderDirections.push(this.options.sortDesc[index] ? "desc" : "asc");
                });
            }

            return query;
        }
    },
    methods: {
        getData() {
            if (this.ready) {
                this.tableLoading = true;
            }

            axios
                .get(`/web-api/extensions/reports/owned-reports`, { params: this.dataQuery })
                .then(response => {
                    const data = response.data;

                    this.reports = data.reports.data;
                    this.total = data.reports.total;

                    this.ready = true;
                    this.tableLoading = false;
                })
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
        updated() {
            this.thing = `updated report`;
            this.success = true;
            this.getData();
        },
        deleted() {
            this.thing = `deleted report`;
            this.success = true;
            this.getData();
        },
        async share(id) {
            await navigator.clipboard.writeText(`${window.location.href}/${id}`)
            this.thing = `copied report link`;
            this.success = true;
        },
        duplicate(id) {
            this.duplicateLoading = id;

            axios
                .post(`/web-api/extensions/reports/${id}/duplicate`)
                .then(response => {
                    this.$router.push(`/extensions/reports/${response.data.id}`);
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.duplicateLoading = null, 2000);
                })
        }
    },
    watch: {
        options: {
            handler: function (options, oldOptions) {
                if (Object.keys(oldOptions).length) {
                    this.getData();
                }
            },
            deep: true
        },
        search: "updateWithOffset"
    },
    mounted() {
        this.getData();
    }
}
</script>
