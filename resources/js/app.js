require('./bootstrap');
window.Vue = require('vue');

jQuery(() => {
    $('[data-toggle="tooltip"]').tooltip();

    // Darkmode switcher
    $('#darkmode-switcher').on('click', () => {
        function replaceAttributes(source, target) {
            $("." + source).removeClass(source).addClass(target);
        }

        const sunMoon = $('#sun-moon');
        if (sunMoon.html().includes('<i class="fas fa-sun"></i>') || sunMoon.html().includes('<i class="fas fa-moon"></i>')) {
            const isDarkmode = sunMoon.html().includes('<i class="fas fa-sun"></i>');

            if (isDarkmode) {
                // Set lightmode
                $('nav').removeClass('navbar-dark bg-dark').addClass('navbar-light bg-light');
                $('body').css('background-color', 'hsl(210, 40%, 98%)');
                replaceAttributes("dark-card", "card");
                replaceAttributes("table-darkmode", "table-lightmode");
                $('.welcome-bg-change').removeClass('bg-dark text-light').addClass('bg-light text-dark');
                $('.showcase').removeClass('welcome-bg-dark').addClass('welcome-bg-light');
                $("#darkmode-status").html("0");
            }
            else {
                // Set darkmode
                $('nav').removeClass('navbar-light bg-light').addClass('navbar-dark bg-dark');
                $('body').css('background-color', 'hsl(210, 60%, 2%)');
                replaceAttributes("card", "dark-card");
                replaceAttributes("table-lightmode", "table-darkmode");
                $('.welcome-bg-change').removeClass('bg-light text-dark').addClass('bg-dark text-light');
                $('.showcase').removeClass('welcome-bg-light').addClass('welcome-bg-dark');
                $("#darkmode-status").html("1");
            }

            sunMoon.html('<i class="far fa-clock"></i>');
            axios
                .post("/webapi/settings/darkmode", {
                    darkmode: !isDarkmode
                })
                .then(() => {
                    sunMoon.html(isDarkmode ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>');
                })
                .catch(() => {
                    sunMoon.html(isDarkmode ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>');
                })
        }
    });

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
});
