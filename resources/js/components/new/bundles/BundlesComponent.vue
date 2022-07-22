<template>
    <v-row>
        <v-col v-for="item in bundles.bundles" :key="item.code" xl="3" lg="4" md="6" cols="12">
            <v-card>
                <v-img
                    height="250"
                    :src="item.thumbnail_link"
                ></v-img>

                <v-card-title class="d-flex justify-center">{{ item.title }}</v-card-title>

                <v-card-text>
                    <div class="text-center">{{ item.short_description }}</div>
                </v-card-text>

                <v-card-actions class="d-flex justify-space-around">
                    <v-btn color="primary" outlined class="mx-1">
                        Buy - €{{ item.price }}
                    </v-btn>

                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn color="amber" outlined class="mx-1" v-on="on" v-bind="attrs">
                                Enable
                            </v-btn>
                        </template>

                        <span>Enable using Premium</span>
                    </v-tooltip>
                </v-card-actions>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import { useBundlesStore } from "&/stores/bundles";

export default {
    setup() {
        const bundles = useBundlesStore();

        return { bundles };
    }
}
</script>
