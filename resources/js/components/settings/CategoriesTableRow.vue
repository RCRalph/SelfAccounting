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
                v-model="value.income_category"
            ></Slider>
        </td>

        <td>
            <Slider
                v-model="value.outcome_category"
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
                :disabled="!value.count_to_summary"
                v-model="value.start_date"
                :class="[
                    'form-date',
                    !validDates && 'is-invalid'
                ]"
            >
        </td>

        <td>
            <input
                type="date"
                :disabled="!value.count_to_summary"
                v-model="value.end_date"
                :class="[
                    'form-date',
                    !validDates && 'is-invalid'
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
        validDates() {
            const dateEmpty = !(this.value.start_date && this.value.end_date);
            return dateEmpty ||
                new Date(this.value.start_date).getTime() <= new Date(this.value.end_date).getTime()
        }
    }
}
</script>
