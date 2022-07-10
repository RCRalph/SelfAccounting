export default {
    data() {
        return {
            validation: {
                date(allowNull, firstEntryDate = null) {
                    return date => {
                        if (allowNull && !date) {
                            return true;
                        }
                        else if (isNaN(Date.parse(date))) {
                            return "Date has to be a date";
                        }
                        else if (firstEntryDate != null && new Date(date).getTime() < new Date(firstEntryDate).getTime()) {
                            return "Date can't be earlier than first entry date set for the current mean";
                        }

                        return true;
                    }
                },
                title(allowNull) {
                    return title => {
                        if (allowNull && !title) {
                            return true;
                        }
                        else if (!title) {
                            return "Title is required";
                        }
                        else if (typeof title != "string") {
                            return "Title has to be a string";
                        }
                        else if (title.length > 64) {
                            return "Title can't have more than 64 characters";
                        }

                        return true;
                    }
                },
                amount(allowNull) {
                    return amount => {
                        amount = String(amount).replaceAll(",", ".");

                        if (allowNull && !amount) {
                            return true;
                        }
                        else if (!amount) {
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
                    }
                },
                price(allowNull) {
                    return price => {
                        price = String(price).replaceAll(",", ".");

                        if (allowNull && !price) {
                            return true;
                        }
                        else if (!price) {
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
                },
                differentMeans(otherMean) {
                    return mean => {
                        if (!mean) {
                            return "Mean of payment is required";
                        }
                        else if (mean == otherMean) {
                            return "Means of payment cannot be the same";
                        }

                        return true;
                    }
                },
                name(allowNull) {
                    return name => {
                        if (allowNull && !price) {
                            return true;
                        }
                        else if (!name) {
                            return "Name is required";
                        }
                        else if (typeof name != "string") {
                            return "Name has to be a string";
                        }
                        else if (name.length > 32) {
                            return "Name can't have more than 32 characters"
                        }

                        return true;
                    }
                }
            }
        }
    }
}
