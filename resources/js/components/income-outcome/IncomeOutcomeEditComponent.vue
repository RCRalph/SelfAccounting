<template>
    <div :class="darkmode ? 'dark-card' : 'card'">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i
                    :class="[
                        'fas',
                        type == 'income' ? 'fa-sign-in-alt' : 'fa-sign-out-alt'
                    ]"
                ></i>
                Edit {{ type }}
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

                <hr :class="darkmode ? 'hr-darkmode' : 'hr-lightmode'">

                <div class="row">
                    <div class="col-12 col-sm-4">
                        <button
                            type="button"
                            class="big-button-danger"
                            @click="destroy"
                            :disabled="destroyed || submitted"
                        >
                            <div v-if="!destroyed">
                                Delete {{ type }}
                            </div>

                            <span
                                v-else
                                class="spinner-border spinner-border-sm"
                                role="status"
                                aria-hidden="true"
                            ></span>
                        </button>
                    </div>

                    <div class="col-12 col-sm-4">
                        <button
                            type="button"
                            class="big-button-primary"
                            @click="reset"
                            :disabled="submitted || destroyed "
                        >
                            Reset changes
                        </button>
                    </div>

                    <div class="col-12 col-sm-4">
                        <button
                            type="button"
                            class="big-button-success"
                            @click="submit"
                            :disabled="submitted || destroyed || !canSave"
                        >
                            <div v-if="!submitted">Save changes</div>

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
    props: ["type", "id"],
    components: {
        Loading,
        CreateForm
    },
    data() {
        return {
            darkmode: false,
            ready: false,
            submitted: false,
            destroyed: false,

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
            },
            dataCopy: {}
        }
    },
    computed: {
        minDate() {
			const currentMean = this.means[this.data.currency_id][this.data.mean_id];
			return currentMean.first_entry_date || "1970-01-01";
        },
        canSave() {
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
                .patch(`/webapi/${this.type}/${this.id}/update`, {
                    data: [this.data]
                })
                .then(response => {
                    const data = response.data.data;

                    this.data = {
                        ...data,
                        amount: Number(data.amount),
                        price: Number(data.price),
                        category_id: data.category_id || 0,
                        mean_id: data.mean_id || 0
                    };
                    this.dataCopy = _.cloneDeep(this.data);

                    this.submitted = false;
                })
                .catch(err => {
                    console.log(err);
                    this.submitted = false;
                })
        },
        reset() {
            this.data = _.cloneDeep(this.dataCopy);
        },
        destroy() {
            this.destroyed = true;

            axios
                .delete(`/webapi/${this.type}/${this.id}/delete`, {})
                .then(response => {
                    window.location.href = `/${this.type}`;
                })
                .catch(err => {
                    console.log(err);
                    this.destroyed = false;
                })
        }
    },
    beforeMount() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    },
    mounted() {
        axios
            .get(`/webapi/${this.type}/${this.id}`, {})
            .then(response => {
                const data = response.data.data;

                this.currencies = data.currencies;
                this.categories = data.categories;
                this.means = data.means;
                this.titles = data.titles;

                this.data = {
                    ...data.data,
                    amount: Number(data.data.amount),
                    price: Number(data.data.price),
                    category_id: data.data.category_id || 0,
                    mean_id: data.data.mean_id || 0
                };

                this.dataCopy = _.cloneDeep(this.data);

                this.ready = true;
            });
    },
    beforeUpdate() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    }
}
</script>
