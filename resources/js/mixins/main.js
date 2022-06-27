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
    }
}
