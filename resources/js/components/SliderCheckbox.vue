<template>
    <div :class="choice ? 'slider-choice' : 'slider-checkbox'">
        <label class="switch m-0">
            <input type="checkbox" :checked="value" @change="emitEvents" :disabled="disabled" :name="name">
            <span class="slider round"></span>
        </label>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                required: true,
                type: Boolean
            },
            disabled: {
                required: false,
                type: Boolean,
                default: false
            },
            name: {
                required: false,
                type: String,
                default: ""
            },
            choice: {
                type: Boolean,
                required: false,
                default: false
            }
        },
        methods: {
            emitEvents(event) {
                this.$emit("input", event.currentTarget.checked);
                this.$emit("htmlElement", event.currentTarget);
            }
        }
    }
</script>
