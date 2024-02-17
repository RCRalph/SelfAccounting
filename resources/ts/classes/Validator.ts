export default class Validator {
    static EMAIL_REGEX = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/

    static date(
        allowNull = false,
        minDate = new Date("1970-01-01"),
    ): (date: string | undefined) => boolean | string {
        return (date) => {
            if (allowNull && !date && typeof date != "undefined") return true

            const dateTime = Date.parse(String(date))
            if (isNaN(dateTime)) {
                return "Date has to be in valid format"
            } else if (dateTime < minDate.getTime()) {
                return `Date can't be earlier than ${minDate.toLocaleDateString()}`
            }

            return true
        }
    }

    static title(
        name: string,
        maxLength: number,
        allowNull = false,
    ): (title: string | undefined) => boolean | string {
        name = name.slice(0, 1).toUpperCase() + name.slice(1)

        return (title) => {
            if (allowNull && !title && typeof title != "undefined") {
                return true
            } else if (!title) {
                return `${name} is required`
            } else if (title.length > maxLength) {
                return `${name} can't have more than ${maxLength} characters`
            }

            return true
        }
    }

    static amount(
        allowNull = false,
        allowZero = false,
    ): (amount: string | number | undefined) => boolean | string {
        return (amount) => {
            if (allowNull && !amount && typeof amount != "undefined") return true

            const amountString = String(amount).replaceAll(",", ".")

            if (!amountString) {
                return "Amount is required"
            } else if (isNaN(Number(amountString))) {
                return "Amount has to be a number"
            } else if (Number(amountString) == 0 && !allowZero) {
                return "Amount can't be equal to 0"
            } else if (Number(amountString) < 0) {
                return "Amount can't be negative"
            } else if (!amountString.match(/^\s*(\d{1,6})?(\.\d{1,3})?\s*$/)) {
                return "Amount is an invalid number"
            }

            return true
        }
    }

    static price(
        allowNull = false,
        allowNegative = false,
        allowZero = false,
    ): (price: string | number | undefined) => boolean | string {
        return (price) => {
            if (allowNull && !price && typeof price != "undefined") return true

            const priceString = String(price).replaceAll(",", ".")

            if (!priceString) {
                return "Price is required"
            } else if (isNaN(Number(priceString))) {
                return "Price has to be a number"
            } else if (Number(priceString) == 0 && !allowZero) {
                return "Price can't be equal to 0"
            } else if (Number(priceString) < 0 && !allowNegative) {
                return "Price can't be negative"
            } else if (!priceString.match(/^\s*(-)?(\d{1,11})?(\.\d{1,2})?\s*$/)) {
                return "Price is an invalid number"
            }

            return true
        }
    }

    static differentAccounts(
        otherAccount: number | null | undefined,
    ): (account: number | null | undefined) => boolean | string {
        return (account) => {
            if (!account) {
                return "Account is required"
            } else if (account == otherAccount) {
                return "Accounts cannot be the same"
            }

            return true
        }
    }

    static email(
        allowNull = false,
    ): (email: string | undefined) => boolean | string {
        return (email) => {
            if (allowNull && !email && typeof email != "undefined") {
                return true
            } else if (!email) {
                return "E-mail is required"
            } else if (email.length > 64) {
                return "E-mail can't have more than 64 characters"
            } else if (!email.match(Validator.EMAIL_REGEX)) {
                return "E-mail has to be a valid e-mail address"
            }

            return true
        }
    }

    static password(
        allowNull = false,
    ): (password: string | undefined) => boolean | string {
        return (password) => {
            if (allowNull && !password && typeof password != "undefined") {
                return true
            } else if (!password) {
                return "Password is required"
            } else if (password.length < 8) {
                return "Password has to have more than 8 characters"
            } else if (password.length > 64) {
                return "Password can't have more than 64 characters"
            }

            return true
        }
    }

    static cash(
        owned = 0,
        type: "value" | "income" | "expenses" | "source" | "target" = "value",
        allowNull = true,
        allowUndefined = true,
    ): (amount: string | number | undefined) => boolean | string {
        return (amount) => {
            if (allowNull && !amount || allowUndefined && typeof amount == "undefined") return true

            const amountNumber = Number(amount)

            if (!Number.isInteger(amountNumber)) {
                return "Amount has to be an integer"
            } else if (!Number.isSafeInteger(amountNumber)) {
                return `Amount has to be less than ${Number.MAX_SAFE_INTEGER}`
            }

            switch (type) {
                case "value":
                    if (amountNumber < 0) return "Amount cannot be negative"
                    break
                case "income":
                case "target":
                    if (owned + amountNumber < 0) return "Amount cannot be greater than currently owned amount"
                    break
                case "expenses":
                case "source":
                    if (owned - amountNumber < 0) return "Amount cannot be greater than currently owned amount"
                    break
                default:
                    return "Invalid cash validation type"
            }

            return true
        }
    }

    static search(
        maxLength = 64,
    ): (search: string | undefined) => boolean | string {
        return (search) => {
            if (!search) return true
            else if (search.length > maxLength) {
                return `Search can't have more than ${maxLength} characters`
            }

            return true
        }
    }
}
