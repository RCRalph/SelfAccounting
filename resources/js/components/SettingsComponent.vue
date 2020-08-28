<template>
    <div :class="darkmode ? 'dark-card' : 'card'">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-cog"></i>
                Settings
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
                <Categories
                    :content="categories[currentCurrency]"
                    :darkmode="darkmode"
                    :buttonsEnabled="axiosCategories && categoriesCanSave"
                    :key="componentKey"
                    @data-reset="categoriesReset"
                    @data-add="categoriesAdd"
                    @data-delete="categoriesDelete"
                    @data-save="categoriesSave"
                    @data-change="updateKey"
                ></Categories>

                <Means
                    class="mt-4"
                    :content="means[currentCurrency]"
                    :darkmode="darkmode"
                    :buttonsEnabled="axiosMeans && meansCanSave"
                    :key="componentKey + 1"
                    @data-reset="meansReset"
                    @data-add="meansAdd"
                    @data-delete="meansDelete"
                    @data-save="meansSave"
                    @data-change="updateKey"
                ></Means>
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
import Categories from "./settings/categories/Categories.vue";
import Means from "./settings/means-of-payment/MeansOfPayment.vue";

export default {
    components: {
        Categories,
        Means
    },
    data() {
        return {
            currencies: [],
            ready: false,
            currentCurrency: 1,
            darkmode: false,
            componentKey: 0,

            // Categories
            categories: {},
            categoriesCopy: {},
            axiosCategories: true,

            // Means of Payment
            means: {},
            meansCopy: {},
            axiosMeans: true
        };
    },
    computed: {
        categoriesCanSave: function() {
            this.componentKey;

            for (let currency in this.categories) {
                for (let i = 0; i < this.categories[currency].length; i++) {
                    const item = this.categories[currency][i];
                    const dateEmpty = !item.start_date || !item.end_date;
                    const validDates = dateEmpty ? true : new Date(item.start_date).getTime() <= new Date(item.end_date).getTime();
                    if (item.name.length > 32 || !item.name.length || !validDates) {
                        return false;
                    }
                }
            }

            return true;
        },
        meansCanSave: function() {
            this.componentKey;

            for (let currency in this.means) {
                for (let i = 0; i < this.means[currency].length; i++) {
                    const item = this.means[currency][i];
                    if (item.name.length > 32 || !item.name.length || !item.first_entry_date || parseFloat(item.first_entry_amount) != item.first_entry_amount) {
                        return false;
                    }
                }
            }

            return true;
        }
    },
    methods: {
        categoriesReset: function() {
            this.categories = _.cloneDeep(this.categoriesCopy);
            this.componentKey++;
        },
        categoriesAdd: function() {
            if (!(this.currentCurrency in this.categories)) {
                this.categories[this.currentCurrency] = [];
            }

            this.categories[this.currentCurrency].push({
                count_to_summary: false,
                currency_id: this.currentCurrency,
                end_date: null,
                id: null,
                income_category: false,
                name: "",
                outcome_category: false,
                show_on_charts: false,
                start_date: null,
            });
            this.componentKey++;
        },
        categoriesDelete: function(index) {
            this.categories[this.currentCurrency].splice(index, 1);
            this.componentKey++;
        },
        categoriesSave: function() {
            this.axiosCategories = false;
            axios
                .post("/webapi/settings/categories", {
                    categories: this.categories
                })
                .then(response => {
                    this.categories = {..._.cloneDeep(response.data.categories)};
                    this.categoriesCopy = {..._.cloneDeep(response.data.categories)};
                    this.componentKey++;
                    this.axiosCategories = true;
                })
                .catch(() => {
                    this.axiosCategories = true;
                    this.componentKey++;
                })
        },
        meansReset: function() {
            this.means = _.cloneDeep(this.meansCopy);
            this.componentKey++;
        },
        meansAdd: function() {
            if (!(this.currentCurrency in this.means)) {
                this.means[this.currentCurrency] = [];
            }

            this.means[this.currentCurrency].push({
                count_to_summary: false,
                currency_id: this.currentCurrency,
                first_entry_date: null,
                first_entry_amount: 0,
                id: null,
                income_mean: false,
                name: "",
                outcome_mean: false,
                show_on_charts: false
            });
            this.componentKey++;
        },
        meansDelete: function(index) {
            this.means[this.currentCurrency].splice(index, 1);
            this.componentKey++;
        },
        meansSave: function() {
            this.axiosMeans = false;
            axios
                .post("/webapi/settings/means", {
                    means: this.means
                })
                .then(response => {
                    this.means = {..._.cloneDeep(response.data.means)};
                    this.meansCopy = {..._.cloneDeep(response.data.means)};
                    this.componentKey++;
                    this.axiosMeans = true;
                })
                .catch(() => {
                    this.axiosMeans = true;
                    this.componentKey++;
                })
        },
        updateKey: function() {
            this.componentKey++;
        }
    },
    beforeMount() {
        this.darkmode = document.getElementById("sun-moon").innerHTML.includes("<i class=\"fas fa-sun\"></i>");
    },
    mounted() {
        axios
            .get("/webapi/settings", {})
            .then(response => {
                this.currencies = response.data.currencies;

                // Categories
                this.categories = {...response.data.categories};
                this.categoriesCopy = _.cloneDeep({...response.data.categories});

                // Means of Payment
                this.means = {...response.data.means};
                this.meansCopy = _.cloneDeep({...response.data.means});

                this.ready = true;

                this.$nextTick(() => {
                    $('[data-toggle="tooltip"]').tooltip()
                });
            });
    },
    beforeUpdate() {
        this.darkmode = document.getElementById("sun-moon").innerHTML.includes("<i class=\"fas fa-sun\"></i>");
    },
    updated() {
        this.$nextTick(() => {
            $('[data-toggle="tooltip"]').tooltip()
        });
    }
};
</script>
