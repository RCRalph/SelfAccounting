import {defineStore} from "pinia"

export interface Currency {
    id: number
    ISO: string
}

interface State {
    currencies: Currency[]
    usedCurrency: number
}

export const useCurrenciesStore = defineStore("currencies", {
    state: (): State => ({
        currencies: [],
        usedCurrency: NaN,
    }),
    getters: {
        usedCurrencyObject(state: State): Currency {
            const foundCurrency = state.currencies.find(item => item.id == state.usedCurrency)

            if (typeof foundCurrency == "undefined") {
                throw new Error(`Cannot find currency with id: ${state.usedCurrency}`)
            }

            return foundCurrency
        },
        findCurrency(state: State): (currencyID: number) => (Currency | undefined) {
            return id => state.currencies.find(item => item.id == id)
        },
        selectCurrencies(state: State): (currencyIDs: number[]) => Currency[] {
            return ids => state.currencies.filter(item => ids.includes(item.id))
        },
        findByISO(state: State): (ISO: string) => (Currency | undefined) {
            return ISO => state.currencies.find(item => item.ISO == ISO)
        },
    },
    actions: {
        changeCurrency(id: number): Currency {
            const foundCurrency = this.findCurrency(id)

            if (typeof foundCurrency == "undefined") {
                throw new Error("Change to unknown currency")
            }

            this.usedCurrency = id
            return foundCurrency
        },
        setCurrencies(currencies: Currency[]) {
            this.currencies = currencies

            if (this.currencies.length) {
                this.usedCurrency = this.currencies[0].id
            }

            return this
        },
    },
})
