<template>
    <v-card v-if="ready" class="loading-height">
        <v-card-title class="justify-center text-capitalize pb-lg-0">Shared reports</v-card-title>

        <v-card-text>
            <v-data-table
                :headers="headers"
                :items="reports"
                :mobile-breakpoint="0"
                :loading="tableLoading"
                :server-items-length="total"
                :options.sync="options"
            >
                <template v-slot:top>
                    <v-row class="align-center mb-0">
                        <v-col cols="12" sm="6" lg="5">
                            <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="Search"
                                dense
                                single-line
                                counter="64"
                                :rules="[validation.search(64)]"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="6" lg="7" :order="$vuetify.breakpoint.xsOnly ? 'first' : 'last'" class="d-flex" :class="$vuetify.breakpoint.xsOnly ? 'justify-center' : 'justify-end'">
                            <!--<ExchangeIODialogComponent
                                @exchanged="exchanged"
                            ></ExchangeIODialogComponent>

                            <AddIODialogComponent
                                :type="type"
                                @added="added"
                            ></AddIODialogComponent>-->
                        </v-col>
                    </v-row>
                </template>

                <template v-slot:[`header.owner`]="{ header }">
                    {{ header.text }}
                    <v-menu offset-y :close-on-content-click="false">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn small icon v-bind="attrs" v-on="on">
                                <v-icon small :color="filteredData.owners.length ? 'primary' : ''">
                                    mdi-filter
                                </v-icon>
                            </v-btn>
                        </template>

                        <v-card max-width="350">
                            <v-card-text>
                                <v-select
                                    v-model="filteredData.owners"
                                    :items="owners"
                                    item-text="username"
                                    item-value="id"
                                    multiple
                                    hide-details
                                    filled
                                >
                                    <template v-slot:selection="{ item, index }">
                                        <v-chip v-if="index === 0" small>
                                            <span>{{ item.username }}</span>
                                        </v-chip>

                                        <span
                                            v-if="index === 1"
                                            class="grey--text text-caption"
                                        >(+{{ filteredData.owners.length - 1 }} other{{ filteredData.owners.length > 2 ? "s" : "" }})</span>
                                    </template>
                                </v-select>
                            </v-card-text>
                        </v-card>
                    </v-menu>
                </template>

                <template v-slot:[`item.title`]="{ item }">
                    <span style="white-space: nowrap">{{ item.title }}</span>
                </template>

                <template v-slot:[`item.username`]="{ item }">
                    <span style="white-space: nowrap">{{ item.username }}</span>
                </template>

                <template v-slot:[`item.actions`]="{ item }">
                    <td>
                        <div class="d-flex flex-nowrap justify-center align-center">
                            <DeleteDialogComponent
                                thing="report"
                                :url="`extensions/reports/${item.id}`"
                                @deleted="deleted"
                            ></DeleteDialogComponent>

                            <v-icon class="mx-1 cursor-pointer" @click="share(item.id)">mdi-share</v-icon>
                        </div>
                    </td>
                </template>
            </v-data-table>
        </v-card-text>

        <SuccessSnackbarComponent v-model="success" :thing="thing"></SuccessSnackbarComponent>
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

import DeleteDialogComponent from "@/DeleteDialogComponent.vue";
import SuccessSnackbarComponent from "@/SuccessSnackbarComponent.vue";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [main, validation],
    components: {
        DeleteDialogComponent,
        SuccessSnackbarComponent
    },
    props: {
        owners: {
            required: true,
            type: Array
        }
    },
    data() {
        return {
            headers: [
                { text: "ID", value: "id", align: "center" },
                { text: "Title", value: "title", align: "center" },
                { text: "Owner", value: "owner", align: "center", sortable: false },
                { text: "Actions", value: "actions", align: "center", sortable: false }
            ],
            search: "",
            reports: [],
            options: {},
            filteredData: {
                owners: []
            },
            total: null,
            lastChange: new Date(),

            tableLoading: false,
            ready: false,
            success: false,
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

            Object.keys(this.filteredData).forEach(key => {
                if (this.filteredData[key]) {
                    query[key] = this.filteredData[key];
                }
            })

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
                .get(`/web-api/extensions/reports/shared-reports`, { params: this.dataQuery })
                .then(response => {
                    const data = response.data;

                    this.reports = data.reports.data;
                    this.total = data.total;

                    if (this.ready) {
                        this.tableLoading = false;
                    }
                    else {
                        this.ready = true;
                    }
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
