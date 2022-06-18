<template>
    <div>
        <v-data-table
            disable-sort
            :headers="headers"
            :items="mergedCells"
            :items-per-page="pagination.perPage"
            :loading="tableLoading"
            hide-default-footer

            class="table-bordered"
        >
            <template v-slot:item="{item, index}">
                <tr class="text-center">
                    <td v-if="item.date.span" :rowspan="item.date.span" :i="index" @mouseover="hoverId = index">{{ item.date.value }}</td>
                    <td v-if="item.title.span" :rowspan="item.title.span">{{ item.title.value }}</td>
                    <td v-if="item.amount.span" :rowspan="item.amount.span">{{ item.amount.value }}</td>
                    <td v-if="item.price.span" :rowspan="item.price.span">{{ item.price.value | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}</td>
                    <td v-if="item.value.span" :rowspan="item.value.span">{{ item.value.value | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}</td>
                    <td v-if="item.category.span" :rowspan="item.category.span">{{ item.category.value }}</td>
                    <td v-if="item.mean.span" :rowspan="item.mean.span">{{ item.mean.value }}</td>
                    <td style="white-space: nowrap">
                        <v-icon class="mr-2 cursor-pointer">mdi-pencil</v-icon>
                        <v-icon class="cursor-pointer">mdi-delete</v-icon>
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
</template>

<script>
import { useCurrenciesStore } from "../../../stores/currencies";
import main from "../../../mixins/main";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [main],
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
            tableLoading: false
        }
    },
    computed: {
        mergedCells() {
            if (!this.items.length) {
                return [];
            }

            let keys = Object.keys(this.items[0]).filter(item => item != "id"), counters = {}, retArr = [];
            keys.forEach(item => {
                counters[item] = {
                    value: this.items[0][item],
                    count: 1
                };
            });

            this.items.forEach((item, index) => {
                let pushObj = {};
                keys.forEach(item => pushObj[item] = {
                    value: this.items[index][item],
                    span: 0
                });
                retArr.push(pushObj);

                keys.forEach(key => {
                    if (index && item[key] != counters[key].value) {
                        retArr[index - counters[key].count][key].span = counters[key].count;
                        counters[key] = {
                            value: item[key],
                            count: 1
                        }
                    }
                    else if (index) {
                        counters[key].count += 1
                    }
                })
            })

            keys.forEach(item => {
                retArr[retArr.length - counters[item].count][item].span = counters[item].count;
            })

            return retArr;
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
