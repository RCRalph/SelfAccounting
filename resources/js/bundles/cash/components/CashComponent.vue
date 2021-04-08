<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-money-bill-wave"></i>
                Cash handling
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
                <div class="form-group row">
                    <div class="col-lg-5 d-flex justify-content-lg-end justify-content-start align-items-center">
                        <div class="h5 font-weight-bold m-lg-0">Mean of payment used as cash:</div>
                    </div>

                    <div class="col-lg-5">
                        <select class="form-control" v-model="cashMeans[currentCurrency]" :disabled="disabled">
                            <option :value="null" :selected="null">N/A</option>
                            <option
                                v-for="(item, i) in means[currentCurrency]"
                                :key="i"
                                :value="item.id"
                                :selected="item.id"
                            >
                                {{ item.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <hr class="hr">

                <!-- Table of cash with inputs and value -->
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import Loading from "../../../components/Loading.vue";

export default {
    components: {
        Loading
    },
    data() {
        return {
            darkmode: false,
            ready: false,

            currencies: [],
            currentCurrency: 1,
            cash: {},
            means: {},
            cashMeans: {},
        }
    },
    beforeMount() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    },
    mounted() {
        axios
            .get("/webapi/bundles/cash", {})
            .then(response => {
                const data = response.data;

                this.currencies = data.currencies;
                this.currentCurrency = data.lastCurrency;
                this.cash = data.cash;
                this.means = data.means;
                this.cashMeans = data.cashMeans;

                this.ready = true;
            })
    },
    beforeUpdate() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    }
}
</script>
