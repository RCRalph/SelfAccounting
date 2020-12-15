<template>
    <div class="slider-checkbox">
        <label class="switch m-0">
            <input type="checkbox" :checked="checked" @change="emitEvents" :disabled="disabled" :name="name">
            <span class="slider round"></span>
        </label>
    </div>
</template>

<script>
    export default {
        props: {
            checked: Boolean,
            disabled: Boolean,
            name: String
        },
        methods: {
            emitEvents(event) {
                this.$emit('input', event.currentTarget.checked);
                this.$emit('htmlElement', event.currentTarget);
            }
        }
    }
</script>
