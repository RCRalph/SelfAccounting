export default {
    data() {
        return {
            validation: {
                date(firstEntryDate) {
                    return date => {
                        if (isNaN(Date.parse(date))) {
                            return "Date has to be a date";
                        }
                        else if (new Date(date).getTime() < new Date(firstEntryDate).getTime()) {
                            return "Date can't be earlier than first entry date set for the current mean";
                        }

                        return true;
                    }
                },
                title: title => {
                    if (!title) {
                        return "Title is required";
                    }
                    else if (typeof title != "string") {
                        return "Title has to be a string";
                    }
                    else if (title.length > 64) {
                        return "Title can't have more than 64 characters";
                    }

                    return true;
                },
                amount: amount => {
                    amount = String(amount).replaceAll(",", ".");

                    if (!amount) {
                        return "Amount is required";
                    }
                    else if (isNaN(Number(amount))) {
                        return "Amount has to be a number";
                    }
                    else if (Number(amount) <= 0) {
                        return "Amount has to be positive";
                    }
                    else if (!amount.match(/^\s*(\d{1,6})?(\.\d{1,3})?\s*$/)) {
                        return "Amount is an invalid number";
                    }

                    return true;
                },
                price: price => {
                    price = String(price).replaceAll(",", ".");

                    if (!price) {
                        return "Price is required";
                    }
                    else if (isNaN(Number(price))) {
                        return "Price has to be a number";
                    }
                    else if (Number(price) <= 0) {
                        return "Price has to be positive";
                    }
                    else if (!price.match(/^\s*(\d{1,11})?(\.\d{1,2})?\s*$/)) {
                        return "Price is an invalid number";
                    }

                    return true;
                }
            }
        }
    }
}
