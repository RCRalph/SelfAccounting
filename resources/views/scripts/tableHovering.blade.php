<script>
    function tableHoveringScript() {
        $("tbody td, tbody th").off();

        const headerValues = Array.from($("thead")[0].children[0].children)
            .map(item => item.innerText.toLowerCase());

        let table = [];

        const tableCells = Array.from($("tbody")[0].children)
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

        $("tbody td, tbody th").on("mouseover", event => {
            const isDarkmode = $('#sun-moon').html().includes('<i class="fas fa-sun"></i>');
            const rowIndex = parseInt(event.currentTarget.parentElement.attributes.i.value);
            const rowspan = event.currentTarget.attributes.rowspan != undefined ?
                parseInt(event.currentTarget.attributes.rowspan.value) : 1;

            for (let i = 0; i < rowspan; i++) {
                for (let j in table[rowIndex + i]) {
                    $(table[rowIndex + i][j]).addClass("hover-bg-" + (isDarkmode ? "dark" : "light"));
                }
            }
        });

        $("tbody td, tbody th").on("mouseleave", event => {
            const isDarkmode = $('#sun-moon').html().includes('<i class="fas fa-sun"></i>');
            const rowIndex = parseInt(event.currentTarget.parentElement.attributes.i.value);
            const rowspan = event.currentTarget.attributes.rowspan != undefined ?
                parseInt(event.currentTarget.attributes.rowspan.value) : 1;

            for (let i = 0; i < rowspan; i++) {
                for (let j in table[rowIndex + i]) {
                    $(table[rowIndex + i][j]).removeClass("hover-bg-" + (isDarkmode ? "dark" : "light"));
                }
            }
        });
    }

    if (document.getElementById("table-multi-hover")) {
        tableHoveringScript();
    }
</script>
