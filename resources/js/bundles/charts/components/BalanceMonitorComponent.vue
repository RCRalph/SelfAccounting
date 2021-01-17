<template>
    <div :class="darkmode ? 'dark-card' : 'card'">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-chart-bar"></i>
                Balance monitor
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
                <div class="w-100 d-flex justify-content-center align-items-center mb-3">
                    <div>
                        <div class="d-flex">
                            <input
                                type="date"
                                :min="limitDates[0].min"
                                :max="limitDates[0].max"
                                v-model="dates[0]"
                                class="form-control"
                                style="min-width: 140px;"
                            >

                            <i class="fas fa-arrow-right h3 my-auto mx-3 text-primary"></i>

                            <input
                                type="date"
                                :min="limitDates[1].min"
                                :max="limitDates[1].max"
                                v-model="dates[1]"
                                class="form-control"
                                style="min-width: 140px;"
                            >
                        </div>
                    </div>
                </div>

                <Chart
					v-if="datasets"
                    :chart-data="datasets"
                    :options="options"
                    style="position: relative; min-height: 70vh;"
                ></Chart>

				<EmptyPlaceholder v-else></EmptyPlaceholder>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import Loading from "../../../components/Loading.vue";
import EmptyPlaceholder from "../../../components/EmptyPlaceholder.vue";
import Chart from "./LineChart.vue";

export default {
    components: {
        Loading,
		EmptyPlaceholder,
        Chart
    },
    data() {
        return {
            darkmode: false,
            ready: false,

            currencies: [],
            currentCurrency: 1,
            dates: [null, null],

            data: {},
            options: {}
        }
    },
    computed: {
        limitDates() {
            const retArr = [
                {
                    min: null,
                    max: null
                },
                {
                    min: null,
                    max: null
                }
            ]

			const MILISECONDS_IN_DAY = 60 * 60 * 24 * 1000;
			retArr[0].max = this.dates[1] ? this.convertToISOdate(new Date(this.dates[1]).getTime() - MILISECONDS_IN_DAY) : null;
            retArr[1].min = this.dates[0] ? this.convertToISOdate(new Date(this.dates[0]).getTime() + MILISECONDS_IN_DAY) : null;

            return retArr;
        },
		datasets() {
            const dataForCurrency = _.cloneDeep(this.data[this.currentCurrency]);

            if (dataForCurrency == undefined) {
                return false;
            }

            const minDateInput = this.dates[0] ? new Date(this.dates[0]).getTime() : -1;
            const maxDateInput = this.dates[1] ? new Date(this.dates[1]).getTime() : -1;

            if (minDateInput > maxDateInput && maxDateInput != -1) {
				console.log("invalid dates");
                return dataForCurrency;
            }

            const limitDates = dataForCurrency.datasets
                .map(item => {
                    return [
                        new Date(item.data[0].t).getTime(),
                        new Date(item.data[item.data.length - 1].t).getTime()
                    ];
				});

			const maxDateFromData = limitDates
				.map(item => item[1])
				.reduce((item1, item2) => item1 > item2 ? item1 : item2);

            dataForCurrency.datasets.forEach((item, index) => {
				const data = item.data
				.map(item1 => {
					return {
						y: item1.y,
						t: new Date(item1.t).getTime()
					}
				})
				.filter(item1 =>
					(minDateInput == -1 || minDateInput <= item1.t) &&
					(maxDateInput == -1 || maxDateInput >= item1.t)
				);

                // Add entries for the start of the x-axis
				if (minDateInput != -1 &&
					new Date(item.data[0].t).getTime() < minDateInput &&
						(
							!data.length ||
							data.length &&
							data[0].t != minDateInput
						)
				) {
					const lastValue = item.data.filter(item1 => new Date(item1.t).getTime() < minDateInput);

					data.unshift({
						t: minDateInput,
						y: lastValue[lastValue.length - 1].y
					});
				}

				// Add entries for the end of the x-axis
				if (maxDateInput == -1) {
					if (item.data.length &&
						new Date(item.data[item.data.length - 1].t).getTime() != maxDateFromData
					) {
						data.push({
							t: maxDateFromData,
							y: item.data[item.data.length - 1].y
						});
					}
				}
				else {
					if (limitDates[index][0] < maxDateInput &&
						data.length && data[data.length - 1].t < maxDateInput) {
						data.push({
							t: maxDateInput,
							y: data[data.length - 1].y
						});
					}
				}

                dataForCurrency.datasets[index].data = data.map(item1 => {
					return {
						t: this.convertToISOdate(item1.t),
						y: item1.y
					};
				});
            });

			return dataForCurrency;
		}
	},
	methods: {
		convertToISOdate(date) {
			return new Date(date).toISOString().split("T")[0];
		}
	},
    beforeMount() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    },
    mounted() {
        axios
            .get("/webapi/bundles/charts/balance-monitor", {})
            .then(response => {
                const data = response.data;

                this.currencies = data.currencies;
                this.currentCurrency = data.lastCurrency;

                this.data = data.data,
                this.options = data.options;

                this.ready = true;
            })
    },
    beforeUpdate() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    }
}
</script>
