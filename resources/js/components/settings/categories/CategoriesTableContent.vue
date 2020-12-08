<template>
    <tr :i="index">
        <td>
            <input
                type="text"
                :class="[
                    'form-text',
                    (!content.name.length || content.name.length > 32) && 'is-invalid',
                ]"
                placeholder="Name"
                v-model="content.name"
                maxlength="32"
                :key="componentKey"
                @change="updateComponentKey"
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
                @input="updateComponentKey"
            ></Slider>
        </td>
        <td>
            <input
                type="date"
                :class="[
                    'form-date',
                    !correctDates && 'is-invalid',
                ]"
                :disabled="!content.count_to_summary"
                v-model="content.start_date"
                :key="componentKey"
                @change="updateComponentKey"
            />
        </td>
        <td>
            <input
                type="date"
                :class="[
                    'form-date',
                    !correctDates && 'is-invalid'
                ]"
                :disabled="!content.count_to_summary"
                v-model="content.end_date"
                :key="componentKey"
                @change="updateComponentKey"
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
            componentKey: 0
        };
    },
    methods: {
        updateComponentKey() {
            this.componentKey++;
            this.$emit("update");
        }
    },
    computed: {
        correctDates() {
            this.componentKey;
            const dateEmpty = !this.content.start_date || !this.content.end_date;
            return dateEmpty ? true : new Date(this.content.start_date).getTime() <= new Date(this.content.end_date).getTime();
        }
    }
};
</script>
