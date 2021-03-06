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
                                        {{ isValidCashAmount(usersCash[item.id]) ?
											Math.round(item.value * usersCash[item.id] * 100) / 100 : 0
										}}
										{{ currencies[currentCurrency - 1].ISO }}
                                    </td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

                <div class="row h3 font-weight-bold">
                    <div class="col-6 text-right">Sum:</div>
                    <div class="col-6 ">{{ sumOfCash }} {{ currencies[currentCurrency - 1].ISO }}</div>
                </div>

                <div v-if="currentCashMeanBalance !== false">
                    <div class="row h3 font-weight-bold">
                        <div class="col-6 text-right">Current balance:</div>
                        <div class="col-6 ">{{ currentCashMeanBalance }} {{ currencies[currentCurrency - 1].ISO }}</div>
                    </div>

                    <hr class="hr-dashed w-75">

                    <div :class="[
                        'row',
                        'h3',
                        'font-weight-bold',
                        currentCashMeanBalance - sumOfCash != 0 ? 'text-danger' : 'text-success'
                    ]">
                        <div class="col-6 text-right">Balance difference:</div>
                        <div class="col-6">
                            {{ currentCashMeanBalance - sumOfCash > 0 ? "+" : "" }}{{ Math.round((currentCashMeanBalance - sumOfCash) * 100) / 100 }}
                            {{ currencies[currentCurrency - 1].ISO }}
                        </div>
                    </div>
                </div>

                <hr class="hr">

                <SaveResetChanges
                    @save="saveChanges"
                    @reset="resetChanges"
                    :disableSave="disableSubmit"
                    :spinner="saveChangesSpinner"
                ></SaveResetChanges>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import Loading from "../../../components/Loading.vue";
import SaveResetChanges from "../../../components/SaveResetChanges.vue";

export default {
    components: {
        Loading,
        SaveResetChanges
    },
    data() {
        return {
            ready: false,

            currencies: [],
            currentCurrency: 1,
            cash: {},
            means: {},
            cashMeans: {},
            cashMeansCopy: {},
            usersCash: {},
            usersCashCopy: {},
            saveChangesSpinner: false
        }
    },
	computed: {
		sumOfCash() {
            if (this.disableSubmit) {
                return 0;
            }

			return Math.round(this.cash[this.currentCurrency]
				.map(item => {
					return { id: item.id, value: item.value }
				})
				.map(item => this.usersCash[item.id] * item.value)
				.reduce((item1, item2) => item1 + item2) * 100) / 100;
		},
        currentCashMeanBalance() {
            if (this.cashMeans[this.currentCurrency] == null) {
                return false;
            }

            return this.means[this.currentCurrency]
                .find(item => item.id == this.cashMeans[this.currentCurrency]).balance
        },
        disableSubmit() {
            for (let i in this.usersCash) {
                if (!this.isValidCashAmount(this.usersCash[i])) {
                    return true;
                }
            }

            return false;
        }
	},
    methods: {
        isValidCashAmount(amount) {
            if (amount === "") {
                return false;
            }

            amount = Number(amount);
            if (isNaN(amount)) {
                return false;
            }

            return amount >= 0 && Math.floor(amount) == amount && amount < Math.pow(2, 63);
        },
        saveChanges() {
            this.saveChangesSpinner = true;

            const usersCash = [];
            for (let i in this.usersCash) {
                if (this.usersCash[i] != 0){
                    usersCash.push({
                        id: i,
                        amount: this.usersCash[i]
                    });
                }
            }

            const cashMeans = [];
            for (let i in this.cashMeans) {
                if (this.cashMeans[i] != null) {
                    cashMeans.push(this.cashMeans[i]);
                }
            }

            axios
                .post("/webapi/bundles/cash", {
                    usersCash, cashMeans
                })
                .then(() => {
                    this.cashMeansCopy = _.cloneDeep(this.cashMeans);
                    this.usersCashCopy = _.cloneDeep(this.usersCash);
                })
                .catch(err => {
                    console.error(err);
                })
                .finally(() => {
                    this.saveChangesSpinner = false;
                })
        },
        resetChanges() {
            this.cashMeans = _.cloneDeep(this.cashMeansCopy);
            this.usersCash = _.cloneDeep(this.usersCashCopy);
        }
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
                this.cashMeansCopy = _.cloneDeep(data.cashMeans);

                for (let i in this.cash) {
                    this.cash[i].forEach(item => {
                        if (data.usersCash[item.id] == undefined) {
                            data.usersCash[item.id] = 0;
                        }
                    })
                }
                this.usersCash = data.usersCash;
                this.usersCashCopy = _.cloneDeep(data.usersCash);

                this.ready = true;
            })
    }
}
</script>
