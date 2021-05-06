<template>
    <div>
        <InputGroup
            name="report title"
            type="text"
            v-model="value.title"
            maxlength="64"
            placeholder="Your title here..."
            :invalid="!validTitle"
            @input="changed.title = true"
        ></InputGroup>

        <hr class="hr-dashed">

        <SliderChoice
            left="Outcome addition"
            right="Income addition"
            v-model="value.income_addition"
        ></SliderChoice>

        <SliderChoice
            left="Sort dates ascending"
            right="Sort dates descending"
            v-model="value.sort_dates_desc"
        ></SliderChoice>
    </div>
</template>

<script>
import InputGroup from "../../../components/InputGroup.vue";
import SliderChoice from "../../../components/SliderChoice.vue";

export default {
    props: {
        value: {
            type: Object,
            required: true
        }
    },
    components: {
        InputGroup,
        SliderChoice
    },
    data() {
        return {
            changed: {
                title: false
            }
        }
    },
    computed: {
        validTitle() {
            if (!this.changed.title) {
                return true;
            }

            return typeof this.value.title == "string" && this.value.title.length > 0 && this.value.title.length <= 64;
        }
    },
    watch: {
        value() {
            this.$emit("input", this.value);
        }
    }
}
</script>
