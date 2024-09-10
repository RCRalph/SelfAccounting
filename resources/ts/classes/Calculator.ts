import { round } from "lodash"

interface Allow {
    null: boolean
    zero: boolean
    negative: boolean
}

interface Field {
    regex: RegExp
    precision: number
    name: string
}

interface ResultObject {
    value: number
    hint: string | undefined
    error: string | undefined
}

export default class Calculator {
    private readonly operation: string | number
    private readonly field: Field | undefined
    private readonly errorMargin: number

    constructor(
        operation: string | number | null | undefined,
        fieldType: keyof typeof Calculator.FIELDS | undefined,
        private readonly allow: Allow = {null: true, zero: true, negative: true},
        private readonly additionalValidation: ((value: number) => string | true)[] = [],
    ) {
        if (typeof operation == "string") {
            this.operation = operation.trim()
                .replaceAll(" ", "")
                .replaceAll(",", ".")
        } else if (typeof operation == "number") {
            this.operation = operation
        } else {
            this.operation = ""
        }

        if (fieldType != undefined) {
            this.field = Calculator.FIELDS[fieldType]
            this.errorMargin = 0.001 / Math.pow(10, this.field.precision)
        } else {
            this.field = undefined
            this.errorMargin = Infinity
        }
    }

    public static FIELDS: Record<"amount" | "price" | "value", Field> = {
        amount: {
            regex: /^-?(\d{1,6}|\d{0,6}\.\d+|\d{1,6}\.\d*)$/,
            precision: 3,
            name: "Amount",
        },
        price: {
            regex: /^-?(\d{1,11}|\d{0,11}\.\d+|\d{1,11}\.\d*)$/,
            precision: 2,
            name: "Price",
        },
        value: {
            regex: /^-?(\d{1,11}|\d{0,11}\.\d+|\d{1,11}\.\d*)$/,
            precision: 2,
            name: "Value",
        },
    }

    /*
        Explanation of regex below:
        1) -? - checking for negative value
        2) \d+ - any integer
        3) \d*\.\d+ - any number like *.(digits), example .453
        4) \d+\.\d* - any number like (digits).*, example 432.34
    */
    public static NUMBER_REGEX = /^-?(\d+|\d*\.\d+|\d+\.\d*)$/

    /*
        Explanation of regex below:
        1) -?(\d+|\d*\.\d+|\d+\.\d*) - number regex, explained above
        2) (\+|-|\*|\/|\^) - valid operations: +, -, *, /, ^
        3) ((\+|-|\*|\/|\^)-?(\d+|\d*\.\d+|\d+\.\d*))* - enforce that operations have two arguments, before and after operator
    */
    public static OPERATION_REGEX = /^-?(\d+|\d*\.\d+|\d+\.\d*)([+\-*/^]-?(\d+|\d*\.\d+|\d+\.\d*))*$/

    private getOperationStringWithoutBrackets(operation: string) {
        for (let i = 0; i < operation.length; i++) {
            if (operation[i] == "(") {
                let j = i + 1, counter = 1

                while (j < operation.length && counter > 0) {
                    if (operation[j] == "(") {
                        counter++
                    } else if (operation[j] == ")") {
                        counter--
                    }

                    j++
                }

                if (j == operation.length && counter > 0) {
                    throw new Error("Invalid brackets")
                }

                const bracketResult = this.calculate(operation.slice(i + 1, j - 1))

                if (bracketResult) {
                    operation = operation.slice(0, i) + bracketResult + operation.slice(j)
                }

                i += bracketResult.length
            }
        }

        return operation
    }

    private validate(value: string | number) {
        if (this.allow.null && typeof value == "string" && !value.length) {
            return undefined
        }

        let name = this.field ? this.field.name : "Value"

        if (!value) {
            return `${name} is required`
        } else if (isNaN(Number(value))) {
            return `${name} has to be a number`
        } else if (!Number(value) && !this.allow.zero) {
            return `${name} cannot be zero`
        } else if (Number(value) < 0 && !this.allow.negative) {
            return `${name} cannot be negative`
        } else if (this.field != null && !String(value).match(this.field.regex)) {
            return `${name} is invalid`
        }

        value = Number(value)
        for (const item of this.additionalValidation) {
            const message = item(value)
            if (typeof message == "string") return message
        }

        return undefined
    }

    private calculate(operation: string | number) {
        if (typeof operation == "number") {
            return String(operation)
        }

        operation = this.getOperationStringWithoutBrackets(operation)

        if (!operation) {
            return ""
        } else if (!Calculator.OPERATION_REGEX.test(operation)) {
            throw new Error("Invalid operation")
        }

        /*
            Next few lines require extensive explanation.

            By doing operation.split(/(\d([+\-*\/^]))/) we divide the array into following elements:
                - numbers without their last digit
                - last digit of a previous number combined with the operator to the right of the number
                - the operator itself

            Example: "12345+123" -> ["1234", "5+", "+", "123"]

            What's special about this regex is that it easily solves the problem of handling negative numbers.

            Example: "12345*-5" -> ["1234", "5*", "*", "-5"]

            The for loop checks if elements are in the form of "5*" or "5+", appends the digit to the previous
            number and removes the given element. This way we end up with an array of numbers and operators.

            Example: "12345*-5^-3" -> ["12345", "*", "-5", "^", "-3"]
        */

        let operationArray = operation.split(/(\d([+\-*\/^]))/)
        for (let i = 1; i < operationArray.length; i++) {
            if (/\d[+\-*/^]/.test(operationArray[i])) {
                operationArray[i - 1] += operationArray[i][0]
                operationArray.splice(i, 1)
            }
        }

        // Handle ^
        let i = 1, num1: number, num2: number
        while (i < operationArray.length) {
            num1 = Number(operationArray[i - 1])
            num2 = Number(operationArray[i + 1])

            switch (operationArray[i]) {
                case "^":
                    operationArray[i - 1] = String(Math.pow(num1, num2))
                    operationArray.splice(i, 2)
                    break
                default:
                    i += 2
            }
        }

        // Handle * and /
        i = 1
        while (i < operationArray.length) {
            num1 = Number(operationArray[i - 1])
            num2 = Number(operationArray[i + 1])

            switch (operationArray[i]) {
                case "*":
                    operationArray[i - 1] = String(num1 * num2)
                    operationArray.splice(i, 2)
                    break
                case "/":
                    operationArray[i - 1] = String(num1 / num2)
                    operationArray.splice(i, 2)
                    break
                default:
                    i += 2
            }
        }

        // Handle + and -
        i = 1
        while (i < operationArray.length) {
            num1 = Number(operationArray[i - 1])
            num2 = Number(operationArray[i + 1])

            switch (operationArray[i]) {
                case "+":
                    operationArray[i - 1] = String(num1 + num2)
                    operationArray.splice(i, 2)
                    break
                case "-":
                    operationArray[i - 1] = String(num1 - num2)
                    operationArray.splice(i, 2)
                    break
                default:
                    i += 2
            }
        }

        return operationArray[0]
    }

    get resultObject() {
        const result: ResultObject = {
            value: NaN,
            hint: undefined,
            error: undefined,
        }

        try {
            const value = this.calculate(this.operation)

            if (this.field == undefined) {
                result.error = this.validate(value)

                if (!result.error) {
                    result.value = Number(value)
                }
            } else {
                result.error = this.validate(value)

                const rounded = round(Number(value), this.field.precision)
                if (!result.error) {
                    result.value = rounded
                }

                if (Math.abs(rounded - Number(value)) >= this.errorMargin) {
                    result.hint = `Result will be rounded to ${this.field.precision} decimal places`
                }
            }
        } catch (err) {
            result.error = err instanceof Error ? err.message : "An unknown error has occurred"
        }

        return result
    }

    get resultValue() {
        try {
            const result = Number(this.calculate(this.operation))
            return this.field == undefined ? result : round(result, this.field.precision)
        } catch {
            return NaN
        }
    }
}
