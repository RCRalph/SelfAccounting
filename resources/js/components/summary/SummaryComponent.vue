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
            <div v-if="ready">
                <div class="row">
                    <div class="mx-auto mb-3 col-md-12 col-lg-6 offset-lg-3">
                        <div :class="darkmode ? 'dark-card' : 'card'">
                            <div class="card-header">
                                <div class="m-auto text-center font-weight-bold h2">
                                    Sum
                                </div>
                            </div>

                            <div class="card-body text-center h2 my-auto">125.00 EUR</div>
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
                                <th scope="col" class="h3 font-weight-bold">Something</th>
                                <th scope="col" class="h3 font-weight-bold">Balance</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(item, i) in content[currentCurrency]" :key="i">
                                <th scole="row" class="h5 my-auto font-weight-bold">{{ item.name }}</th>
                                <td class="h5 my-auto">{{ item.balance + " " + currencies[currentCurrency - 1].ISO }}</td>
                            </tr>
                        </tbody>
                    </table>
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
    beforeMount() {
        this.darkmode = document.getElementById("sun-moon").innerHTML.includes("<i class=\"fas fa-sun\"></i>");
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
        this.darkmode = document.getElementById("sun-moon").innerHTML.includes("<i class=\"fas fa-sun\"></i>");
    }
}
</script>
