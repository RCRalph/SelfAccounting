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
        arrayHasUniqueEntries<T extends Record<string, any>>(array: T[], keys: string[]): boolean {
            array.forEach(item => {
                let arrayCopy = _.cloneDeep(array)

                keys.forEach(key => {
                    arrayCopy = arrayCopy.filter(item2 => item2[key] == item[key])
                })

                if (arrayCopy.length > 1) return false
            })

            return true
        },
    },
}
