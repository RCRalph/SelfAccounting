import _ from "lodash"

import { ref } from "vue"
import type { Ref } from "vue"

type TableDataType = Record<string, unknown>

export default class TableDataMerger<T extends TableDataType> {
    public data: Ref<Record<string, { value: unknown, span: number }>[]> = ref([])

    // Has to be public because Vue has to watch for changes of these properties in order to highlight rows
    public hoveredRows = ref({
        start: Infinity,
        end: -Infinity,
    })

    private keyCounters: Record<string, { counter: number, value: any }> = {}

    constructor(
        private forcedSeparators: string[],
        private singleSeparators: string[],
    ) {
    }

    private setKeyCounters(firstRow: T) {
        this.keyCounters = {}

        for (let key in firstRow) {
            this.keyCounters[key] = {
                counter: 1,
                value: undefined,
            }
        }
    }

    private shouldSeparate(mustSeparate: boolean, key: string, row: T) {
        if (!this.data.value.length) return false

        return mustSeparate
            || this.singleSeparators.includes(key)
            || !_.isEqual(row[key], this.keyCounters[key].value)
    }

    private changeDataSpan(key: string, value: any) {
        const index = Math.max(this.data.value.length - this.keyCounters[key].counter - 1, 0)
        this.data.value[index][key].span = this.keyCounters[key].counter

        this.keyCounters[key].value = value
        this.keyCounters[key].counter = 1
    }

    private appendDataRow(row: T) {
        const index = this.data.value.length
        this.data.value.push({})

        for (let key in row) {
            this.data.value[index][key] = {
                value: row[key],
                span: 0,
            }
        }
    }

    resetData() {
        this.data.value = []
        this.keyCounters = {}
    }

    appendData(data: T[]) {
        if (!data.length) return this
        else if (!Object.keys(this.keyCounters).length) {
            this.setKeyCounters(data[0])
        }

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
                } else if (this.data.value.length) {
                    this.keyCounters[key].counter++
                }
            }
        }

        for (let key in this.keyCounters) {
            const index = this.data.value.length - this.keyCounters[key].counter
            this.data.value[index][key].span = this.keyCounters[key].counter
        }

        return this
    }

    setHoveredRows(start: number, span: number): void {
        this.hoveredRows.value.start = start
        this.hoveredRows.value.end = start + span - 1
    }

    resetHoveredRows(): void {
        this.hoveredRows.value.start = Infinity
        this.hoveredRows.value.end = -Infinity
    }

    isRowHighlighted(start: number, span: number): boolean {
        // Check if two intervals intersect in O(1) time
        return Math.max(this.hoveredRows.value.start, start) <= Math.min(this.hoveredRows.value.end, start + span - 1)
    }

    get tabulatedData(): T[] {
        const result: T[] = [], previousRow: TableDataType = {}

        if (!this.data.value.length) return result

        for (let item of this.data.value) {
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
