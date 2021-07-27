<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fab fa-buffer"></i>
                Means of payment
            </div>

            <div class="d-flex">
                <div class="h4 my-auto me-3">Currency:</div>
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
            <div
                v-if="content[currentCurrency].length"
                class="table-responsive-lg"
            >
                <table class="responsive-table-hover table-themed">
                    <TableHeader
                        :cells="header"
                    ></TableHeader>

                    <tbody>
                        <tr
                            v-for="mean in means[currentCurrency]"
                            :key="mean.id"
                        >
                            <th scope="row">
                                {{ mean.name }}
                            </th>

                            <td>
                                <i :class="[
                                    'fas',
                                    'h3',
                                    'my-auto',
                                    mean.income_mean ? 'fa-check-square' : 'fa-times-circle',
                                    mean.income_mean ? 'text-success' : 'text-danger'
                                ]"></i>
                            </td>

                            <td>
                                <i :class="[
                                    'fas',
                                    'h3',
                                    'my-auto',
                                    mean.outcome_mean ? 'fa-check-square' : 'fa-times-circle',
                                    mean.outcome_mean ? 'text-success' : 'text-danger'
                                ]"></i>
                            </td>

                            <td>
                                <Slider
                                    v-model="mean.show_on_charts"
                                ></Slider>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <EmptyPlaceholder v-else></EmptyPlaceholder>

            <hr class="hr">

            <SaveResetChanges
                @save="save"
                @reset="reset"
                :disableAll="submitted"
                :spinner="submitted"
            ></SaveResetChanges>
        </div>
    </div>
</template>

<script>
import TableHeader from "../../../../components/TableHeader.vue";
import EmptyPlaceholder from "../../../../components/EmptyPlaceholder.vue";
import Slider from "../../../../components/SliderCheckbox.vue";
import SaveResetChanges from "../../../../components/SaveResetChanges.vue";

export default {
    props: {
        content: {
            required: true,
            type: Object
        },
        currencies: {
            required: true,
            type: Array
        },
        lastcurrency: {
            required: false,
            type: Number,
            default: 1
        }
    },
    components: {
        TableHeader,
        EmptyPlaceholder,
        Slider,
        SaveResetChanges
    },
    data() {
        return {
            header: [
                {
                    text: "Name",
                    tooltip: "The name of your mean of payment"
                },
                {
                    text: "Income",
                    tooltip: "Use in income"
                },
                {
                    text: "Outcome",
                    tooltip: "Use in outcome"
                },
                {
                    text: "Charts",
                    tooltip: "Mean of payment present on charts"
                }
            ],

            means: {},
            meansCopy: {},
            currentCurrency: 1,

            submitted: false
        }
    },
    methods: {
        save() {
            this.submitted = true;

            const toSubmit = [];

            for (let i in this.means) {
                this.means[i].forEach(item => {
                    if (item.show_on_charts) {
                        toSubmit.push(item.id);
                    }
                })
            }

            axios
                .patch("/webapi/bundles/charts/presence/means-of-payment", {
                    data: toSubmit
                })
				.then(response => {
					this.meansCopy = _.cloneDeep(this.means);
					this.submitted = false;
				})
				.catch(err => {
					console.log(err);
					this.submitted = false;
				})
        },
        reset() {
            this.means = _.cloneDeep(this.meansCopy);
        },
        refreshTooltip() {
            this.$nextTick(() => {
                $('[data-toggle="tooltip"]').tooltip()
            });
        }
    },
    beforeMount() {
        this.currentCurrency = this.lastcurrency;
        this.means = _.cloneDeep(this.content);
        this.meansCopy = _.cloneDeep(this.content);
    },
    mounted() {
        this.refreshTooltip();
    },
    updated() {
        this.refreshTooltip();
    }
}
</script>
