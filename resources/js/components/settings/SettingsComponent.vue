<template>
    <div v-if="ready">
        <v-row>
            <v-col xl="6" cols="12">
                <CategoriesComponent
                    :startData="categories"
                ></CategoriesComponent>
            </v-col>

            <v-col xl="6" cols="12">
                <MeansComponent
                    :startData="means"
                ></MeansComponent>
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
import MeansComponent from "@/settings/MeansComponent.vue";

export default {
    components: {
        CategoriesComponent,
        MeansComponent
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
