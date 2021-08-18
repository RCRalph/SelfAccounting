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
                Edit {{ type }}
            </div>

            <div class="d-flex" v-if="ready">
                <button class="big-button-primary" @click="goBack">
                    <div v-if="goBackClicked">
                        Go back
                    </div>

                    <span
                        v-else
                        class="spinner-border spinner-border-sm"
                        role="status"
                        aria-hidden="true"
                    ></span>
                </button>
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

                <DeleteSaveResetChanges
                    :disableAll="destroyed || submitted"
                    :disableSave="!canSave"
                    :spinnerDelete="destroyed"
                    :spinnerSave="submitted"
                    :deleteText="type"
                    @delete="destroy"
                    @save="submit"
                    @reset="reset"
                ></DeleteSaveResetChanges>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import Loading from "../Loading.vue";
import CreateForm from "./CreateFormComponent.vue";
import DeleteSaveResetChanges from "../DeleteSaveResetChanges.vue";

export default {
    props: ["type", "id"],
    components: {
        Loading,
        CreateForm,
        DeleteSaveResetChanges
    },
    data() {
        return {
            ready: false,
            submitted: false,
            destroyed: false,
            goBackClicked: false,

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
                category_id: null,
                mean_id: null
            },
            dataCopy: {}
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
                toNumber.amount <= 1e7 - 0.001 &&
                toNumber.amount > 0;

            const validPrice = !isNaN(toNumber.price) &&
                toNumber.price <= 1e11 - 0.01 &&
                toNumber.price > 0;

            return validDate && validTitle && validAmount && validPrice;
        }
    },
    methods: {
        submit() {
            this.submitted = true;

            return axios
                .patch(`/webapi/${this.type}/${this.id}/update`, {
                    data: [this.data]
                })
                .then(response => {
                    const data = response.data.data;

                    this.data = {
                        ...data,
                        amount: Number(data.amount),
                        price: Number(data.price)
                    };
                    this.dataCopy = _.cloneDeep(this.data);
                })
                .catch(err => {
                    console.error(err);
                    this.goBackClicked = false;
                })
                .finally(() => this.submitted = false)
        },
        reset() {
            this.data = _.cloneDeep(this.dataCopy);
        },
        destroy() {
            this.destroyed = true;

            axios
                .delete(`/webapi/${this.type}/${this.id}/delete`, {})
                .then(() => {
                    window.location.href = `/${this.type}`;
                })
                .catch(err => {
                    console.log(err);
                    this.destroyed = false;
                })
        },
        goBack() {
            this.goBackClicked = true;
            this.submit()
                .then(() => {
                    if (this.goBackClicked) {
                        window.location = `/${this.type}`;
                    }
                });
        }
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
                    price: Number(data.data.price)
                };

                this.dataCopy = _.cloneDeep(this.data);

                this.ready = true;
            });
    }
}
</script>
