<template>
    <v-dialog :value="value" @input="$emit('input', value)" max-width="550" persistent>
        <v-card>
            <div class="d-flex justify-center pt-4">
                <v-avatar size="113">
                    <img src="favicon.ico" alt="SelfAccounting">
                </v-avatar>
            </div>

            <v-card-title class="justify-center text-h5">
                <div style="word-break: break-word" class="text-center">
                Your SelfAccounting Premium has expired.
                </div>
            </v-card-title>

            <v-card-text class="text-center pb-1">
                To renew your Premium subscription, click the button below:
            </v-card-text>

            <v-card-actions class="d-flex justify-space-around flex-wrap flex-md-row flex-column-reverse">
                <v-btn
                    outlined
                    class="mx-0 my-1"
                    width="80"
                    :block="$vuetify.breakpoint.smAndDown"
                    @click="$emit('input', false)"
                >Close</v-btn>

                <v-btn
                    outlined
                    class="mx-0 my-1"
                    width="80"
                    :block="$vuetify.breakpoint.smAndDown"
                    @click="$emit('input', false)"
                    color="success"
                    disabled
                >Renew</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
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
