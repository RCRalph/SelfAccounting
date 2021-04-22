<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i
                    :class="[
                        'fas',
                        type == 'income' ? 'fa-sign-in-alt' : 'fa-sign-out-alt'
                    ]"
                ></i>
                Add single {{ type }}
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <CreateForm
                    v-model="data"
                    :currencies="currencies"
                    :categories="categories"
                    :means="means"
                    :titles="titles"
                ></CreateForm>

                <hr class="hr">

                <div class="row">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <button
                            type="button"
                            class="big-button-success"
                            @click="submit"
                            :disabled="submitted || !canSubmit"
                        >
                            <div v-if="!submitted">Submit</div>

                            <span
                                v-else
                                class="spinner-border spinner-border-sm"
                                role="status"
                                aria-hidden="true"
                            ></span>
                        </button>
                    </div>
                </div>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import Loading from "../Loading.vue";
import CreateForm from "./CreateFormComponent.vue";

export default {
    props: ["type"],
    components: {
        Loading,
        CreateForm
    },
    data() {
        return {
            ready: false,
            submitted: false,

            currencies: {},
            categories: {},
            means: {},
            titles: [],

            data: {
                date: "",
                title: "",
                amount: "",
                price: "",
                currency_id: "",
                category_id: "",
                mean_id: ""
            }
        }
    },
    computed: {
        minDate() {
            const meansForCurrency = this.means[this.data.currency_id];
            if (meansForCurrency == undefined) {
                return "1970-01-01";
            }
			const currentMean = meansForCurrency.filter(item => item.id == this.data.mean_id);
			return currentMean.length ? currentMean[0].first_entry_date : "1970-01-01";
        },
        canSubmit() {
            const validDate = this.data.date !== "" &&
                !isNaN(Date.parse(this.data.date)) &&
                new Date(this.data.date) >= new Date(this.minDate).getTime();

            const validTitle = this.data.title.length &&
                this.data.title.length <= 64;

            const toNumber = {
                amount: Number(this.data.amount),
                price: Number(this.data.price)
            }

            const validAmount = !isNaN(toNumber.amount) &&
                toNumber.amount <= 1e6 &&
                toNumber.amount > 0;

            const validPrice = !isNaN(toNumber.price) &&
                toNumber.price <= 1e11 &&
                toNumber.price > 0;

            return validDate && validTitle && validAmount && validPrice;
        }
    },
    methods: {
        submit() {
            this.submitted = true;

            axios
                .post(`/webapi/${this.type}/store`, {
                    data: [this.data]
                })
                .then(response => {
                    window.location.href = `/${this.type}`
                })
                .catch(err => {
                    console.log(err);
                    this.submitted = false;
                })
        }
    },
    mounted() {
        axios
            .get(`/webapi/${this.type}/create`, {})
            .then(response => {
                const data = response.data.data;

                this.currencies = data.currencies;
                this.categories = data.categories;
                this.means = data.means;
                this.titles = data.titles;

                this.data.currency_id = data.last.currency;
                this.data.category_id = data.last.category || 0;
                this.data.mean_id = data.last.mean || 0;

                this.data.date = (new Date(this.minDate).getTime() > new Date().getTime() ?
                    new Date(this.minDate) : new Date())
                    .toISOString().split("T")[0];

                this.ready = true;
            });
    }
}
</script>
