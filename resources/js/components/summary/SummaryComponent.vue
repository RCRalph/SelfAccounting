<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-file-invoice-dollar"></i>
                Summary
            </div>

            <div class="d-flex" v-if="ready && availableCurrencies.length > 1">
                <div class="h4 my-auto me-3">Currency:</div>
                <select class="form-control" v-model="currentCurrency">
                    <option
                        v-for="currency in availableCurrencies"
                        :key="currency.id"
                        :value="currency.id"
                    >
                        {{ currency.ISO }}
                    </option>
                </select>
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready && content[currentCurrency]">
                <div class="row">
                    <div class="mx-auto mb-3 col-md-12 col-lg-8 offset-lg-2">
                        <div class="card">
                            <div class="card-header">
                                <div class="m-auto text-center fw-bold h2">
                                    Sum
                                </div>
                            </div>

                            <div class="card-body text-center h2 my-auto">{{ sum | addSpaces }} {{ currencies[currentCurrency - 1].ISO }}</div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive w-100">
                    <table class="table-themed responsive-table-hover">
                        <thead>
                            <tr>
                                <th scope="col" class="h3 fw-bold">Mean</th>
                                <th scope="col" class="h3 fw-bold">Balance</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(item, i) in content[currentCurrency]" :key="i">
                                <th scope="row" class="h5 my-auto fw-bold">{{ item.name }}</th>
                                <td class="h5 my-auto">{{ item.balance | addSpaces }} {{ currencies[currentCurrency - 1].ISO }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <EmptyPlaceholder
                v-else-if="ready && !content[currentCurrency]"
            ></EmptyPlaceholder>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import EmptyPlaceholder from "../EmptyPlaceholder.vue";
import Loading from "../Loading.vue";

export default {
    components: {
        EmptyPlaceholder,
        Loading
    },
    data() {
        return {
            ready: false,
            content: {},
            currencies: [],
            currentCurrency: 1
        };
    },
    computed: {
        sum() {
            if (!this.content[this.currentCurrency]) {
                return 0;
            }

            return this.content[this.currentCurrency]
                .map(item => item.balance)
                .reduce((item1, item2) => item1 + item2);
        },
        availableCurrencies() {
            let retArr = [];

            for (let i in this.currencies) {
                if (this.content[this.currencies[i].id]) {
                    retArr.push(this.currencies[i]);
                }
            }

            return retArr;
        }
    },
    filters: {
        addSpaces(value) {
            return value
                .toLocaleString("en", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })
                .split(",")
                .join(" ");
        }
    },
    mounted() {
        axios
            .get('/webapi/summary', {})
            .then(response => {
                this.content = response.data.finalData;
                this.currencies = response.data.currencies;
                this.currentCurrency = response.data.lastCurrency;

                this.ready = true;
            });
    }
}
</script>
