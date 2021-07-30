<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-table"></i>
                Columns to show
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table-themed responsive-table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Title</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Price</th>
                            <th scope="col">Value</th>
                            <th scope="col">Category</th>
                            <th scope="col">Mean of payment</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>
                                <SliderCheckbox
                                    v-model="value.date"
                                ></SliderCheckbox>
                            </td>
                            <td>
                                <SliderCheckbox
                                    v-model="value.title"
                                ></SliderCheckbox>
                            </td>
                            <td>
                                <SliderCheckbox
                                    v-model="value.amount"
                                ></SliderCheckbox>
                            </td>
                            <td>
                                <SliderCheckbox
                                    v-model="value.price"
                                ></SliderCheckbox>
                            </td>
                            <td>
                                <SliderCheckbox
                                    v-model="value.value"
                                ></SliderCheckbox>
                            </td>
                            <td>
                                <SliderCheckbox
                                    v-model="value.category"
                                ></SliderCheckbox>
                            </td>
                            <td>
                                <SliderCheckbox
                                    v-model="value.mean"
                                ></SliderCheckbox>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import SliderCheckbox from "../../../components/SliderCheckbox.vue"

export default {
    props: {
        value: {
            type: Object,
            required: true
        }
    },
    components: {
        SliderCheckbox
    },
    watch: {
        value() {
            this.$emit("input", this.value);
        }
    }
}
</script>
