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
                Edit {{ type.charAt(0).toUpperCase() + type.slice(1) }}
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <div class="form-group row">
                    <label for="date" class="col-md-3 col-form-label text-md-right">Date</label>

                    <div class="col-md-7">
                        <input
                            type="date"
                            :class="[
                                'form-control',
                                invalidDate && 'is-invalid'
                            ]"
                            v-model="ioAttributes.date"
                        >

                        <span class="invalid-feedback" role="alert" v-if="invalidDate">
                            <strong>This date is invalid</strong>
                        </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="title" class="col-md-3 col-form-label text-md-right">Title</label>

                    <div class="col-md-7">
                        <input
                            type="text"
                            :class="[
                                'form-control',
                                invalidTitle && 'is-invalid'
                            ]"
                            v-model="ioAttributes.title"
                            autocomplete="off"
                            placeholder="Your title here"
                            list="titleList"
                        >
                        <datalist id="titleList">
                            <option v-for="(title, i) in titles" :key="i">{{ title }}</option>
                        </datalist>

                        <span class="invalid-feedback" role="alert" v-if="invalidTitle">
                            <strong>This title is invalid</strong>
                        </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="amount" class="col-md-3 col-form-label text-md-right">Amount</label>

                    <div class="col-md-7">
                        <input
                            type="number"
                            :class="[
                                'form-control',
                                invalidAmount && 'is-invalid'
                            ]"
                            step="0.001"
                            v-model="ioAttributes.amount"
                            placeholder="Your amount here"
                        >

                        <span class="invalid-feedback" role="alert" v-if="invalidAmount">
                            <strong>This amount is invalid</strong>
                        </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="price" class="col-md-3 col-form-label text-md-right">Price</label>

                    <div class="col-md-4 col-sm-12">
                        <input
                            type="number"
                            step="0.01"
                            :class="[
                                'form-control',
                                invalidPrice && 'is-invalid'
                            ]"
                            placeholder="Your price here"
                            v-model="ioAttributes.price"
                        >

                        <span class="invalid-feedback" role="alert" v-if="invalidPrice">
                            <strong>This price is invalid</strong>
                        </span>
                    </div>

                    <div class="col-md-3 col-sm-12 mt-2 mt-md-0">
                        <select class="form-control" v-model="ioAttributes.currency_id">
                            <option
                                v-for="(currency, i) in currencies"
                                :key="i"
                                :value="currency['id']"
                            >{{ currency["ISO"] }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="value" class="col-md-3 col-form-label text-md-right">Value</label>

                    <div class="col-md-7">
                        <input type="number" class="form-control" disabled :value="Math.round(ioAttributes.price * ioAttributes.amount * 100) / 100">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-md-right">Category</label>

                    <div class="col-md-7">
                        <select class="form-control" v-model="ioAttributes.category_id">
                            <option
                                v-for="(category, i) in categories[ioAttributes.currency_id]"
                                :key="i"
                                :value="category['id']"
                            >{{ category['name'] }}</option>
                            <option value="null">N / A</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-md-right">Mean of payment</label>

                    <div class="col-md-7">
                        <select class="form-control" v-model="ioAttributes.mean_id">
                            <option
                                v-for="(mean, i) in means[ioAttributes.currency_id]"
                                :key="i"
                                :value="mean['id']"
                            >{{ mean['name'] }}</option>
                            <option value="null">N / A</option>
                        </select>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-4">
                        <button class="big-button-primary" @click="reset">
                            Reset changes
                        </button>
                    </div>

                    <div class="col-4">
                        <button class="big-button-success" @click="save">
                            Save changes
                        </button>
                    </div>

                    <div class="col-4">
                        <button class="big-button-danger" @click="destroy">
                            Delete
                        </button>
                    </div>
                </div>
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
export default {
    props: {
        type: String,
        ioid: String
    },
    data() {
        return {
            darkmode: false,
            ready: false,
            ioAttributes: {},
			ioAttributesCopy: {},
            currencies: [],
            categories: {},
            means: {},
            titles: [],
            firstEntryDate: ""
        }
    },
    computed: {
        invalidDate: function() {
            if (!!this.ioAttributes.date == false) {
                return true;
            }

            return new Date(this.firstEntryDate).getTime() > new Date(this.ioAttributes.date).getTime()
        },
        invalidTitle: function() {
            return this.ioAttributes.title.length == 0 || this.ioAttributes.title.length > 32
        },
        invalidAmount: function() {
            return parseFloat(this.ioAttributes.amount) != this.ioAttributes.amount
        },
        invalidPrice: function() {
            return parseFloat(this.ioAttributes.price) != this.ioAttributes.price
        },
    },
    methods: {
        reset: function() {
            this.ioAttributes = _.cloneDeep(this.ioAttributesCopy);
        },
        save: function() {
            axios
                .patch(`/webapi/${this.type}/edit`, this.ioAttributes)
                .then(response => {
                    this.ioAttributes = response.data.data;
                    this.ioAttributes.amount = Number(this.ioAttributes.amount);
                    this.ioAttributes.price = Number(this.ioAttributes.price);

                    console.table([this.ioAttributes, response.data.data])

                    this.ioAttributesCopy = _.cloneDeep(response.data.data);
                    this.ioAttributesCopy.amount = Number(this.ioAttributesCopy.amount);
                    this.ioAttributesCopy.price = Number(this.ioAttributesCopy.price);
                })
        },
        destroy: function() {
            axios
                .delete(`/webapi/${this.type}/delete/${this.ioid}`)
                .then(response => {
                    if (response.data.status == "success") {
                        window.location.href = "/income";
                    }
                });
        }
    },
    beforeMount() {
        this.darkmode = document.getElementById("sun-moon").innerHTML.includes("<i class=\"fas fa-sun\"></i>");
    },
    mounted() {
        axios
            .get(`/webapi/${this.type}/${this.ioid}`)
            .then(response => {
                this.ioAttributes = response.data.income;
                this.ioAttributes.amount = Number(this.ioAttributes.amount);
                this.ioAttributes.price = Number(this.ioAttributes.price);

                this.ioAttributesCopy = _.cloneDeep(response.data.income);
                this.ioAttributesCopy.amount = Number(this.ioAttributesCopy.amount);
                this.ioAttributesCopy.price = Number(this.ioAttributesCopy.price);
                this.currencies = response.data.currencies;
                this.categories = response.data.categories;
                this.means = response.data.means;
                this.titles = response.data.titles;
                this.firstEntryDate = response.data.firstEntryDate;

                this.ready = true;
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
}
</script>
