function formatDate(date: Date, showTime: boolean) {
    const data = {
        year: String(date.getFullYear()),
        month: String(date.getMonth() + 1).padStart(2, "0"),
        day: String(date.getDate()).padStart(2, "0"),
        hour: String(date.getHours()).padStart(2, "0"),
        minute: String(date.getMinutes()).padStart(2, "0"),
    }

    let result: string = `${data.year}-${data.month}-${data.day}`

    if (showTime) {
        result += ` ${data.hour}:${data.minute}`
    }

    return result
}

function currentTimeZoneDate(date: string | undefined = undefined) {
    if (date === undefined) {
        return formatDate(new Date(), false)
    } else if (!isNaN(Date.parse(date))) {
        return formatDate(new Date(date), false)
    } else {
        return "N/A"
    }
}

function dateToUTC(date: Date) {
    return new Date(
        date.getUTCFullYear(),
        date.getUTCMonth(),
        date.getUTCDate(),
    )
}

export {
    formatDate,
    currentTimeZoneDate,
    dateToUTC,
}