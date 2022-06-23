export default {
    data() {
        return {
            hoverRows: {
                start: -1,
                end: -1
            },
            separatorKeys: [ "date" ]
        }
    },
    computed: {
        mergedCells() {
            if (!this.items.length) {
                return [];
            }

            let keys = Object.keys(this.items[0]), counters = {}, retArr = [];
            keys.forEach(item => {
                counters[item] = {
                    value: this.items[0][item],
                    count: 1
                };
            });

            this.items.forEach((item, index) => {
                let pushObj = {};
                keys.forEach(item => pushObj[item] = {
                    value: this.items[index][item],
                    span: 0
                });
                retArr.push(pushObj);

                let foundChangeInSeparatorKeys = false;
                this.separatorKeys.forEach(key => {
                    if (item[key] != counters[key].value) {
                        foundChangeInSeparatorKeys = true;
                    }
                })

                keys.forEach(key => {
                    if (foundChangeInSeparatorKeys || index && (item[key] != counters[key].value || key == "id")) {
                        retArr[index - counters[key].count][key].span = counters[key].count;
                        counters[key] = {
                            value: item[key],
                            count: 1
                        }
                    }
                    else if (index) {
                        counters[key].count += 1
                    }
                })
            })

            keys.forEach(item => {
                retArr[retArr.length - counters[item].count][item].span = counters[item].count;
            })

            return retArr;
        },
        rowsToHighlight() {
            if (this.hoverRows.start == -1 || this.hoverRows.end == -1) {
                return [];
            }

            let retArr = [];
            for (let i = this.hoverRows.start; i <= this.hoverRows.end; i++) {
                retArr.push(i);
            }
            return retArr;
        }
    },
    methods: {
        setRowsToHighlight(start, span) {
            this.hoverRows.start = start;
            this.hoverRows.end = start + span - 1;
        },
        resetRowsToHighlight() {
            this.hoverRows.start = this.hoverRows.end = -1;
        },
        isRowHighlighted(index, span) {
            if (this.hoverRows.start > this.hoverRows.end) {
                return false;
            }

            for (let i = index; i <= index + span - 1; i++) {
                if (this.rowsToHighlight.indexOf(i) != -1) {
                    return true;
                }
            }

            return false;
        }
    }
}
