import _ from "lodash"

import { ref, toRef, type Ref } from "vue"

export default class TableDataMerger<T> {
    public readonly data = ref<T[]>([]) as Ref<T[]>

    private readonly spans: Map<keyof T, number>[] = []

    private readonly keyCounters = new Map<keyof T, { counter: number, value: unknown }>()

    private readonly hoveredRows = ref({
        start: Infinity,
        end: -Infinity,
    })

    constructor(
        private readonly forcedSeparators: Array<keyof T>,
        private readonly singleSeparators: Array<keyof T>,
    ) {
    }

    private setKeyCounters(firstRow: T) {
        this.keyCounters.clear()

        for (let key in firstRow) {
            this.keyCounters.set(key, {
                counter: 1,
                value: undefined,
            })
        }
    }

    private shouldSeparate(mustSeparate: boolean, row: T, key: keyof T) {
        if (!this.data.value.length) return false

        return mustSeparate ||
            this.singleSeparators.includes(key) ||
            !_.isEqual(row[key], this.keyCounters.get(key)?.value)
    }

    private changeDataSpan(key: keyof T, value: unknown) {
        const index = Math.max(
            0,
            this.data.value.length - (this.keyCounters.get(key)?.counter ?? 0) - 1,
        )

        this.spans[index].set(key, this.keyCounters.get(key)?.counter ?? 0)

        if (this.keyCounters.has(key)) {
            this.keyCounters.get(key)!.value = value
            this.keyCounters.get(key)!.counter = 1
        }
    }

    private appendDataRow(row: T) {
        this.data.value.push(toRef(row).value)
        this.spans.push(new Map())

        for (let key in row) {
            this.spans[this.spans.length - 1].set(key, 0)
        }
    }

    reset() {
        this.data.value.length = 0
        this.spans.length = 0
        this.keyCounters.clear()
    }

    append(data: T[]) {
        if (!data.length) return this
        else if (!Object.keys(this.keyCounters).length) {
            this.setKeyCounters(data[0])
        }

        for (let item of data) {
            this.appendDataRow(item)

            let forceSeparation = this.forcedSeparators
                .map(key => item[key] !== undefined && !_.isEqual(item[key], this.keyCounters.get(key)?.value))
                .reduce((item1, item2) => item1 || item2, false)

            for (let key of this.keyCounters.keys()) {
                if (this.shouldSeparate(forceSeparation, item, key)) {
                    this.changeDataSpan(key, item[key])
                } else if (this.keyCounters.has(key)) {
                    this.keyCounters.get(key)!.counter++
                }
            }
        }

        for (let key of this.keyCounters.keys()) {
            const index = this.data.value.length - (this.keyCounters.get(key)?.counter ?? 0)
            this.spans[index].set(key, this.keyCounters.get(key)?.counter ?? 0)
        }

        return this
    }

    setHoveredRows(start: number, span: number = 0) {
        this.hoveredRows.value.start = start
        this.hoveredRows.value.end = start + span - 1
    }

    resetHoveredRows() {
        this.hoveredRows.value.start = Infinity
        this.hoveredRows.value.end = -Infinity
    }

    isRowHighlighted(start: number, span: number = 0) {
        // Check if two intervals intersect
        return Math.max(this.hoveredRows.value.start, start) <= Math.min(this.hoveredRows.value.end, start + span - 1)
    }

    getSpan(index: number, key: keyof T) {
        return this.spans[index].get(key) ?? 0
    }
}
