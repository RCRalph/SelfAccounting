<template>
    <div>
        <InputGroup
            type="select"
            name="query data"
            :selectOptions="queryTypes"
            :optionKeyAndValue="true"
            v-model="value.query_data"
            :capitalizeOptions="true"
        ></InputGroup>

        <InputGroup
            type="date"
            name="minimal date"
            v-model="value.min_date"
            :max="value.max_date"
            :invalid="changed.min_date && !validMinDate"
            @input="changed.min_date = true"
        ></InputGroup>

        <InputGroup
            type="date"
            name="maximal date"
            v-model="value.max_date"
            :min="value.min_date"
            :invalid="changed.max_date && !validMaxDate"
            @input="changed.max_date = true"
        ></InputGroup>

        <InputGroup
            type="select"
            name="title"
            :selectOptions="titles"
            :optionKeyAndValue="true"
            nullValue="All titles"
            v-model="value.title"
        ></InputGroup>

        <InputGroup
            name="minimal amount"
            type="number"
            v-model="value.min_amount"
            step="0.001"
            placeholder="Leave blank if not set"
            :invalid="changed.min_amount && !validMinAmount"
            @input="changed.min_amount = true"
        ></InputGroup>

        <InputGroup
            name="maximal amount"
            type="number"
            v-model="value.max_amount"
            step="0.001"
            placeholder="Leave blank if not set"
            :invalid="changed.max_amount && !validMaxAmount"
            @input="changed.max_amount = true"
        ></InputGroup>

		<InputGroup
            name="minimal price"
            type="number"
            v-model="value.min_price"
            step="0.001"
            placeholder="Leave blank if not set"
            :invalid="changed.min_price && !validMinPrice"
            @input="changed.min_price = true"
        ></InputGroup>

        <InputGroup
            name="maximal price"
            type="number"
            v-model="value.max_price"
            step="0.001"
            placeholder="Leave blank if not set"
            :invalid="changed.max_price && !validMaxPrice"
            @input="changed.max_price = true"
        ></InputGroup>

        <InputGroup
            name="currency"
            type="select"
            v-model="value.currency_id"
            :selectOptions="currencies"
            optionValueKey="id"
            optionTextKey="ISO"
            nullValue="All currencies"
            @input="resetCategoriesAndMeans"
        ></InputGroup>

        <InputGroup
            name="category"
            type="select"
            v-model="value.category_id"
            :selectOptions="categories[value.currency_id]"
            optionValueKey="id"
            optionTextKey="name"
            nullValue="All categories"
            :disabled="!value.currency_id"
        ></InputGroup>

        <InputGroup
            name="mean of payment"
            type="select"
            v-model="value.mean_id"
            :selectOptions="means[value.currency_id]"
            optionValueKey="id"
            optionTextKey="name"
            nullValue="All means of payment"
            :disabled="!value.currency_id"
        ></InputGroup>
    </div>
</template>

<script>
import InputGroup from "../../../components/InputGroup.vue";

export default {
    props: {
        value: Object,
        queryTypes: Array,
        titles: Array,
        currencies: Array,
        categories: Object,
        means: Object
    },
    components: {
        InputGroup
    },
    data() {
        return {
            changed: {
                min_date: false,
                max_date: false,
                min_amount: false,
                max_amount: false,
				min_price: false,
				max_price: false
            }
        }
    },
    computed: {
        validMinDate() {
            const date = this.value.min_date;
            return date === "" || !isNaN(Date.parse(date)) && (!this.value.max_date || new Date(date).getTime() <= new Date(this.value.max_date).getTime())
        },
        validMaxDate() {
            const date = this.value.max_date;
            return date === "" || !isNaN(Date.parse(date)) && (!this.value.min_date || new Date(date).getTime() >= new Date(this.value.min_date).getTime())
        },
        validMinAmount() {
            const amount = Number(this.value.min_amount);
            return this.value.min_amount === "" || !isNaN(amount) && (!this.value.max_amount || amount <= this.value.max_amount) && amount <= 1e7 - 0.001 && amount >= 0;
        },
        validMaxAmount() {
            const amount = Number(this.value.max_amount);
            return this.value.max_amount === "" || !isNaN(amount) && (!this.value.min_amount || amount >= this.value.min_amount) && amount <= 1e7 - 0.001 && amount >= 0;
        },
		validMinPrice() {
			const price = Number(this.value.min_price);
			return this.value.min_price === "" || !isNaN(price) && (!this.value.max_price || price <= this.value.max_price) && price <= 1e11 - 0.01 && price >= 0;
		},
		validMaxPrice() {
			const price = Number(this.value.max_price);
			return this.value.max_price === "" || !isNaN(price) && (!this.value.min_price || price >= this.value.min_price) && price <= 1e11 - 0.01 && price >= 0;
		}
    },
    methods: {
        resetCategoriesAndMeans() {
            this.value.category_id = null;
            this.value.mean_id = null;
        }
    }
}
</script>
