function downloadJSON(filename: string, data: Record<string, unknown>) {
    const download = document.createElement("a")

    download.style.display = "none"
    download.href = `data:application/octet-stream;charset:utf-8,${encodeURIComponent(JSON.stringify(data))}`
    download.download = filename

    document.body.appendChild(download)
    download.click()
    document.body.removeChild(download)
}

export {
    downloadJSON,
}