<template>
    <div>
        <InputGroup
            name="date"
            type="date"
            v-model="value.date"
            :min="minDate"
            :invalid="changed.date && !validDate"
            :disabled="common && common.hasOwnProperty('date')"
            @input="changed.date = true"
        ></InputGroup>

        <InputGroup
            name="title"
            v-model="value.title"
            datalistName="titles"
            :datalist="titles"
            maxlength="64"
            placeholder="Your title here..."
            :invalid="changed.title && !validTitle"
            :disabled="common && common.hasOwnProperty('title')"
            @input="changed.title = true"
        ></InputGroup>

        <InputGroup
            name="amount"
            type="number"
            v-model="value.amount"
            step="0.001"
            placeholder="Your amount here..."
            :invalid="changed.amount && !validAmount"
            :disabled="common && common.hasOwnProperty('amount')"
            @input="changed.amount = true"
        ></InputGroup>

        <!-- Price and currency -->
        <div class="form-group row">
            <div class="col-md-4 d-flex justify-content-md-end justify-content-start align-items-center">
                <div class="h5 font-weight-bold m-md-0">Price</div>
            </div>

            <div class="col-md-4 col-sm-12">
                <input
                    type="number"
                    step="0.01"
                    :class="[
                        'form-control',
                        changed.price && !validPrice && 'is-invalid'
                    ]"
                    placeholder="Your price here..."
                    v-model="value.price"
                    :disabled="common && common.hasOwnProperty('price')"
                    @input="changed.price = true"
                >

                <span class="invalid-feedback" role="alert" v-if="!validPrice">
                    <strong>Price is invalid</strong>
                </span>
            </div>

            <div class="col-md-3 col-sm-12 mt-2 mt-md-0">
                <select class="form-control" v-model="value.currency_id" @change="currencyChange" :disabled="common && common.hasOwnProperty('currency_id')">
                    <option
                        v-for="(currency, i) in currencies"
                        :key="i"
                        :value="currency.id"
                    >
                        {{ currency.ISO }}
                    </option>
                </select>
            </div>
        </div>

        <InputGroup
            name="value"
            :value="Math.round(value.amount * value.price * 100) / 100"
            :disabled="true"
        ></InputGroup>

        <InputGroup
            type="select"
            name="category"
            :selectOptions="categories[value.currency_id]"
            optionValueKey="id"
            optionTextKey="name"
            v-model="value.category_id"
            :disabled="common && common.hasOwnProperty('category_id')"
        ></InputGroup>

        <InputGroup
            type="select"
            name="mean of payment"
            :selectOptions="means[value.currency_id]"
            optionValueKey="id"
            optionTextKey="name"
            v-model="value.mean_id"
            :disabled="common && common.hasOwnProperty('mean_id')"
        ></InputGroup>
    </div>
</template>

<script>
import InputGroup from "../InputGroup.vue";

export default {
    props: {
        value: {
            required: true,
            type: Object
        },
        currencies: {
            required: true,
            type: Array
        },
        categories: {
            required: true,
            type: Object
        },
        means: {
            required: true,
            type: Object
        },
        common: {
            required: false,
            type: Object
        },
        titles: {
            required: false,
            type: Array
        }
    },
    components: {
        InputGroup
    },
    data() {
        return {
            changed: {
                date: false,
                title: false,
                amount: false,
                price: false
            }
        }
    },
    computed: {
        minDate() {
			const currentMean = this.means[this.value.currency_id][this.value.mean_id];
			return currentMean.first_entry_date || "1970-01-01";
        },
        validDate() {
            const date = this.value.date;
            return date !== "" && !isNaN(Date.parse(date)) && new Date(date) >= new Date(this.minDate).getTime()
        },
        validTitle() {
            const title = this.value.title;
            return title.length && title.length <= 64;
        },
        validAmount() {
            const amount = Number(this.value.amount);
            return !isNaN(amount) && amount < 1e6 && amount > 0;
        },
        validPrice() {
            const price = Number(this.value.price);
            return !isNaN(price) & price < 1e11 && price > 0;
        }
    },
    methods: {
        currencyChange() {
            this.value.category_id = 0;
            this.value.mean_id = 0;
        }
    }
}
</script>
