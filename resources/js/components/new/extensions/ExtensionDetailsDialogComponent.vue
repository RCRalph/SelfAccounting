<template>
    <v-dialog v-model="dialog" max-width="800">
        <template v-slot:activator="{ on, attrs }">
            <v-btn outlined block v-on="on" v-bind="attrs">Details</v-btn>
        </template>

        <v-card>
            <v-card-title class="justify-center mb-3">{{ extension.title }}</v-card-title>

            <v-carousel
                cycle
                height="400"
                show-arrows-on-hover
            >
                <v-carousel-item
                    v-for="(image, i) in extension.gallery"
                    :key="i"
                    :src="image"
                ></v-carousel-item>
            </v-carousel>

            <v-card-text v-html="description" class="mt-6 extension-description"></v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
import { marked } from "marked";
import DOMPurify from "dompurify";

export default {
    props: {
        extension: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            dialog: false
        }
    },
    computed: {
        description() {
            return DOMPurify.sanitize(marked(this.extension.description));
        },
    }
}
</script>
