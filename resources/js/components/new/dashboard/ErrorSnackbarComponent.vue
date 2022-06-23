<template>
    <v-snackbar v-model="value" color="error" bottom>
        An error occured, please try again...

        <template v-slot:action="{ attrs }">
            <v-btn
                text
                v-bind="attrs"
                @click="value = false"
            >
                Close
            </v-btn>
        </template>
    </v-snackbar>
</template>

<script>
export default {
    props: {
        value: Boolean
    },
    watch: {
        value() {
            this.$emit("input", this.value);
        }
    }
}
</script>
