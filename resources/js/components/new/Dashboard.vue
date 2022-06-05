<template>
    <v-row>
        <v-col class="col-xl-3 col-lg-4 col-md-6 col-12">
            <v-card>
                <v-card-title class="font-weight-bold justify-center text-h5">Current balance</v-card-title>

                <v-card-text>
                    <div class="text-h4 text-center font-weight-regular mb-6 white--text">{{ 2169.42 | addSpaces }}&nbsp;PLN</div>

                    <v-simple-table>
                        <template v-slot:default>
                            <tbody>
                                <tr v-for="(item, i) in summary" :key="i">
                                    <td class="text-right text-h6">{{ item.mean | addNoBreakSpaces }}</td>
                                    <td class="text-h6">{{ item.balance | addSpaces }}&nbsp;PLN</td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </v-card-text>
            </v-card>
        </v-col>

        <v-col class="col-md-9 col-lg-8 col-md-6 col-12">

        </v-col>
    </v-row>
</template>

<script>
export default {
    data() {
        return {
            summary: [
                {
                    mean: 'Oszczędności',
                    balance: 10000,
                },
                {
                    mean: 'Konto Główne',
                    balance: 673.03,
                },
                {
                    mean: 'Gotówka',
                    balance: 93.89,
                },
                {
                    mean: 'PayPal',
                    balance: 38.93,
                }
            ],
        }
    },
    filters: {
        addSpaces(value) {
            return value
                .toLocaleString("en", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })
                .split(",")
                .join(String.fromCharCode(160));
        },
        addNoBreakSpaces(value) {
            return value.split(" ").join(String.fromCharCode(160));
        }
    },
}
</script>
