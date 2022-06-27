<template>
    <div v-if="ready">
        <v-row>
            <v-col lg="8" md="7" cols="12">
                <v-card class="sticky-panel loading-height">
                    <v-card-title class="font-weight-bold justify-center text-h5 text-capitalize pb-lg-0">{{ type }}</v-card-title>

                    <v-card-text>
                        <IOTableComponent
                            :type="type"
                            :categories="categories"
                            :means="means"
                        ></IOTableComponent>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col lg="4" md="5" cols="12">
                <v-card class="sticky-panel">
                    <v-card-title class="font-weight-bold justify-center text-h5">Overview</v-card-title>

                    <div class="mx-3">
                        <!--<BalanceHistoryChartComponent v-if="currentChart == 1" :id="1"></BalanceHistoryChartComponent>

                        <DataByTypeChartComponent v-else-if="currentChart <= 5" :id="currentChart"></DataByTypeChartComponent>-->
                    </div>
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
import main from "&/mixins/main";

import IOTableComponent from "@/income-outcome/IOTableComponent.vue";

export default {
    components: {
        IOTableComponent
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
            chartTypes: [],
            currentChart: null,

            categories: [],
            means: [],

            ready: false
        }
    },
    computed: {

    },
    methods: {
        getData() {
            this.ready = false;

            axios
                .get(`/web-api/${this.type}/overview/${this.currencies.usedCurrency}`)
                .then(response => {
                    const data = response.data;

                    if (data.charts.length) {
                        this.currentChart = data.charts[0].id
                    }

                    this.categories = data.categories;
                    this.means = data.means;

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
