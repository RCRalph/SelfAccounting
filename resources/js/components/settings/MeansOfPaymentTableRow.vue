<template>
    <tr :i="index">
        <td>
            <input
                type="text"
                placeholder="Name..."
                v-model="value.name"
                maxlength="32"
                :class="[
                    'form-text',
                    !validName && 'is-invalid'
                ]"
            >
        </td>

        <td>
            <Slider
                v-model="value.income_mean"
            ></Slider>
        </td>

        <td>
            <Slider
                v-model="value.outcome_mean"
            ></Slider>
        </td>

        <td>
            <Slider
                v-model="value.count_to_summary"
            ></Slider>
        </td>

        <td>
            <input
                type="date"
                v-model="value.first_entry_date"
                :class="[
                    'form-date',
                    !validDate && 'is-invalid'
                ]"
            >
        </td>

        <td>
            <input
                type="number"
                step="0.01"
                v-model="value.first_entry_amount"
                :class="[
                    'form-date',
                    !validAmount && 'is-invalid'
                ]"
            >
        </td>

        <td class="trashbin" @click="$emit('delete')">
            <i class="fas fa-trash"></i>
        </td>
    </tr>
</template>

<script>
import Slider from "../SliderCheckbox.vue";

export default {
    props: {
        value: {
            required: true,
            type: Object
        },
        index: {
            required: true,
            type: Number
        }
    },
    components: {
        Slider
    },
    computed: {
        validName() {
            const name = this.value.name;
            return name && name.length <= 32;
        },
        validDate() {
            const date = this.value.first_entry_date;
            return date && (new Date(date).getTime() <= new Date(this.value.date_limit).getTime() ||
                this.value.date_limit === null);
        },
        validAmount() {
            const amount = Number(this.value.first_entry_amount);

            return this.value.first_entry_amount !== "" && Math.abs(amount) <= 1e11 - 0.01;
        }
    }
}
</script>
