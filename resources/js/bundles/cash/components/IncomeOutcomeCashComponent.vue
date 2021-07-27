<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-money-bill-wave"></i>
                Enter cash
            </div>

            <div class="d-flex" v-if="used.length > 1">
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
            <div class="row">
                <div class="w-100 table-responsive-lg">
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
                                            !isValidCashAmount(value[item.id], item.id) && 'is-invalid'
                                        ]"
                                        type="number"
                                        step="1"
                                        min="0"
                                        v-model="value[item.id]"
                                    >
                                </td>

                                <td class="h5 font-weight-bold">
                                    {{
                                        isValidCashAmount(value[item.id], item.id) ?
                                        Math.round(item.value * value[item.id] * 100) / 100 : 0
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
                <div class="col-6 ">{{ currentSum }} {{ currencies[currentCurrency - 1].ISO }}</div>
            </div>

            <div class="row h3 font-weight-bold">
                <div class="col-6 text-right">Sum from {{ this.type }}:</div>
                <div class="col-6 ">{{ sums[currentCurrency] }} {{ currencies[currentCurrency - 1].ISO }}</div>
            </div>

            <hr class="hr-dashed w-75">

            <div :class="[
                    'row',
                    'h3',
                    'font-weight-bold',
                    sums[currentCurrency] - currentSum != 0 ? 'text-danger' : 'text-success'
                ]">
                    <div class="col-6 text-right">Difference:</div>

                    <div class="col-6">
                        {{ sums[currentCurrency] - currentSum > 0 ? "+" : "" }}{{ Math.round((sums[currentCurrency] - currentSum) * 100) / 100 }}
                        {{ currencies[currentCurrency - 1].ISO }}
                    </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        currencies: Array,
        used: Array,
        cash: Object,
        value: Object,
        sums: Object,
        type: String,
        usersCash: Object
    },
    data() {
        return {
            currentCurrency: 1
        }
    },
	computed: {
        availableCurrencies() {
            return this.currencies.filter(item => this.used.includes(item.id));
        },
        currentSum() {
            for (let i in this.value) {
                if (!this.isValidCashAmount(this.value[i], i)) {
                    return 0;
                }
            }

            return Math.round(this.cash[this.currentCurrency]
				.map(item => {
					return { id: item.id, value: item.value }
				})
				.map(item => this.value[item.id] * item.value)
				.reduce((item1, item2) => item1 + item2) * 100, 3) / 100;
        }
	},
    methods: {
        isValidCashAmount(amount, id) {
            if (amount === "") {
                return false;
            }

            amount = Number(amount);
            if (isNaN(amount)) {
                return false;
            }

            return amount >= 0 && Math.floor(amount) == amount && amount < Math.pow(2, 63) &&
                (this.type == "outcome" && (this.userscash[id] == undefined && amount == 0 || this.userscash[id] >= amount) || this.type == "income");
        }
    },
    mounted() {
        this.currentCurrency = this.used[0];
    },
    watch: {
        value() {
            this.$emit("input", this.value);
        }
    }
}
</script>
