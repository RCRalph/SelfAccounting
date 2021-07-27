<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fab fa-buffer"></i>
                Categories
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
                            v-for="category in categories[currentCurrency]"
                            :key="category.id"
                        >
                            <th scope="row">
                                {{ category.name }}
                            </th>

                            <td>
                                <i :class="[
                                    'fas',
                                    'h3',
                                    'my-auto',
                                    category.income_category ? 'fa-check-square' : 'fa-times-circle',
                                    category.income_category ? 'text-success' : 'text-danger'
                                ]"></i>
                            </td>

                            <td>
                                <i :class="[
                                    'fas',
                                    'h3',
                                    'my-auto',
                                    category.outcome_category ? 'fa-check-square' : 'fa-times-circle',
                                    category.outcome_category ? 'text-success' : 'text-danger'
                                ]"></i>
                            </td>

                            <td>
                                <Slider
                                    v-model="category.show_on_charts"
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
                    tooltip: "The name of your category"
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
                    tooltip: "Category present on charts"
                }
            ],

            categories: {},
            categoriesCopy: {},
            currentCurrency: 1,

            submitted: false
        }
    },
    methods: {
        save() {
            this.submitted = true;

            const toSubmit = [];

            for (let i in this.categories) {
                this.categories[i].forEach(item => {
                    if (item.show_on_charts) {
                        toSubmit.push(item.id);
                    }
                })
            }

            axios
                .patch("/webapi/bundles/charts/presence/categories", {
                    data: toSubmit
                })
				.then(response => {
					this.categoriesCopy = _.cloneDeep(this.categories);
					this.submitted = false;
				})
				.catch(err => {
					console.log(err);
					this.submitted = false;
				})
        },
        reset() {
            this.categories = _.cloneDeep(this.categoriesCopy);
        },
        refreshTooltip() {
            this.$nextTick(() => {
                $('[data-toggle="tooltip"]').tooltip()
            });
        }
    },
    beforeMount() {
        this.currentCurrency = this.lastcurrency;
        this.categories = _.cloneDeep(this.content);
        this.categoriesCopy = _.cloneDeep(this.content);
    },
    mounted() {
        this.refreshTooltip();
    },
    updated() {
        this.refreshTooltip();
    }
}
</script>
