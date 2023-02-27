<template>
    <v-dialog v-model="dialog" max-width="700">
        <template v-slot:activator="{ on, attrs }">
            <v-btn outlined v-bind="attrs" v-on="on">Add category</v-btn>
        </template>

        <v-card>
            <v-card-title>Add category</v-card-title>

            <v-card-text>
                <v-form v-model="canSubmit">
                    <v-row>
                        <v-col cols="12" sm="4" class="d-flex justify-center align-center">
                            <IconPickerComponent
                                v-model="data.icon"
                                type="categories"
                            ></IconPickerComponent>
                        </v-col>

                        <v-col cols="12" sm="8">
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
                            <v-switch :color="$vuetify.theme.dark ? 'white' : 'grey'" v-model="data.used_in_income">
                                <template v-slot:label>
                                    Show in income
                                </template>
                            </v-switch>
                        </v-col>

                        <v-col cols="6" sm="4">
                            <v-switch :color="$vuetify.theme.dark ? 'white' : 'grey'" v-model="data.used_in_expences">
                                <template v-slot:label>
                                    Show in expences
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
                <v-btn color="success" outlined :disabled="!canSubmit || loading" @click="submit" :loading="loading">
                    Submit
                </v-btn>
            </v-card-actions>
        </v-card>

        <ErrorSnackbarComponent v-model="error"></ErrorSnackbarComponent>
    </v-dialog>
</template>

<script>
import ErrorSnackbarComponent from "@/ErrorSnackbarComponent.vue";
import IconPickerComponent from "@/IconPickerComponent.vue";

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
        ErrorSnackbarComponent,
        IconPickerComponent
    },
    data() {
        return {
            dialog: false,
            startData: {
                icon: "",
                name: "",
                used_in_income: true,
                used_in_expences: true,
                show_on_charts: true,
                count_to_summary: false,
                start_date: null,
                end_date: null
            },
            data: {},

            error: false,
            loading: false,
            canSubmit: false
        }
    },
    methods: {
        submit() {
            this.loading = true;

            axios
                .post(`/web-api/categories/${this.currencies.usedCurrency}`, this.data)
                .then(() => {
                    this.data = _.cloneDeep(this.startData);
                    this.$emit("added");
                    this.dialog = false;
                    this.loading = false;
                })
                .catch(err => {
                    console.error(err);
                    setTimeout(() => this.error = true, 1000);
                    setTimeout(() => this.loading = false, 2000);
                })
        }
    },
    mounted() {
        this.data = _.cloneDeep(this.startData);
    }
}
</script>
