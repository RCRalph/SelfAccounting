<template>
    <div v-if="ready">
        <v-row>
            <v-col xl="6" cols="12">
                <CategoriesComponent
                    :startData="categories"
                ></CategoriesComponent>
            </v-col>

            <v-col xl="6" cols="12">
                <v-card class="loading-height">
                    <v-card-title class="font-weight-bold justify-center text-h5 text-capitalize pb-lg-0">Means of payment</v-card-title>

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

import CategoriesComponent from "@/settings/CategoriesComponent.vue";

export default {
    components: {
        CategoriesComponent
    },
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    data() {
        return {
            ready: false,
            categories: [],
            means: []
        }
    },
    methods: {
        getData() {
            this.ready = false;

            axios
                .get(`/web-api/settings/${this.currencies.usedCurrency}`)
                .then(response => {
                    const data = response.data;

                    this.categories = data.categories;
                    this.means = data.means;

                    this.ready = true;
                })
        }
    },
    mounted() {
        this.getData();
        this.currencies.$subscribe(() => this.getData());
    }
}
</script>
