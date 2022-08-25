export default {
    data() {
        return {
            validation: {
                date(allowNull = false, firstEntryDate = null) {
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
                title(allowNull = false) {
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
                amount(allowNull = false, allowZero = false) {
                    return amount => {
                        if (allowNull && !amount) {
                            return true;
                        }

                        amount = String(amount).replaceAll(",", ".");

                        if (!amount) {
                            return "Amount is required";
                        }
                        else if (isNaN(Number(amount))) {
                            return "Amount has to be a number";
                        }
                        else if (!Number(amount) && !allowZero) {
                            return "Amount can't be equal to 0";
                        }
                        else if (Number(amount) < 0) {
                            return "Amount can't be negative";
                        }
                        else if (!amount.match(/^\s*(\d{1,6})?(\.\d{1,3})?\s*$/)) {
                            return "Amount is an invalid number";
                        }

                        return true;
                    }
                },
                price(allowNull = false, allowNegativeAndZero = false) {
                    return price => {
                        if (allowNull && !price) {
                            return true;
                        }

                        price = String(price).replaceAll(",", ".");

                        if (!price) {
                            return "Price is required";
                        }
                        else if (isNaN(Number(price))) {
                            return "Price has to be a number";
                        }
                        else if (Number(price) <= 0 && !allowNegativeAndZero) {
                            return "Price has to be positive";
                        }
                        else if (!price.match(/^\s*(-)?(\d{1,11})?(\.\d{1,2})?\s*$/)) {
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
                name(allowNull = false) {
                    return name => {
                        if (allowNull && !name) {
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
                },
                startBalance(allowNull = false) {
                    return balance => {
                        balance = String(balance).replaceAll(",", ".");

                        if (allowNull && !balance) {
                            return true;
                        }
                        else if (!balance) {
                            return "Balance is required";
                        }
                        else if (isNaN(Number(balance))) {
                            return "Balance has to be a number";
                        }
                        else if (!balance.match(/^\s*(-)?(\d{1,11})?(\.\d{1,2})?\s*$/)) {
                            return "Balance is an invalid number";
                        }

                        return true;
                    }
                },
                username(allowNull = false) {
                    return username => {
                        if (allowNull && !username) {
                            return true;
                        }
                        else if (!username) {
                            return "Username is required";
                        }
                        else if (typeof username != "string") {
                            return "Username has to be a string";
                        }
                        else if (username.length > 32) {
                            return "Username can't have more than 32 characters"
                        }

                        return true;
                    }
                },
                email(allowNull = false) {
                    return email => {
                        if (allowNull && !email) {
                            return true;
                        }
                        else if (!email) {
                            return "E-mail is required";
                        }
                        else if (typeof email != "string") {
                            return "E-mail has to be a string";
                        }
                        else if (!email.match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
                            return "E-mail has to be a valid e-mail"
                        }
                        else if (email.length > 64) {
                            return "E-mail can't have more than 64 characters"
                        }

                        return true;
                    }
                },
                password(allowNull = false) {
                    return password => {
                        if (allowNull && !password) {
                            return true;
                        }
                        else if (!password) {
                            return "Password is required";
                        }
                        else if (typeof password != "string") {
                            return "Password has to be a string";
                        }
                        else if (password.length < 8) {
                            return "Password has to have more than 8 characters";
                        }
                        else if (password.length > 64) {
                            return "Password can't have more than 64 characters";
                        }

                        return true;
                    }
                },
                cash(owned = 0, type, allowNull = false) {
                    return amount => {
                        if (allowNull && !amount) {
                            return true;
                        }
                        if (isNaN(Number(amount))) {
                            return "Amount has to be a number";
                        }
                        else if (amount < 0) {
                            return "Amount cannot be negative";
                        }
                        else if (amount >= 1e7) {
                            return "Amount has to be less than 1000000";
                        }
                        else if (!Number.isInteger(Number(amount))) {
                            return "Amount has to be an integer";
                        }
                        else if (type == "outcome" && owned < amount) {
                            return "Amount cannot be greater than currently owned amount";
                        }

                        return true;
                    }
                },
                search(length = 64) {
                    return search => {
                        if (!search) {
                            return true;
                        }
                        else if (typeof search != "string") {
                            return "Search has to be a string";
                        }
                        else if (search.length > length) {
                            return `Search can't have more than ${length} characters`;
                        }

                        return true;
                    }
                }
            }
        }
    }
}
