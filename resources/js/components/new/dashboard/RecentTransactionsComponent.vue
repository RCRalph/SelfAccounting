<template>
    <div v-if="ready">
        <v-data-table
            disable-sort
            :headers="headers"
            :items="mergedCells"
            :items-per-page="pagination.perPage"
            :loading="tableLoading"
            hide-default-footer
            :mobile-breakpoint="0"

            class="table-bordered"
        >
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

                    <td v-if="item.value.span" :rowspan="item.value.span" @mouseover="setRowsToHighlight(index, item.price.span)" @mouseleave="resetRowsToHighlight()"
                        :class="isRowHighlighted(index, item.price.span) && 'table-hover-background'"
                    >{{ item.value.value | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}</td>

                    <td v-if="item.category.span" :rowspan="item.category.span" @mouseover="setRowsToHighlight(index, item.category.span)" @mouseleave="resetRowsToHighlight()"
                        :class="isRowHighlighted(index, item.category.span) && 'table-hover-background'"
                    >{{ item.category.value }}</td>

                    <td v-if="item.mean.span" :rowspan="item.mean.span" @mouseover="setRowsToHighlight(index, item.mean.span)" @mouseleave="resetRowsToHighlight()"
                        :class="isRowHighlighted(index, item.mean.span) && 'table-hover-background'"
                    >{{ item.mean.value }}</td>

                    <td style="white-space: nowrap" @mouseover="setRowsToHighlight(index, 1)" @mouseleave="resetRowsToHighlight()"
                        :class="isRowHighlighted(index, 1) && 'table-hover-background'"
                    >
                        <EditIODialogComponent
                            :type="item.value.value < 0 ? 'outcome' : 'income'"
                            :id="item.id.value"
                            @updated="getData"
                        ></EditIODialogComponent>

                        <DeleteDialogComponent
                            :thing="item.value.value < 0 ? 'outcome' : 'income'"
                            :url="`${item.value.value < 0 ? 'outcome' : 'income'}/${item.id.value}`"
                            @deleted="getData"
                        ></DeleteDialogComponent>
                    </td>
                </tr>
            </template>
        </v-data-table>

        <div class="text-center pt-4">
            <v-pagination
                v-model="pagination.page"
                :length="pagination.last"
            ></v-pagination>
        </div>
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

import EditIODialogComponent from "@/income-outcome/EditIODialogComponent.vue";
import DeleteDialogComponent from "@/DeleteDialogComponent.vue";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [main, customTableMerged],
    components: {
        EditIODialogComponent,
        DeleteDialogComponent
    },
    data() {
        return {
            headers: [
                { text: "Date", align: "center" },
                { text: "Title", align: "center" },
                { text: "Amount", align: "center" },
                { text: "Price", align: "center" },
                { text: "Value", align: "center" },
                { text: "Category", align: "center" },
                { text: "Mean of payment", align: "center" },
                { text: "Actions", align: "center" }
            ],
            items: [],
            pagination: {
                page: 1,
                last: null,
                perPage: null
            },

            ready: false,
            tableLoading: false,
            dialogs: {
                delete: false,
                edit: false
            }
        }
    },
    methods: {
        getData() {
            if (this.ready) {
                this.tableLoading = true
            }
            else {
                this.ready = false;
            }

            axios
                .get(`/web-api/dashboard/${this.currencies.usedCurrency}/recent-transactions?page=${this.pagination.page}`)
                .then(response => {
                    const data = response.data;

                    this.items = data.items.data;
                    this.pagination.last = data.items.last_page;
                    this.pagination.perPage = data.items.per_page;

                    if (this.ready) {
                        this.tableLoading = false;
                    }
                    else {
                        this.ready = true;
                    }
                })
        }
    },
    watch: {
        'pagination.page'() {
            this.getData()
        }
    },
    mounted() {
        this.getData();
    }
}
</script>
