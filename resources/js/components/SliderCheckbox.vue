<template>
    <label class="switch m-0">
        <input type="checkbox" :checked="checked" @change="$emit('input', $event.currentTarget.checked)">
        <span class="slider round"></span>
    </label>
</template>

<script>
    export default {
        props: {
            checked: Boolean
        }
    }
</script>
