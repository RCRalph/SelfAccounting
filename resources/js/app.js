require('./bootstrap');

jQuery(() => {
    $('[data-toggle="tooltip"]').tooltip();

    // Darkmode switcher
    $('#darkmode-switcher').on('click', () => {
        function replaceAttributes(source, target) {
            $("." + source).removeClass(source).addClass(target);
        }

        const isDarkmode = $('#sun-moon').html() == '<i class="fas fa-sun"></i>';
        $('#sun-moon').html('<i class="fas fa-clock"></i>');

        axios.post("/user/darkmode", {
            darkmode: !isDarkmode
        })
        .then(response => {
            if (response.status == 200) {
                if (isDarkmode) {
                    // Set lightmode
                    $('nav').removeClass('navbar-dark bg-dark').addClass('navbar-light bg-light');
                    $('body').css('background-color', 'hsl(210, 40%, 98%)');
                    replaceAttributes("dark-card", "card");
                    replaceAttributes("table-darkmode", "table-lightmode");
                }
                else {
                    // Set darkmode
                    $('nav').removeClass('navbar-light bg-light').addClass('navbar-dark bg-dark');
                    $('body').css('background-color', 'hsl(210, 60%, 2%)');
                    replaceAttributes("card", "dark-card");
                    replaceAttributes("table-lightmode", "table-darkmode");
                }

                $('#sun-moon').html(isDarkmode ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>');
            }
            else {
                $('#sun-moon').html(!isDarkmode ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>')
            }
        })
    })

    // Special table hovering
    if ($("#table-multi-hover").length) {
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
            const isDarkmode = $('#sun-moon').html() == '<i class="fas fa-sun"></i>'
            const rowIndex = parseInt(event.currentTarget.parentElement.attributes.i.value);
            const rep = event.currentTarget.attributes.rep.value;
            const rowspan = event.currentTarget.attributes.rowspan != undefined ?
                parseInt(event.currentTarget.attributes.rowspan.value) : 1;

            for (let i = 0; i < rowspan; i++) {
                for (let j in table[rowIndex + i]) {
                    $(table[rowIndex + i][j]).addClass("hover-bg-" + (isDarkmode ? "dark" : "light"));
                }
            }
        });

        $("tbody td, tbody th").on("mouseleave", event => {
            const isDarkmode = $('#sun-moon').html() == '<i class="fas fa-sun"></i>'
            const rowIndex = parseInt(event.currentTarget.parentElement.attributes.i.value);
            const rep = event.currentTarget.attributes.rep.value;
            const rowspan = event.currentTarget.attributes.rowspan != undefined ?
                parseInt(event.currentTarget.attributes.rowspan.value) : 1;

            for (let i = 0; i < rowspan; i++) {
                for (let j in table[rowIndex + i]) {
                    $(table[rowIndex + i][j]).removeClass("hover-bg-" + (isDarkmode ? "dark" : "light"));
                }
            }
        });
    }
});
