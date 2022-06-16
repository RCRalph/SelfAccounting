<template>
    <div>
        <v-data-table
            disable-sort
            :headers="headers"
            :items="items"
            :items-per-page="15"
            hide-default-footer
        >
            <template v-slot:item="{item, index}">
                <tr class="text-center">
                    <td :i="index" @mouseover="hoverId = index">{{ item.date }}</td>
                    <td>{{ item.title }}</td>
                    <td>{{ item.amount }}</td>
                    <td>{{ item.price | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}</td>
                    <td>{{ item.value | addSpaces }}&nbsp;{{ currencies.usedCurrencyObject.ISO }}</td>
                    <td>{{ item.category }}</td>
                    <td>{{ item.mean }}</td>
                    <td style="white-space: nowrap">
                        <v-icon class="mr-2 cursor-pointer">mdi-pencil</v-icon>
                        <v-icon class="cursor-pointer">mdi-delete</v-icon>
                    </td>
                </tr>
            </template>
        </v-data-table>

        <div class="text-center pt-4">
            <v-pagination></v-pagination>
        </div>
    </div>
</template>

<script>
import { useCurrenciesStore } from "../../../stores/currencies";
import main from "../../../mixins/main";

export default {
    setup() {
        const currencies = useCurrenciesStore();

        return { currencies };
    },
    mixins: [main],
    data() {
        return {
            headers: [
                { text: "Date", align: "center" },
                { text: "Title", align: "center" },
                { text: "Amount", align: "center" },
                { text: "Price", align: "center" },
                { text: "Value", align: "center" },
                { text: "Category", align: "center" },
                { text: "Mean of payment", align: "center" },
                { text: "Actions", align: "center" }
            ],
            items: [],

            hoverId: 0,

            ready: false
        }
    },
    methods: {
        getData() {
            this.ready = false;

            axios
                .get(`/web-api/dashboard/${this.currencies.usedCurrency}/recent-transactions`)
                .then(response => {
                    const data = response.data;

                    this.items = data.items.data;

                    this.ready = true;
                })
        }
    },
    mounted() {
        this.getData()
    }
}
</script>
