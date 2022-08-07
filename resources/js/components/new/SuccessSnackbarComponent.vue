<template>
    <v-snackbar
        :value="value"
        @input="$emit('input', value)"
        color="success"
        bottom
    >
        Successfully {{ thing }}!

        <template v-slot:action="{ attrs }">
            <v-btn
                text
                v-bind="attrs"
                @click="$emit('input', false)"
            >
                Close
            </v-btn>
        </template>
    </v-snackbar>
</template>

<script>
export default {
    props: {
        value: {
            required: true,
            type: Boolean
        },
        thing: {
            required: true,
            type: String
        }
    },
    watch: {
        value() {
            if (this.value) {
                setTimeout(() => this.$emit("input", false), 2000);
            }
        }
    }
}
</script>
