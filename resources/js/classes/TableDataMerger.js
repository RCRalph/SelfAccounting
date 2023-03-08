export default class TableDataMerger {
    // Public properties
    data = [];
    hoveredRows = { // Has to be public because Vue has to watch for change of these properties
        start: Infinity,
        end: -Infinity
    };

    // Private properties
    #forcedSeparators = [];
    #singleSeparators = [];
    #keyCounters = undefined;

    constructor (forcedSeparators, singleSeparators) {
        this.#forcedSeparators = forcedSeparators;
        this.#singleSeparators = singleSeparators;
    }

    #setKeyCounters(firstRow) {
        this.#keyCounters = {};

        for (let key in firstRow) {
            this.#keyCounters[key] = {
                counter: 1,
                value: undefined,
            };
        }
    }

    #shouldSeparate(mustSeparate, key, row) {
        if (!this.data.length) return false;

        return mustSeparate
            || this.#singleSeparators.includes(key)
            || !_.isEqual(row[key], this.#keyCounters[key].value);
    }

    #changeDataSpan(key, value) {
        const index = Math.max(this.data.length - this.#keyCounters[key].counter - 1, 0);
        this.data[index][key].span = this.#keyCounters[key].counter;

        this.#keyCounters[key].value = value;
        this.#keyCounters[key].counter = 1;
    }

    #appendDataRow(row) {
        const index = this.data.length;
        this.data.push({});

        for (let key in this.#keyCounters) {
            this.data[index][key] = {
                value: row[key],
                span: 0
            };
        }
    }

    resetData() {
        this.data = [];
        this.#keyCounters = undefined;
    }

    appendData(data) {
        if (!data.length) return
        else if (this.#keyCounters === undefined) {
            this.#setKeyCounters(data[0]);
        }

        for (let item of data) {
            this.#appendDataRow(item);

            let forceSeparation = false;
            for (let key of this.#forcedSeparators) {
                if (item[key] !== undefined && !_.isEqual(item[key], this.#keyCounters[key].value)) {
                    forceSeparation = true;
                    break;
                }
            }

            for (let key in this.#keyCounters) {
                if (this.#shouldSeparate(forceSeparation, key, item)) {
                    this.#changeDataSpan(key, item[key]);
                } else if (this.data.length) {
                    this.#keyCounters[key].counter += 1;
                }
            }
        }

        for (let key in this.#keyCounters) {
            const index = this.data.length - this.#keyCounters[key].counter;
            this.data[index][key].span = this.#keyCounters[key].counter;
        }

        return this;
    }

    setHoveredRows(start, span) {
        this.hoveredRows.start = start;
        this.hoveredRows.end = start + span - 1;
    }

    resetHoveredRows() {
        this.hoveredRows.start = Infinity;
        this.hoveredRows.end = -Infinity;
    }

    isRowHighlighted(start, span) { // Check if two intervals intersect
        return Math.max(this.hoveredRows.start, start) <= Math.min(this.hoveredRows.end, start + span - 1);
    }
}
