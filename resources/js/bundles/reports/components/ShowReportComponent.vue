<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-newspaper"></i>
                {{ ready ? data.title : `Report #${id}` }}
            </div>

            <div class="d-flex" v-if="ready && edit == '1'">
                <a role="button" class="big-button-primary" :href="`/bundles/reports/${id}/edit`">
                    Edit report
                </a>
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <div v-if="rows.length">
                    <div class="row" v-if="sum !== null">
                        <div class="mx-auto mb-3 col-md-8 col-lg-6 offset-lg-3">
                            <div class="card">
                                <div class="card-header-flex">
                                    <div class="card-header-text">
                                        <i class="fas fa-calculator"></i>
                                        Sum
                                    </div>

                                    <div class="d-flex" v-if="Object.keys(sum).length">
                                        <div class="h4 my-auto mr-3">Currency:</div>
                                        <select class="form-control" v-model="sumCurrency">
                                            <option
                                                v-for="(currency, i) in Object.keys(sum)"
                                                :key="i"
                                                :value="currency"
                                            >
                                                {{ currencies[currency - 1].ISO }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="card-body text-center h2 my-auto">
                                    {{ sum[sumCurrency].toLocaleString('en').split(",").join(" ") }}
                                    {{ currencies[sumCurrency - 1].ISO }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-4 col-md-6 col-12 my-2 my-md-0 offset-lg-4 offset-md-3">
                            <button class="big-button-primary btn-lg" @click="exportToTXT">
                                <i class="fas fa-file-alt"></i>
                                Export to .txt file
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive w-100">
                        <table
                            id="table-multi-hover"
                            class="responsive-table-bordered table-themed"
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
                                    >{{ row.date }}
                                    </th>

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
                                    >{{ row.price + " " + currencies[rows[index].currency_id - 1].ISO }}</td>

                                    <td
                                        rep="value"
                                        v-if="row.value"
                                        :rowspan="row.span.value"
                                    >{{ row.value + " " + currencies[rows[index].currency_id - 1].ISO }}</td>

                                    <td
                                        rep="category"
                                        v-if="row.category_id !== undefined"
                                        :rowspan="row.span.category_id"
                                    >{{ getCategoryName(rows[index].currency_id, row.category_id) }}</td>

                                    <td
                                        rep="mean"
                                        v-if="row.mean_id !== undefined"
                                        :rowspan="row.span.mean_id"
                                    >{{ getMeanName(rows[index].currency_id, row.mean_id) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <EmptyPlaceholder v-else></EmptyPlaceholder>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import TableHeader from "../../../components/TableHeader.vue";
import EmptyPlaceholder from "../../../components/EmptyPlaceholder.vue";
import Loading from "../../../components/Loading.vue";

export default {
    props: ["id", "edit"],
    components: {
        TableHeader,
        EmptyPlaceholder,
        Loading
    },
    data() {
        return {
            data: {},
            currencies: [],
            means: {},
            categories: {},
            rows: [],
            sum: null,
            sumCurrency: 1,
            ready: false,

            headerCells: [
                { text: "Date" },
                { text: "Title" },
                { text: "Amount" },
                { text: "Price" },
                { text: "Value" },
                { text: "Category" },
                { text: "Mean" }
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

            this.rows.forEach((item1, i) => {
                let item = _.cloneDeep(item1);

                item.value = (item.amount * item.price)
                    .toLocaleString("en", {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).split(",").join(" ");
                item.amount = Number(item.amount)
                    .toLocaleString("en", {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 3
                    }).split(",").join(" ");
                item.price = Number(item.price)
                    .toLocaleString("en", {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).split(",").join(" ");

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
            return rowspaned;
        }
    },
    methods: {
        getCategoryName(currency, id) {
            if (!id) {
                return "N/A";
            }
            const foundCategory = this.categories[currency]
                .find(item => item.id == id);

            return foundCategory ? foundCategory.name : "N/A";
        },
        getMeanName(currency, id) {
            if (!id) {
                return "N/A";
            }

            const foundMean = this.means[currency]
                .find(item => item.id == id);

            return foundMean ? foundMean.name : "N/A";
        },
		exportToTXT() {
			let fileRows = ["Date\tTitle\tAmount\tPrice\tValue\tCategory\tMean"];

            this.rows.forEach((item, index) => {
                fileRows.push(
                `${
                    item.date
                }\t${
                    item.title
                }\t${
                    item.amount
                }\t${
                    item.price + " " + this.currencies[this.rows[index].currency_id - 1].ISO
                }\t${
                    item.value + " " + this.currencies[this.rows[index].currency_id - 1].ISO
                }\t${
                    this.getCategoryName(this.rows[index].currency_id, item.category_id)
                }\t${
                    this.getMeanName(this.rows[index].currency_id, item.mean_id)
                }`);
            })

            let result = fileRows.join("\n");

            const download = document.createElement("a");
            download.style.display = "none;"
            download.href = `data:text/plain;charset:utf-8,${result}`;
            download.download = `${this.data.title}.txt`

            document.body.appendChild(download);
            download.click();
            document.body.removeChild(download);
		}
    },
    mounted() {
        axios
            .get(`/webapi/bundles/reports/${this.id}`, {})
            .then(response => {
                this.currencies = response.data.currencies;
                this.means = response.data.means;
                this.categories = response.data.categories;
                this.data = response.data.reportData;
                this.rows = response.data.rows;
                this.sum = response.data.sum;

                if (response.data.sum !== null) {
                    this.sumCurrency = Object.keys(response.data.sum);
                    this.sumCurrency = this.sumCurrency.length ? this.sumCurrency[0] : 0;
                }

                this.ready = true;
            });
    },
    updated() {
        this.$nextTick(() => {
            if (document.getElementById("table-multi-hover")) {
                tableHoveringScript();
            }
        });
    }
}
</script>
