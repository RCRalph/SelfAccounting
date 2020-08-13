/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

jQuery(() => {
    $('[data-toggle="tooltip"]').tooltip();

    // Darkmode switcher
    $('#darkmode-switcher').on('click', () => {
        function replaceAttributes(source, target) {
            $("." + source).removeClass(source).addClass(target);
        }

        if ($('#sun-moon').html() == '<i class="fas fa-sun"></i>') {
            // Set lightmode
            $('#sun-moon').html('<i class="fas fa-moon"></i>');
            $('nav').removeClass('navbar-dark bg-dark').addClass('navbar-light bg-light');
            $('body').css('background-color', 'hsl(210, 40%, 98%)');
            replaceAttributes("dark-card", "card");
            replaceAttributes("table-darkmode", "table-lightmode");
        }
        else {
            // Set darkmode
            $('#sun-moon').html('<i class="fas fa-sun"></i>');
            $('nav').removeClass('navbar-light bg-light').addClass('navbar-dark bg-dark');
            $('body').css('background-color', 'hsl(210, 60%, 2%)');
            replaceAttributes("card", "dark-card");
            replaceAttributes("table-lightmode", "table-darkmode");
        }

        const isDarkmode = $('#sun-moon').html() == '<i class="fas fa-sun"></i>';

        axios.post("/user/darkmode", {
            darkmode: isDarkmode
        });
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
