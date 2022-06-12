import { defineStore } from "pinia";

export const useCurrenciesStore = defineStore("currencies", {
    state: () => ({
        currencies: [],
        usedCurrency: 1
    }),
    getters: {
        usedCurrencyObject: (state) => state.currencies.find(item => item.id == state.usedCurrency)
    },
    actions: {
        changeCurrency(id) {
            this.usedCurrency = id;
        },
        setCurrencies(currencies) {
            this.currencies = currencies;
        }
    }
})
