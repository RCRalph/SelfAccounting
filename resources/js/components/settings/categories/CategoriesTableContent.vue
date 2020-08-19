<template>
    <tr :i="index">
        <td>
            <input
                type="text"
                class="form-text"
                placeholder="Name of the category"
                v-model="content.name"
            />
        </td>
        <td>
            <Slider
                :checked="content.income_category"
                v-model="content.income_category"
            ></Slider>
        </td>
        <td>
            <Slider
                :checked="content.outcome_category"
                v-model="content.outcome_category"
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
                class="form-date"
                :disabled="!content.count_to_summary"
                v-model="content.start_date"
                :key="dateKey"
            />
        </td>
        <td>
            <input
                type="date"
                class="form-date"
                :disabled="!content.count_to_summary"
                v-model="content.end_date"
                :key="dateKey"
            />
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
