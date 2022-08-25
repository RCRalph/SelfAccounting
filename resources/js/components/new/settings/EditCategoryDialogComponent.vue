<template>
    <v-dialog v-model="dialog" max-width="700">
        <template v-slot:activator="{ on: dialogOn, attrs: dialogAttrs }">
            <v-tooltip bottom >
                <template v-slot:activator="{ on: tooltipOn, attrs: tooltipAttrs }">
                    <v-icon class="mx-1 cursor-pointer" v-bind="{ ...dialogAttrs, ...tooltipAttrs }" v-on="{ ...dialogOn, ...tooltipOn }">mdi-pencil</v-icon>
                </template>

                <span>Edit category</span>
            </v-tooltip>
        </template>

        <v-card v-if="ready">
            <v-card-title>Edit category</v-card-title>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <v-row>
                        <v-col cols="12" sm="8" offset-sm="2">
                            <v-text-field
                                label="Name"
                                v-model="data.name"
                                counter="32"
                                :rules="[validation.name()]"
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="6" sm="4">
                            <v-switch :color="$vuetify.theme.dark ? 'white' : 'grey'" v-model="data.income_category">
                                <template v-slot:label>
                                    Show in income
                                </template>
                            </v-switch>
                        </v-col>

                        <v-col cols="6" sm="4">
                            <v-switch :color="$vuetify.theme.dark ? 'white' : 'grey'" v-model="data.outcome_category">
                                <template v-slot:label>
                                    Show in outcome
                                </template>
                            </v-switch>
                        </v-col>

                        <v-col cols="6" sm="4">
                            <v-switch :color="$vuetify.theme.dark ? 'white' : 'grey'" v-model="data.show_on_charts">
                                <template v-slot:label>
                                    Show on charts
                                </template>
                            </v-switch>
                        </v-col>

                        <v-col cols="6" sm="4">
                            <v-switch :color="$vuetify.theme.dark ? 'white' : 'grey'" v-model="data.count_to_summary">
                                <template v-slot:label>
                                    Count to summary
                                </template>
                            </v-switch>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <v-text-field :disabled="!data.count_to_summary" type="date" label="Start date" v-model="data.start_date" :max="data.end_date" :rules="[validation.date(true)]"></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <v-text-field :disabled="!data.count_to_summary" type="date" label="End date" v-model="data.end_date" :min="data.start_date" :rules="[validation.date(true)]"></v-text-field>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <v-card-actions class="d-flex justify-space-around">
                <v-btn color="error" outlined @click="reset" :disabled="loading" class="mx-1" width="85">
                    Reset
                </v-btn>

                <v-btn color="success" outlined :disabled="!canSubmit || loading" @click="update" :loading="loading" class="mx-1" width="85">
                    Update
                </v-btn>
            </v-card-actions>
        </v-card>

        <v-card v-else>
            <v-card-title>Edit category</v-card-title>

            <v-card-text class="d-flex justify-center">
                <v-progress-circular
                    indeterminate
                    size="96"
                ></v-progress-circular>
            </v-card-text>
        </v-card>

        <ErrorSnackbarComponent v-model="error"></ErrorSnackbarComponent>
    </v-dialog>
</template>

<script>
import ErrorSnackbarComponent from "@/ErrorSnackbarComponent.vue";

import { useCurrenciesStore } from "&/stores/currencies";
import validation from "&/mixins/validation";
import main from "&/mixins/main";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [validation, main],
    components: {
        ErrorSnackbarComponent
    },
    props: {
        id: Number
    },
    data() {
        return {
            dialog: false,
            data: {},
            dataCopy: {},

            ready: false,
            error: false,
            loading: false,
            canSubmit: false
        }
    },
    watch: {
        dialog() {
            this.ready = false;

            axios
                .get(`/web-api/settings/categories/${this.id}`)
                .then(response => {
                    const data = response.data;

                    this.data = data.data;
                    this.dataCopy = _.cloneDeep(data.data);

                    this.ready = true;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                })
        }
    },
    methods: {
        reset() {
            this.data = _.cloneDeep(this.dataCopy);
        },
        update() {
            this.loading = true;

            axios
                .patch(`/web-api/settings/categories/${this.id}`, this.data)
                .then(() => {
                    this.dataCopy = _.cloneDeep(this.data);
                    this.$emit("updated");
                    this.dialog = false;
                    this.loading = false;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.loading = false, 2000);
                })
        }
    }
}
</script>
