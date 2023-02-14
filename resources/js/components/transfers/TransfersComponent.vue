<template>
    <div v-if="ready">
        <v-row>
            <v-col xl="8" cols="12" order="last" order-xl="first">
                <TransfersTableComponent
                    :accounts="accounts"
                ></TransfersTableComponent>
            </v-col>

            <v-col xl="4" cols="12" order-xl="last">
                <!--<TransferOverviewComponent
                    :type="type"
                    :charts="charts"
                ></TransferOverviewComponent>-->
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

import TransfersTableComponent from "@/transfers/TransfersTableComponent.vue";
//import TransfersOverviewComponent from "@/income-expences/TransfersOverviewComponent.vue";

export default {
    components: {
        TransfersTableComponent,
        //TransfersOverviewComponent
    },
    mixins: [main],
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    data() {
        return {
            accounts: [],
            charts: [],

            ready: false
        }
    },
    methods: {
        getData() {
            this.ready = false;

            axios
                .get(`/web-api/transfers/currency/${this.currencies.usedCurrency}`)
                .then(response => {
                    const data = response.data;

                    this.accounts = data.accounts;
                    this.charts = data.charts;

                    this.ready = true;
                })
        }
    },
    mounted() {
        this.getData()
        this.currencies.$subscribe(() => this.getData());
    }
}
</script>
