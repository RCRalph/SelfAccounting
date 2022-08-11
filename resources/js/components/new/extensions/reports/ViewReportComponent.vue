<template>
    <div v-if="ready">
        <v-row>
            <v-col xl="4" cols="12">
                <v-card class="mb-4" height="100%">
                    <v-card-title class="font-weight-bold justify-center text-h5">Overview</v-card-title>

                    <v-card-text>

                    </v-card-text>
                </v-card>
            </v-col>

            <v-col xl="8" cols="12" height="100%">
                <v-card class="sticky-panel loading-height">
                    <v-card-title class="font-weight-bold justify-center text-h5">Report content</v-card-title>

                    <v-card-text>

                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>

    <v-overlay v-else :value="true" opacity="1" absolute>
        <v-progress-circular
            indeterminate
            size="96"
        ></v-progress-circular>
    </v-overlay>
</template>

<script>
import { useCurrenciesStore } from "&/stores/currencies";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    data() {
        return {
            ready: true
        }
    },
    methods: {
        getData() {
            this.ready = false;

            axios
                .get(`/web-api/extensions/reports/${this.$route.params.id}`)
                .then(response => {
                    const data = response.data;

                    this.ready = true;
                })
        }
    },
    mounted() {
        this.getData()
    }
}
</script>
