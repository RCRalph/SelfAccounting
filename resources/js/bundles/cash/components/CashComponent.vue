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
                        <select class="form-control" v-model="cashMeans[currentCurrency]">
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

                <div class="row">
					<div class="col-lg-10 offset-lg-1 table-responsive-lg">
						<table class="table-themed responsive-table-hover">
							<thead>
								<th class="h4 font-weight-bold" scope="col">Face value</th>
								<th class="h4 font-weight-bold" scope="col">Amount</th>
                                <th class="h4 font-weight-bold" scope="col">Value</th>
							</thead>

							<tbody>
								<tr
									v-for="(item, i) in cash[currentCurrency]"
									:key="i"
								>
									<th scope="row" class="h5 font-weight-bold">
                                        {{ Number(item.value) }} {{ currencies[currentCurrency - 1].ISO }}
                                    </th>

                                    <td>
                                        <input
                                            :class="[
                                                'cash-input',
                                                !isValidCashAmount(usersCash[item.id]) && 'is-invalid'
                                            ]"
                                            type="number"
                                            step="1"
                                            min="0"
                                            v-model="usersCash[item.id]"
                                        >
                                    </td>

                                    <td class="h5 font-weight-bold">
                                        {{ isValidCashAmount(usersCash[item.id]) ? item.value * usersCash[item.id] : 0 }} {{ currencies[currentCurrency - 1].ISO }}
                                    </td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

                <hr class="hr">


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
            usersCash: {}
        }
    },
    methods: {
        removeZerosFromUsersCash() {
            for (let i in this.usersCash) {
                if (this.usersCash[i] == 0) {
                    delete this.usersCash[i];
                }
            }
        },
        isValidCashAmount(amount) {
            amount = Number(amount);
            if (isNaN(amount)) {
                return false;
            }

            return amount >= 0 && Math.floor(amount) == amount;
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

                for (let i in this.cash) {
                    this.cash[i].forEach(item => {
                        if (data.usersCash[item.id] == undefined) {
                            data.usersCash[item.id] = 0;
                        }
                    })
                }
                this.usersCash = data.usersCash;

                this.ready = true;
            })
    },
    beforeUpdate() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    }
}
</script>
