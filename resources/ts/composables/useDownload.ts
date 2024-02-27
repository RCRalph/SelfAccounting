function download(filename: string, content: string) {
    const anchor = document.createElement("a")

    anchor.style.display = "none"
    anchor.href = `data:application/octet-stream;charset:utf-8,${encodeURIComponent(content)}`
    anchor.download = filename

    document.body.appendChild(anchor)
    anchor.click()
    document.body.removeChild(anchor)
}

function downloadJSON(filename: string, data: Record<string, unknown>) {
    download(filename, JSON.stringify(data))
}

function downloadCSV(
    filename: string,
    headers: { title: string, key: string }[],
    data: Record<string, string>[],
    showHeader = true,
) {
    const content: string[][] = []

    if (showHeader) {
        content.push(
            headers.map(item => item.title),
        )
    }

    for (const item of data) {
        content.push(headers.map(header => `"${item[header.key]}"`))
    }

    download(filename, content.map(item => item.join(",")).join("\n"))
}

export {
    downloadJSON,
    downloadCSV,
}