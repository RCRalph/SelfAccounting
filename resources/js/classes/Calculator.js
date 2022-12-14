export default class Calculator {
    #operation;
    #field;
    #allow = {
        null: false,
        zero: false,
        negative: false
    };
    #errorMargin = Infinity;

    constructor (operation, field = null, allowNull = false, allowZero = false, allowNegative = false) {
        this.#operation = String(operation)
            .trim()
            .replaceAll(" ", "")
            .replaceAll(",", ".");

        this.#field = field;

        this.#allow.null = allowNull;
        this.#allow.zero = allowZero;
        this.#allow.negative = allowNegative;

        if (this.#field != null) {
            this.#errorMargin = 1e-3 * Math.pow(10, -this.#field.precision);
        }
    }

    static FIELDS = {
        amount: {
            regex: /^-?(\d{1,6}|\d{0,6}\.\d+|\d{1,6}\.\d*)$/,
            precision: 3,
            name: "Amount",
        },
        price: {
            regex: /^-?(\d{1,11}|\d{0,11}\.\d+|\d{1,11}\.\d*)$/,
            precision: 2,
            name: "Price"
        }
    };

    /*
        Explanation of regex below:
        1) -? - checking for negative value
        2) \d+ - any integer
        3) \d*\.\d+ - any number like *.(digits), example .453
        4) \d+\.\d* - any number like (digits).*, example 432.34
    */
    static NUMBER_REGEX = /^-?(\d+|\d*\.\d+|\d+\.\d*)$/;

    /*
        Explanation of regex below:
        1) -?(\d+|\d*\.\d+|\d+\.\d*) - number regex, explained above
        2) (\+|-|\*\*|\*|\/) - valid operations: +, -, *, /, ** (it's important to check for ** before * or else it won't match)
        3) ((\+|-|\*\*|\*|\/)-?(\d+|\d*\.\d+|\d+\.\d*))* - enforce that operations have two arguments, before and after operator
    */
    static OPERATION_REGEX = /^-?(\d+|\d*\.\d+|\d+\.\d*)((\+|-|\*\*|\*|\/)-?(\d+|\d*\.\d+|\d+\.\d*))*$/;

    #operationString(operation) {
        for (let i = 0; i < operation.length; i++) {
            if (operation[i] == "(") {
                let j = i + 1, counter = 1;
                while (j < operation.length && counter > 0) {
                    if (operation[j] == "(") {
                        counter++;
                    }
                    else if (operation[j] == ")") {
                        counter--;
                    }

                    j += 1;
                }

                if (j == operation.length && counter > 0) {
                    throw new Error("Invalid brackets");
                }
                else {
                    const bracketResult = this.calculate(operation.slice(i + 1, j - 1));
                    operation = operation.slice(0, i) + bracketResult + operation.slice(j);
                    i += bracketResult.length;
                }
            }
        }

        return operation;
    }

    validate(value) {
        if (this.#allow.null && !value && value !== undefined) {
            return undefined;
        }

        let name = "Value";
        if (this.#field != null) {
            name = this.#field.name;
        }

        if (!value) {
            return `${name} is required`;
        } else if (isNaN(Number(value))) {
            return `${name} has to be a number`;
        } else if (!Number(value) && !this.#allow.zero) {
            return `${name} cannot be zero`;
        } else if (Number(value) < 0 && !this.#allow.negative) {
            return `${name} cannot be nagative`;
        } else if (this.#field != null && !value.match(this.#field.regex)) {
            return `${name} is invalid`;
        }

        return undefined;
    }

    #calculate(operation) {
        operation = this.#operationString(operation);

        if (!operation) {
            return null;
        }

        if (!this.constructor.OPERATION_REGEX.test(operation)) {
            throw new Error("Invalid operation");
        }

        /*
            Next few lines require extensive explaination.

            By doing operation.split(/(\d(\+|-|\*\*|\*|\/))/) we divide the array into following elements:
                - numbers without their last digit
                - last digit of a previous number combined with the operator to the right of the number
                - the operator itself

            Example: "12345+123" -> ["1234", "5+", "+", "123"]

            What's special about this regex is that it easily solves the problem of handling negative numbers.

            Example: "12345*-5" -> ["1234", "5*", "*", "-5"]

            The for loop checks if elements are in the form of "5*" or "5+", appends the digit to the previous
            number and removes the given element. This way we end up with an array of numbers and operators.

            Example: "12345*-5**-3" -> ["12345", "*", "-5", "**", "-3"]
        */

        let operationArray = operation.split(/(\d(\+|-|\*\*|\*|\/))/);
        for (let i = 1; i < operationArray.length; i++) {
            if (/\d(\+|-|\*\*|\*|\/)/.test(operationArray[i])) {
                operationArray[i - 1] += operationArray[i][0];
                operationArray.splice(i, 1);
            }
        }

        // Handle **
        let i = 1, num1, num2;
        while (i < operationArray.length) {
            num1 = Number(operationArray[i - 1]);
            num2 = Number(operationArray[i + 1]);

            switch (operationArray[i]) {
                case "**":
                    operationArray[i - 1] = Math.pow(num1, num2).toString();
                    operationArray.splice(i, 2);
                    break
                default:
                    i += 2;
            }
        }

        // Handle * and /
        i = 1;
        while (i < operationArray.length) {
            num1 = Number(operationArray[i - 1]);
            num2 = Number(operationArray[i + 1]);

            switch (operationArray[i]) {
                case "*":
                    operationArray[i - 1] = (num1 * num2).toString();
                    operationArray.splice(i, 2);
                    break
                case "/":
                    operationArray[i - 1] = (num1 / num2).toString();
                    operationArray.splice(i, 2);
                    break
                default:
                    i += 2
            }
        }

        // Handle + and -
        i = 1;
        while (i < operationArray.length) {
            num1 = Number(operationArray[i - 1]);
            num2 = Number(operationArray[i + 1]);

            switch (operationArray[i]) {
                case "+":
                    operationArray[i - 1] = (num1 + num2).toString();
                    operationArray.splice(i, 2);
                    break
                case "-":
                    operationArray[i - 1] = (num1 - num2).toString();
                    operationArray.splice(i, 2);
                    break
                default:
                    i += 2;
            }
        }

        return operationArray[0];
    }

    get resultObject() {
        const result = {
            value: NaN,
            hint: undefined,
            error: undefined
        };

        try {
            let value = this.#calculate(this.#operation);

            if (this.#field === null) {
                result.error = this.validate(value);

                if (!result.error) {
                    result.value = Number(value);
                }
            } else {
                result.error = this.validate(value);

                const rounded = _.round(Number(value), this.#field.precision);
                if (!result.error) {
                    result.value = rounded;
                }

                if (Math.abs(rounded - Number(value)) >= this.#errorMargin) {
                    result.hint = `Result will be rounded to ${this.#field.precision} decimal places`;
                }
            }
        } catch (err) {
            result.error = err.message;
        }

        return result;
    }

    get resultValue() {
        let result;

        try {
            result = Number(this.#calculate(this.#operation));
        } catch {
            return NaN;
        }

        if (this.#field === null) {
            return result;
        }

        return _.round(result, this.#field.precision);
    }
}
