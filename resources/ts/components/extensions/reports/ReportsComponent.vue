<template>
    <div>
        <v-row>
            <v-col xl="5" lg="6" cols="12">
                <OwnedReportsComponent></OwnedReportsComponent>
            </v-col>

            <v-col xl="7" lg="6" cols="12">
                <SharedReportsComponent></SharedReportsComponent>
            </v-col>
        </v-row>
    </div>
</template>

<script setup lang="ts">
import OwnedReportsComponent from "@components/extensions/reports/OwnedReportsComponent.vue"
import SharedReportsComponent from "@components/extensions/reports/SharedReportsComponent.vue"
</script>
