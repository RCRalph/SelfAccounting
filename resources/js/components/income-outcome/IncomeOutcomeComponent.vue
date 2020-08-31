<template>
    <div :class="darkmode ? 'dark-card' : 'card'">
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
                        <a type="button" :href="'/' + type + '/create/many'" class="big-button-primary">
                            {{ 'Add many ' + type + 's' }}
                        </a>
                    </div>
                    <div class="col-md-4 col-6">
                        <a type="button" :href="'/' + type + '/create/one'" class="big-button-primary">
                            {{ 'Add single ' + type }}
                        </a>
                    </div>
                </div>

                <div class="table-responsive-xl w-100" :key="tableKey">
                    <table
                        id="table-multi-hover"
                        :class="[
                            'responsive-table-bordered',
                            darkmode ? 'table-darkmode' : 'table-lightmode'
                        ]"
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
                                    >{{ Number(row.amount) }}</td>
                                <td
                                    rep="price"
                                    v-if="row.price"
                                    :rowspan="row.span.price"
                                    >{{ Number(row.price) + " " + currencies[currentCurrency - 1].ISO }}</td>
                                <td
                                    rep="value"
                                    v-if="row.value"
                                    :rowspan="row.span.value"
                                    >{{ row.value + " " + currencies[currentCurrency - 1].ISO }}</td>
                                <td
                                    rep="category"
                                    v-if="row.category_id"
                                    :rowspan="row.span.category_id"
                                    >{{ categories[row.category_id] || "N / A" }}</td>
                                <td
                                    rep="mean"
                                    v-if="row.mean_id"
                                    :rowspan="row.span.mean_id"
                                    >{{ means[row.mean_id] || "N / A" }}</td>
                                <td class="py-0 h4 cursor-pointer" @click="redirectToShow(row.id)" rep="edit">
                                    <i class="fas fa-edit"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-else-if="!rows.length && dataReady">
                        <h1 class="text-center">Not found</h1>
                    </div>

                    <InfiniteLoading @infinite="getData" :key="currentCurrency">
                        <div slot="no-more"></div>
                    </InfiniteLoading>
                </div>
            </div>

            <div class="d-flex justify-content-center my-2" v-else>
                <div
                    class="spinner-grow"
                    role="status"
                    style="width: 3rem; height: 3rem;"
                >
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TableHeader from "../TableHeader.vue";
import InfiniteLoading from 'vue-infinite-loading';

export default {
    props: {
        type: String
    },
    components: {
        TableHeader,
        InfiniteLoading
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
            darkmode: false,
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
        tableRowsSpanned: function() {
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
                item.value = item.amount * item.price;

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
        getData: function($state) {
            axios
                .get("/webapi/" + this.type, {
                    params: {
                        page: this.page,
                        currency_id: this.currentCurrency
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
        redirectToShow: function(id) {
            window.document.location = "/" + this.type + "/" + id;
        },
        resetRows: function() {
            this.dataReady = false;
            this.rows = [];
            this.page = 1;
            this.tableKey++;
        }
    },
    beforeMount() {
        this.darkmode = document.getElementById("sun-moon").innerHTML.includes("<i class=\"fas fa-sun\"></i>");
    },
    mounted() {
        axios
            .get('/webapi/' + this.type + '/start', {})
            .then(response => {
                this.currencies = response.data.currencies;
                this.means = response.data.means;
                this.categories = response.data.categories;
                this.data = response.data.data;
                this.ready = true;
            });
    },
    beforeUpdate() {
        this.darkmode = document.getElementById("sun-moon").innerHTML.includes("<i class=\"fas fa-sun\"></i>");
    },
    updated() {
        this.$nextTick(() => {
            $('[data-toggle="tooltip"]').tooltip()

            if ($("#table-multi-hover").length) {
                const headerValues = Array.from($("thead")[0].children[0].children)
                    .map(item => item.innerText.toLowerCase());

                let table = [];

                const tableCells = Array.from($("tbody")[0].children)
                    .map(item => item.children)
                    .map(item => Array.from(item));

                for (let i = 0; i < tableCells.length; i++) {
                    let tempObj = {};
                    for (let j = 0; j < tableCells[i].length; j++) {
                        tempObj[tableCells[i][j].attributes.rep.value] = tableCells[i][j];
                    }

                    for (let j = 0; j < headerValues.length; j++) {
                        if (i != 0 && tempObj[headerValues[j]] == undefined) {
                            tempObj[headerValues[j]] = table[i - 1][headerValues[j]];
                        }
                    }
                    table.push(Object.assign({}, tempObj));
                }

                $("tbody td, tbody th").on("mouseover", event => {
                    const isDarkmode = $('#sun-moon').html().includes('<i class="fas fa-sun"></i>');
                    const rowIndex = parseInt(event.currentTarget.parentElement.attributes.i.value);
                    const rep = event.currentTarget.attributes.rep.value;
                    const rowspan = event.currentTarget.attributes.rowspan != undefined ?
                        parseInt(event.currentTarget.attributes.rowspan.value) : 1;

                    for (let i = 0; i < rowspan; i++) {
                        for (let j in table[rowIndex + i]) {
                            $(table[rowIndex + i][j]).addClass("hover-bg-" + (isDarkmode ? "dark" : "light"));
                        }
                    }
                });

                $("tbody td, tbody th").on("mouseleave", event => {
                    const isDarkmode = $('#sun-moon').html().includes('<i class="fas fa-sun"></i>');
                    const rowIndex = parseInt(event.currentTarget.parentElement.attributes.i.value);
                    const rep = event.currentTarget.attributes.rep.value;
                    const rowspan = event.currentTarget.attributes.rowspan != undefined ?
                        parseInt(event.currentTarget.attributes.rowspan.value) : 1;

                    for (let i = 0; i < rowspan; i++) {
                        for (let j in table[rowIndex + i]) {
                            $(table[rowIndex + i][j]).removeClass("hover-bg-" + (isDarkmode ? "dark" : "light"));
                        }
                    }
                });
            }
        });
    }
}
</script>
