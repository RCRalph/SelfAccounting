<template>
    <tr :i="index">
        <td>
            <input
                type="text"
                :class="[
                    'form-text',
                    (!content.name.length || content.name.length > 32) && 'border-danger',
                    (!content.name.length || content.name.length > 32) && 'border-large'
                ]"
                placeholder="Name"
                v-model="content.name"
                maxlength="32"
            >
        <td>
            <Slider
                :checked="content.income_mean"
                v-model="content.income_mean"
            ></Slider>
        </td>
        <td>
            <Slider
                :checked="content.outcome_mean"
                v-model="content.outcome_mean"
            ></Slider>
        </td>
        <td>
            <Slider
                :checked="content.show_on_charts"
                v-model="content.show_on_charts"
            ></Slider>
        </td>
        <td>
            <Slider
                :checked="content.count_to_summary"
                v-model="content.count_to_summary"
                @input="updateDates"
            ></Slider>
        </td>
        <td>
            <input
                type="date"
                :class="[
                    'form-date',
                    !content.first_entry_date && 'border-danger',
                    !content.first_entry_date && 'border-large'
                ]"
                v-model="content.first_entry_date"
            >
        </td>
        <td>
            <input
                type="number"
                :class="[
                    'form-text',
                    parseFloat(content.first_entry_amount) != content.first_entry_amount && 'border-danger',
                    parseFloat(content.first_entry_amount) != content.first_entry_amount && 'border-large'
                ]"
                step=".01"
                placeholder="0.00"
                v-model="content.first_entry_amount"
            >
        </td>
        <td class="trashbin" @click="$emit('delete', index)">
            <i class="fas fa-trash"></i>
        </td>
    </tr>
</template>

<script>
import Slider from "../../SliderCheckbox.vue";

export default {
    props: {
        content: Object,
        index: Number
    },
    components: {
        Slider
    },
    data() {
        return {
            dateKey: 0
        };
    },
    methods: {
        updateDates: function() {
            this.dateKey++;
        }
    }
};
</script>
