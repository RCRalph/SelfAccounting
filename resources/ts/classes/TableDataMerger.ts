import _ from "lodash"

type TableDataType = Record<string, any>

export default class TableDataMerger<T extends TableDataType> {
    public data: Record<string, { value: Object, span: number }>[] = []

    // Has to be public because Vue has to watch for changes of these properties in order to highlight rows
    public hoveredRows = {start: Infinity, end: -Infinity}

    private keyCounters: Record<string, { counter: number, value: any }> = {}

    constructor(
        private forcedSeparators: string[],
        private singleSeparators: string[],
    ) {
    }

    private setKeyCounters(firstRow: T): void {
        this.keyCounters = {}

        for (let key in firstRow) {
            this.keyCounters[key] = {
                counter: 1,
                value: undefined,
            }
        }
    }

    private shouldSeparate(mustSeparate: boolean, key: string, row: T): boolean {
        if (!this.data.length) return false

        return mustSeparate
            || this.singleSeparators.includes(key)
            || !_.isEqual(row[key], this.keyCounters[key].value)
    }

    private changeDataSpan(key: string, value: any): void {
        const index = Math.max(this.data.length - this.keyCounters[key].counter - 1, 0)
        this.data[index][key].span = this.keyCounters[key].counter

        this.keyCounters[key].value = value
        this.keyCounters[key].counter = 1
    }

    private appendDataRow(row: T): void {
        const index = this.data.length
        this.data.push({})

        for (let key in row) {
            this.data[index][key] = {
                value: row[key],
                span: 0,
            }
        }
    }

    resetData(): void {
        this.data = []
        this.keyCounters = {}
    }

    appendData(data: T[]): TableDataMerger<T> {
        if (!data.length) return this

        this.setKeyCounters(data[0])

        for (let item of data) {
            this.appendDataRow(item)

            let forceSeparation = false
            for (let key of this.forcedSeparators) {
                if (item[key] !== undefined && !_.isEqual(item[key], this.keyCounters[key].value)) {
                    forceSeparation = true
                    break
                }
            }

            for (let key in this.keyCounters) {
                if (this.shouldSeparate(forceSeparation, key, item)) {
                    this.changeDataSpan(key, item[key])
                } else if (this.data.length) {
                    this.keyCounters[key].counter++
                }
            }
        }

        for (let key in this.keyCounters) {
            this.data[this.data.length - this.keyCounters[key].counter][key].span = this.keyCounters[key].counter
        }

        return this
    }

    setHoveredRows(start: number, span: number): void {
        this.hoveredRows.start = start
        this.hoveredRows.end = start + span - 1
    }

    resetHoveredRows(): void {
        this.hoveredRows.start = Infinity
        this.hoveredRows.end = -Infinity
    }

    isRowHighlighted(start: number, span: number): boolean {
        // Check if two intervals intersect in O(1) time
        return Math.max(this.hoveredRows.start, start) <= Math.min(this.hoveredRows.end, start + span - 1)
    }

    get tabulatedData(): T[] {
        const result: T[] = [], previousRow: TableDataType = {}

        if (!this.data.length) return result

        for (let item of this.data) {
            const row: TableDataType = {}
            for (let key in item) {
                if (item[key].span) {
                    previousRow[key] = item[key].value
                }

                row[key] = previousRow[key]
            }

            result.push(row as T)
        }

        return result
    }
}
