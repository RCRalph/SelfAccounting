<template>
    <div class="row my-4">
        <div class="col-5 h5 text-end fw-bold mb-0" v-html="left"></div>

        <div class="col-2 d-flex justify-content-center align-items-center">
            <SliderCheckbox v-model="value" :choice="true"></SliderCheckbox>
        </div>

        <div class="col-5 h5 fw-bold mb-0" v-html="right"></div>
    </div>
</template>

<script>
import SliderCheckbox from "./SliderCheckbox.vue";

export default {
    props: {
        left: String,
        right: String,
        value: Boolean // false for left, true for right
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
