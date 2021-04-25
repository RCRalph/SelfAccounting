<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i :class="[
                        'fas',
                        type == 'income' ? 'fa-sign-in-alt' : 'fa-sign-out-alt'
                    ]"
                ></i>
                {{ type.charAt(0).toUpperCase() + type.slice(1) }}
            </div>

            <div class="d-flex" v-if="ready">
                <div class="h4 my-auto mr-3">Currency:</div>
                <select class="form-control" v-model="currentCurrency" @change="resetRows">
                    <option
                        v-for="currency in currencies"
                        :key="currency.id"
                        :value="currency.id"
                    >
                        {{ currency.ISO }}
                    </option>
                </select>
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <div class="row mb-3">
                    <div class="col-md-4 col-6 offset-md-2">
                        <a type="button" :href="'/' + type + '/create-multiple'" class="big-button-primary">
                            {{ `Add multiple ${type}` }}
                        </a>
                    </div>
                    <div class="col-md-4 col-6">
                        <a type="button" :href="'/' + type + '/create-one'" class="big-button-primary">
                            {{ `Add single ${type}` }}
                        </a>
                    </div>
                </div>

                <div class="table-responsive-xl w-100" :key="tableKey">
                    <table
                        id="table-multi-hover"
                        class="responsive-table-bordered table-themed"
                        v-if="rows.length && dataReady"
                    >
                        <TableHeader :cells="headerCells"></TableHeader>

                        <tbody>
                            <tr
                                v-for="(row, index) in tableRowsSpanned"
                                :key="index"
                                :i="index"
                            >
                                <th
                                    scope="row"
                                    rep="date"
                                    v-if="row.date"
                                    :rowspan="row.span.date"
                                    >{{ row.date }}</th>
                                <td
                                    rep="title"
                                    v-if="row.title"
                                    :rowspan="row.span.title"
                                    >{{ row.title }}</td>
                                <td
                                    rep="amount"
                                    v-if="row.amount"
                                    :rowspan="row.span.amount"
                                    >{{ row.amount }}</td>
                                <td
                                    rep="price"
                                    v-if="row.price"
                                    :rowspan="row.span.price"
                                    >{{ row.price + " " + currencies[currentCurrency - 1].ISO }}</td>
                                <td
                                    rep="value"
                                    v-if="row.value"
                                    :rowspan="row.span.value"
                                    >{{ row.value + " " + currencies[currentCurrency - 1].ISO }}</td>
                                <td
                                    rep="category"
                                    v-if="row.category_id !== undefined"
                                    :rowspan="row.span.category_id"
                                    >{{ categories[row.category_id] || "N / A" }}</td>
                                <td
                                    rep="mean"
                                    v-if="row.mean_id !== undefined"
                                    :rowspan="row.span.mean_id"
                                    >{{ means[row.mean_id] || "N / A" }}</td>
                                <td class="py-0 h4 cursor-pointer" @click="redirectToShow(row.id)" rep="edit">
                                    <i class="fas fa-edit p-2"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <EmptyPlaceholder
                        v-else-if="!rows.length && dataReady"
                    ></EmptyPlaceholder>

                    <InfiniteLoading @infinite="getData" :key="currentCurrency">
                        <div slot="no-more"></div>
                    </InfiniteLoading>
                </div>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import TableHeader from "../TableHeader.vue";
import InfiniteLoading from 'vue-infinite-loading';
import EmptyPlaceholder from "../EmptyPlaceholder.vue";
import Loading from "../Loading.vue";

export default {
    props: ["type"],
    components: {
        TableHeader,
        InfiniteLoading,
        EmptyPlaceholder,
        Loading
    },
    data() {
        return {
            currencies: [],
            means: {},
            categories: {},
            rows: [],
            page: 1,
            dataReady: false,
            ready: false,
            tableKey: 0,
            currentCurrency: 1,
            headerCells: [
                { text: "Date" },
                { text: "Title" },
                { text: "Amount" },
                { text: "Price" },
                { text: "Value" },
                { text: "Category" },
                { text: "Mean" },
                { text: "Edit" }
            ],
        }
    },
    computed: {
        tableRowsSpanned() {
            let rowspaned = [], lastValues = {};
            const spanSet1 = {
                date: 1,
                title: 1,
                amount: 1,
                price: 1,
                value: 1,
                category_id: 1,
                mean_id: 1,
                currency_id: 1
            };

            this.rows.forEach((item, i) => {
                item.value = (Math.round(item.amount * item.price * 100) / 100).toLocaleString('en').split(",").join(" ");
                item.amount = Number(item.amount).toLocaleString('en').split(",").join(" ");
                item.price = Number(item.price).toLocaleString('en').split(",").join(" ");

                if (!rowspaned.length) {
                    rowspaned.push({
                        ...item,
                        span: { ...spanSet1 }
                    });
                    lastValues = {
                        ...item,
                        span: { ...spanSet1 },
                        indeces: {
                            date: 0,
                            title: 0,
                            amount: 0,
                            price: 0,
                            value: 0,
                            category_id: 0,
                            mean_id: 0,
                            currency_id: 0
                        }
                    };
                    return
                }

                if (item.date != lastValues.date) {
                    rowspaned.push({
                        ...item,
                        span: { ...spanSet1 }
                    });
                    lastValues = {
                        ...item,
                        span: { ...spanSet1 },
                        indeces: {
                            date: i,
                            title: i,
                            amount: i,
                            price: i,
                            value: i,
                            category_id: i,
                            mean_id: i,
                            currency_id: i
                        }
                    };
                }
                else {
                    let pushObj = {span: {}}
                    for (let j in item) {
                        if (item[j] == lastValues[j]) {
                            rowspaned[lastValues.indeces[j]].span[j]++;
                        }
                        else {
                            pushObj[j] = item[j];
                            pushObj.span[j] = 1;
                            lastValues[j] = item[j];
                            lastValues.indeces[j] = i;
                        }
                    }
                    rowspaned.push(pushObj);
                }
            })
            return rowspaned
        }
    },
    methods: {
        getData($state) {
            axios
                .get(`/webapi/${this.type}/all/${this.currentCurrency}`, {
                    params: {
                        page: this.page
                    }
                })
                .then(response => {
                    this.dataReady = true;
                    this.rows = this.rows.concat(response.data.data.data);
                    this.page++;

					if (response.data.data.current_page == response.data.data.last_page) {
						$state.complete();
					}
                    $state.loaded();
                });
        },
        redirectToShow(id) {
            window.location.href = `/${this.type}/${id}`;
        },
        resetRows() {
            this.dataReady = false;
            this.rows = [];
            this.page = 1;
            this.tableKey++;
        }
    },
    mounted() {
        axios
            .get(`/webapi/${this.type}/start`, {})
            .then(response => {
                this.currencies = response.data.currencies;
                this.means = response.data.means;
                this.categories = response.data.categories;
                this.data = response.data.data;
                this.currentCurrency = response.data.lastCurrency;
                this.ready = true;
            });
    },
    updated() {
        this.$nextTick(() => {
            $('[data-toggle="tooltip"]').tooltip()

            if (document.getElementById("table-multi-hover")) {
                tableHoveringScript();
            }
        });
    }
}
</script>
