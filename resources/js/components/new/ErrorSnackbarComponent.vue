<template>
    <v-snackbar :value="value" @input="$emit('input', value)" color="error" bottom>
        An error occured, please try again...

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
        }
    }
}
</script>
