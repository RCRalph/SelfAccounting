<template>
    <div v-if="ready">
        <v-row>
            <v-col lg="5" cols="12">
                <OwnedReportsComponent></OwnedReportsComponent>
            </v-col>

            <v-col lg="7" cols="12">
                <SharedReportsComponent
                    :owners="owners"
                ></SharedReportsComponent>
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
import OwnedReportsComponent from "@/extensions/reports/OwnedReportsComponent.vue";
import SharedReportsComponent from "@/extensions/reports/SharedReportsComponent.vue";

export default {
    components: {
        OwnedReportsComponent,
        SharedReportsComponent
    },
    data() {
        return {
            owners: [],
            ready: false
        }
    },
    mounted() {
        this.ready = false;

        axios.get("/web-api/extensions/reports")
            .then(response => {
                const data = response.data;

                this.owners = data.owners;

                this.ready = true;
            })
    }
}
</script>
