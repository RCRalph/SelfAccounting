<template>
    <div>
        <InputGroup
            name="amount"
            type="number"
            v-model="value.amount"
            step="0.001"
            placeholder="Your amount here..."
            :invalid="changed.amount && !validAmount"
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
                    @input="changed.price = true"
                >

                <span class="invalid-feedback" role="alert" v-if="changed.price && !validPrice">
                    <strong>Price is invalid</strong>
                </span>
            </div>

            <div class="col-md-3 col-sm-12 mt-2 mt-md-0">
                <select class="form-control" v-model="value.currency_id" @change="currencyChange">
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
            :value="`${Math.round(value.amount * value.price * 100) / 100} ${currencies[value.currency_id - 1].ISO}`"
            :disabled="true"
        ></InputGroup>

        <InputGroup
            type="select"
            name="category"
            :selectOptions="categories[value.currency_id]"
            optionValueKey="id"
            optionTextKey="name"
            v-model="value.category_id"
            nullValue="N/A"
        ></InputGroup>

        <InputGroup
            type="select"
            name="mean of payment"
            :selectOptions="means[value.currency_id]"
            optionValueKey="id"
            optionTextKey="name"
            :invalid="samemeans"
            v-model="value.mean_id"
            nullValue="N/A"
        ></InputGroup>
    </div>
</template>

<script>
import InputGroup from "../../InputGroup.vue";

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
        samemeans: {
            required: true,
            type: Boolean
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
            this.value.category_id = null;
            this.value.mean_id = null;
        }
    }
}
</script>
