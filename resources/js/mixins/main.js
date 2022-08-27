export default {
    filters: {
        addSpaces(value) {
            return value
                .toLocaleString("en", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })
                .split(",")
                .join(String.fromCharCode(160));
        },
        addNoBreakSpaces(value) {
            if (!value) {
                return "";
            }

            return value.split(" ").join(String.fromCharCode(160));
        }
    },
    methods: {
        sortUsingOptions(i) {
            return (a, b) => {
                if (i == this.options.sortBy.length) {
                    return 0;
                }

                if (a[this.options.sortBy[i]] == b[this.options.sortBy[i]]) {
                    return this.sortUsingOptions(i + 1)(a, b);
                }

                const sortDirection = this.options.sortDesc[i] ? -1 : 1;

                if (typeof a == "string" && typeof b == "string") {
                    return sortDirection * a[this.options.sortBy[i]].localeCompare(b[this.options.sortBy[i]])
                }

                if (a[this.options.sortBy[i]] > b[this.options.sortBy[i]]) {
                    return sortDirection;
                }
                else {
                    return -sortDirection;
                }
            }
        },
        dateWithCurrentTimeZone(date, showTime = false) {
            date = new Date(date);

            if (isNaN(date)) {
                return "N/A";
            }

            let retStr = [
                date.getFullYear(),
                (date.getMonth() + 1).toString().padStart(2, '0'),
                date.getDate().toString().padStart(2, '0')
            ].join("-");

            if (showTime) {
                retStr += " " + [
                    date.getHours().toString().padStart(2, '0'),
                    date.getMinutes().toString().padStart(2, '0')
                ].join(":");
            }

            return retStr;
        }
    }
}
