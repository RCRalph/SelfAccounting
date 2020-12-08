<template>
    <div :class="darkmode ? 'dark-card' : 'card'">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-file-invoice-dollar"></i>
                Summary
            </div>
            <div class="d-flex" v-if="ready">
                <div class="h4 my-auto mr-3">Currency:</div>
                <select class="form-control" v-model="currentCurrency">
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
            <div v-if="ready && content[currentCurrency]">
                <div class="row">
                    <div class="mx-auto mb-3 col-md-12 col-lg-8 offset-lg-2">
                        <div :class="darkmode ? 'dark-card' : 'card'">
                            <div class="card-header">
                                <div class="m-auto text-center font-weight-bold h2">
                                    Sum
                                </div>
                            </div>

                            <div class="card-body text-center h2 my-auto">{{ sum | addSpaces }} {{ currencies[currentCurrency - 1].ISO }}</div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive-xl w-100">
                    <table
                        :class="[
                            'responsive-table-hover',
                            darkmode ? 'table-darkmode' : 'table-lightmode'
                        ]"
                    >
                        <thead>
                            <tr>
                                <th scope="col" class="h3 font-weight-bold">Type</th>
                                <th scope="col" class="h3 font-weight-bold">Balance</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(item, i) in content[currentCurrency]" :key="i">
                                <th scope="row" class="h5 my-auto font-weight-bold">{{ item.name }}</th>
                                <td class="h5 my-auto">{{ (Math.round(item.balance * 100) / 100) | addSpaces }} {{ currencies[currentCurrency - 1].ISO }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-else-if="ready && !content[currentCurrency]">
                <h1 class="text-center">Nothing to see here, for now...</h1>
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
export default {
    data() {
        return {
            darkmode: false,
            ready: false,
            content: {},
            currencies: [],
            currentCurrency: 1
        };
    },
    computed: {
        sum() {
            return this.content[this.currentCurrency]
                .map(item => item.balance)
                .reduce((item1, item2) => item1 + item2);
        }
    },
    filters: {
        addSpaces(value) {
            return value
                .toLocaleString('en')
                .split(",")
                .join(" ");
        }
    },
    beforeMount() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
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
    },
    beforeUpdate() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    }
}
</script>
