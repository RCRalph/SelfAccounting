import _ from "lodash"

export default {
    methods: {
        sortUsingOptions(options: any, i = 0): (a: string | any[], b: string | any[]) => number {
            return (a, b) => {
                if (i == options.sortBy.length) return 0

                if (a[options.sortBy[i]] == b[options.sortBy[i]]) {
                    return this.sortUsingOptions(i + 1)(a, b)
                }

                const sortDirection = options.sortDesc[i] ? -1 : 1

                if (typeof a == "string" && typeof b == "string") {
                    return sortDirection * a[options.sortBy[i]].localeCompare(b[options.sortBy[i]])
                }

                return sortDirection * (a[options.sortBy[i]] > b[options.sortBy[i]] ? 1 : -1)
            }
        },
    },
}
