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
                        >{{ currency.ISO }}</option
                    >
                </select>
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <Categories
                    :content="categories[currentCurrency]"
                    :darkmode="darkmode"
                    :key="componentKey"
                    @data-reset="categoriesReset"
                    @data-add="categoriesAdd"
                    @data-delete="categoriesDelete"
                    @data-save="categoriesSave"
                ></Categories>
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
import axios from "axios";

export default {
    components: {
        Categories
    },
    props: {
        darkmode: String
    },
    data() {
        return {
            currencies: [],
            categories: {},
            categoriesCopy: {},
            currentCurrency: 1,
            ready: false,
            componentKey: 0,
            response: [] //for debugging purposes only
        };
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
                start_date: null
            });
            this.componentKey++;
        },
        categoriesDelete: function(index) {
            this.categories[this.currentCurrency].splice(index, 1);
            this.componentKey++;
        },
        categoriesSave: function() {
            axios
                .post("/webapi/settings/categories", {
                    categories: this.categories
                })
                .then(response => {
                    this.categories = {..._.cloneDeep(response.data.categories)};
                    this.categoriesCopy = {..._.cloneDeep(response.data.categories)};
                    this.response = response;
                    this.componentKey++;
                });
        }
    },
    mounted() {
        axios.get("/webapi/settings", {}).then(response => {
            this.currencies = response.data.currencies;
            this.categories = {...response.data.categories};
            this.categoriesCopy = _.cloneDeep({...response.data.categories});
            this.ready = true;
        });
    }
};
</script>
