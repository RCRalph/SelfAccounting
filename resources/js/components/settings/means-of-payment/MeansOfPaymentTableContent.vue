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
                :key="componentKey"
                @change="updateComponentKey"
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
                :key="componentKey"
				@change="updateComponentKey"
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
                v-model="content.first_entry_amount"
                :key="componentKey"
                @change="updateComponentKey"
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
            componentKey: 0
        };
    },
    methods: {
        updateComponentKey: function() {
            this.componentKey++;
            this.$emit("update");
        }
    }
};
</script>
