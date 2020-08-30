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

                        <tr
                            v-for="(row, index) in rows"
                            :key="index"
                            :i="index"
                        >
                            <th scope="row" rep="date">{{ row.date }}</th>
                            <td rep="title">{{ row.title }}</td>
                            <td rep="amount">{{ Number(row.amount) }}</td>
                            <td rep="price">{{ Number(row.price) }}</td>
                            <td rep="value">{{ row.price * row.amount }}</td>
                            <td rep="category">{{ row.category }}</td>
                            <td rep="mean">{{ row.mean }}</td>
                            <td class="py-0 h4 cursor-pointer" @click="redirectToShow(row.id)" rep="edit">
                                <i class="fas fa-edit"></i>
                            </td>
                        </tr>
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
        });
    }
}
</script>
