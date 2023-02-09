<template>
    <div v-if="ready">
        <v-row>
            <v-col xl="8" cols="12" order="last" order-xl="first">
                <v-card class="sticky-panel loading-height">
                    <v-card-title class="justify-center text-h5 text-capitalize pb-lg-0">{{ type }}</v-card-title>

                    <v-card-text>
                        <IOTableComponent
                            :type="type"
                            :categories="categories"
                            :accounts="accounts"
                        ></IOTableComponent>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col xl="4" cols="12" order-xl="last">
                <IOOverviewComponent
                    :type="type"
                    :charts="charts"
                ></IOOverviewComponent>
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
import main from "&/mixins/main";

import IOTableComponent from "@/income-outcome/IOTableComponent.vue";
import IOOverviewComponent from "@/income-outcome/IOOverviewComponent.vue";

export default {
    components: {
        IOTableComponent,
        IOOverviewComponent
    },
    mixins: [main],
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    props: {
        type: {
            type: String,
            required: true,
            validator: value => ["income", "outcome"].includes(value)
        }
    },
    data() {
        return {
            categories: [],
            accounts: [],
            charts: [],

            ready: false
        }
    },
    methods: {
        getData() {
            this.ready = false;

            axios
                .get(`/web-api/${this.type}/currency/${this.currencies.usedCurrency}`)
                .then(response => {
                    const data = response.data;

                    this.categories = data.categories;
                    this.accounts = data.accounts;
                    this.charts = data.charts;

                    this.ready = true;
                })
        }
    },
    watch: {
        type() {
            this.getData();
        }
    },
    mounted() {
        this.getData()
        this.currencies.$subscribe(() => this.getData());
    }
}
</script>
