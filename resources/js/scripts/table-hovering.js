window.tableHovering = () => {
    function mouseOver(event) {
        const rowIndex = parseInt(event.currentTarget.parentElement.attributes.i.value);
        const rowspan = event.currentTarget.attributes.rowspan != undefined ?
            parseInt(event.currentTarget.attributes.rowspan.value) : 1;

        for (let i = 0; i < rowspan; i++) {
            for (let j in table[rowIndex + i]) {
                table[rowIndex + i][j].classList.add("row-hover-bg");
            }
        }
    }

    function mouseLeave(event) {
        const rowIndex = parseInt(event.currentTarget.parentElement.attributes.i.value);
        const rowspan = event.currentTarget.attributes.rowspan != undefined ?
            parseInt(event.currentTarget.attributes.rowspan.value) : 1;

        for (let i = 0; i < rowspan; i++) {
            for (let j in table[rowIndex + i]) {
                table[rowIndex + i][j].classList.remove("row-hover-bg");
            }
        }
    }

    const tableCellElements = document.querySelectorAll("tbody td, tbody th");

    tableCellElements.forEach(item => {
        item.removeEventListener("mouseover", mouseOver);
        item.removeEventListener("mouseleave", mouseLeave);
    });

    const headerValues = Array.from(document.getElementsByTagName("thead")[0].children[0].children)
        .map(item => item.innerText.toLowerCase());

    let table = [];

    const tableCells = Array.from(document.getElementsByTagName("tbody")[0].children)
        .map(item => item.children)
        .map(item => Array.from(item));

    for (let i = 0; i < tableCells.length; i++) {
        let tempObj = {};
        for (let j = 0; j < tableCells[i].length; j++) {
            tempObj[tableCells[i][j].attributes.rep.value] = tableCells[i][j];
        }

        for (let j = 0; j < headerValues.length; j++) {
            if (i != 0 && tempObj[headerValues[j]] == undefined) {
                tempObj[headerValues[j]] = table[i - 1][headerValues[j]];
            }
        }
        table.push(Object.assign({}, tempObj));
    }

    tableCellElements.forEach(item => {
        item.addEventListener("mouseover", mouseOver);
        item.addEventListener("mouseleave", mouseLeave);
    });
}

if (document.getElementById("table-multi-hover")) {
    tableHovering();
}
