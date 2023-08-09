import _ from "lodash"

export default {
    filters: {
        addSpaces(value: number): string {
            return value
                .toLocaleString("en", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                })
                .split(",")
                .join(String.fromCharCode(160))
        },
        addNoBreakSpaces(value: string): string {
            if (!value) return ""

            return value.split(" ").join(String.fromCharCode(160))
        },
    },
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
        changeJsonValue(object: any, key: string, value: any): any {
            if (typeof object !== "object" || object === null) return object

            if (Array.isArray(object)) {
                object.forEach((item) => this.changeJsonValue(item, key, value))
            } else {
                Object.keys(object).forEach(k => {
                    if (k === key) {
                        object[k] = value
                    } else {
                        this.changeJsonValue(object[k], key, value)
                    }
                })
            }

            return object
        },
    },
}
