import { defineStore } from "pinia";

export const useCurrenciesStore = defineStore("currencies", {
    state: () => ({
        currencies: [],
        usedCurrency: 1
    }),
    getters: {
        usedCurrencyObject: state => state.currencies.find(item => item.id == state.usedCurrency),
        findCurrency: state => {
            return currencyID => state.currencies.find(item => currencyID == item.id);
        },
        selectCurrencies: state => {
            return currencyIDs => state.currencies.filter(item => currencyIDs.includes(item.id));
        },
        findByISO: state => {
            return ISO => state.currencies.find(item => ISO == item.ISO);
        }
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
