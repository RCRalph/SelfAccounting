function arrayHasUniqueEntries(
    array: Record<string | number | symbol, unknown>[],
    keys: (string | number | symbol)[],
) {
    // Does not work yet
    return array.every(item => {
        let copy = [...array]

        for (const key of keys) {
            copy = copy.filter(value => value[key] == item[key])
        }

        return copy.length == 1
    })
}

export {
    arrayHasUniqueEntries,
}