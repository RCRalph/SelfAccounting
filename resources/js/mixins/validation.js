export default {
    data() {
        return {
            validation: {
                date(allowNull = false, minDate = "1970-01-01") {
                    return date => {
                        if (allowNull && !date && date !== undefined) {
                            return true;
                        }
                        else if (isNaN(Date.parse(date))) {
                            return "Date has to be a date";
                        }
                        else if (!isNaN(Date.parse(minDate)) && new Date(date).getTime() < new Date(minDate).getTime()) {
                            return `Date can't be earlier than ${minDate}`;
                        }

                        return true;
                    }
                },
                title(allowNull = false) {
                    return title => {
                        if (allowNull && !title && title !== undefined) {
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
                        if (allowNull && !amount && amount !== undefined) {
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
                        if (allowNull && !price && price !== undefined) {
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
                differentAccounts(otherAccount) {
                    return account => {
                        if (!account) {
                            return "Account is required";
                        }
                        else if (account == otherAccount) {
                            return "Accounts cannot be the same";
                        }

                        return true;
                    }
                },
                name(allowNull = false) {
                    return name => {
                        if (allowNull && !name && name !== undefined) {
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
                icon(allowNull = true) {
                    return name => {
                        if (allowNull && !name && name !== undefined) {
                            return true;
                        }
                        else if (!name) {
                            return "Icon name is required";
                        }
                        else if (typeof name != "string") {
                            return "Icon name has to be a string";
                        }
                        else if (name.length > 64) {
                            return "Icon name can't have more than 64 characters"
                        }

                        return true;
                    }
                },
                username(allowNull = false) {
                    return username => {
                        if (allowNull && !username && username != undefined) {
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
                        if (allowNull && !email && email !== undefined) {
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
                        if (allowNull && !password && password !== undefined) {
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
                cash(owned = 0, type = "income", allowNull = false, allowUndefined = false) {
                    return amount => {
                        if (allowNull && !amount || allowUndefined && amount == undefined) {
                            return true;
                        }
                        else if (isNaN(Number(amount))) {
                            return "Amount has to be a number";
                        }
                        else if (amount < 0) {
                            return "Amount cannot be negative";
                        }
                        else if (amount >= Number.MAX_SAFE_INTEGER) {
                            return `Amount has to be less than ${Number.MAX_SAFE_INTEGER}`;
                        }
                        else if (!Number.isInteger(Number(amount))) {
                            return "Amount has to be an integer";
                        }
                        else if (type == "expences" && owned < amount) {
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
