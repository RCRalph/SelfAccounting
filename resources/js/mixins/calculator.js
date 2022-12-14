export default {
    data() {
        return {
            CALCULATOR: {
                /*
                    Explanation of regex below:
                    1) -? - checking for negative value
                    2) \d+ - any integer
                    3) \d*\.\d+ - any number like *.(digits)
                    4) \d+\.\d* - any number like (digits).*
                */
                NUMBER_REGEX: /^-?(\d+|\d*\.\d+|\d+\.\d*)$/,

                /*
                    Explanation of regex below:
                    1) -?(\d+|\d*\.\d+|\d+\.\d*) - number regex, explained above
                    2) (\+|-|\*\*|\*|\/) - valid operations: +, -, *, /, ** (it's important to check for ** before * or else it won't match)
                    3) ((\+|-|\*\*|\*|\/)-?(\d+|\d*\.\d+|\d+\.\d*))* - enforce that operations have two arguments, before and after operator
                */
                OPERATION_REGEX: /^-?(\d+|\d*\.\d+|\d+\.\d*)((\+|-|\*\*|\*|\/)-?(\d+|\d*\.\d+|\d+\.\d*))*$/,

                FIELDS: {
                    amount: {
                        regex: /^-?(\d{1,6}|\d{0,6}\.\d{1,3}|\d{1,6}\.\d{0,3})$/,
                        precision: 3,
                        name: "Amount",
                    },
                    price: {
                        regex: /^-?(\d{1,11}|\d{0,11}\.\d{1,2}|\d{1,11}\.\d{0,2})$/,
                        precision: 2,
                        name: "Price"
                    }
                }
            },
        }
    },
    methods: {
        validateField(value, field = null, allowNull = false, allowZero = false, allowNegative = false) {
            if (allowNull && !value && value !== undefined) {
                return undefined;
            }

            let name = "Value"
            if (field != null) {
                name = field.name;
            }

            if (!value) {
                return `${name} is required`;
            } else if (isNaN(Number(value))) {
                return `${name} has to be a number`;
            } else if (!Number(value) && !allowZero) {
                return `${name} cannot be zero`;
            } else if (Number(value) < 0 && !allowNegative) {
                return `${name} cannot be nagative`;
            } else if (field != null && !value.match(field.regex)) {
                return `${name} is invalid`;
            }

            return undefined;
        },
        getOperationString(operation) {
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
        },
        calculate(operation, field = null) {
            operation = this.getOperationString(operation);

            if (!operation) {
                return null
            }

            if (!this.CALCULATOR.OPERATION_REGEX.test(operation)) {
                throw new Error(field == null ? "Invalid syntax": `${field.name} is invalid`);
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
        },
        getCalculationResult(operation, field = null, allowNull = false, allowZero = false, allowNegative = false) {
            operation = String(operation).trim()
                .replaceAll(" ", "")
                .replaceAll(",", ".");

            const result = {
                value: NaN,
                hint: undefined,
                error: undefined
            };

            if (field === null) {
                result.error = this.validateField(operation, null, allowNull, allowZero, allowNegative);

                if (!result.error) {
                    result.value = Number(operation);
                }
            } else {
                try {
                    let value = this.calculate(operation, field);

                    if (value === null) {
                        result.error = this.validateField(value, field, allowNull, allowZero, allowNegative);
                    }
                    else {
                        let roundedValue = _.round(Number(value), field.precision),
                            errorMargin = 1e-2 * Math.pow(10, -field.precision);

                        result.error = this.validateField(String(roundedValue), field, allowNull, allowZero, allowNegative);
                        if (!result.error) {
                            result.value = roundedValue;
                        }

                        if (Math.abs(roundedValue - Number(value)) >= errorMargin) {
                            result.hint = `${field.name} will be rounded to ${field.precision} decimal places`;
                        }
                    }
                } catch (err) {
                    result.error = err.message;
                }
            }

            return result;
        }
    }
}
