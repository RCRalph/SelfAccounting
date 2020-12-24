require('./bootstrap');
window.Vue = require('vue');

jQuery(() => {
    $('[data-toggle="tooltip"]').tooltip();

    // Darkmode switcher
    $('#darkmode-switcher').on('click', () => {
        const sunMoon = $('#sun-moon');
        if (sunMoon.html().includes('<i class="fas fa-sun"></i>') || sunMoon.html().includes('<i class="fas fa-moon"></i>')) {
            const isDarkmode = sunMoon.html().includes('<i class="fas fa-sun"></i>'),
                switchAttributes = [
				{
                    light: "navbar-lightmode",
                    dark: "navbar-darkmode"
                },
                {
                    light: "card",
                    dark: "dark-card"
                },
                {
                    light: "table-lightmode",
                    dark: "table-darkmode"
                },
                {
                    light: "welcome-bg-change-lightmode",
                    dark: "welcome-bg-change-darkmode"
                },
                {
                    light: "showcase-light",
                    dark: "showcase-dark"
                },
                {
                    light: "profile-wrapper-light",
                    dark: "profile-wrapper-dark"
                },
                {
                    light: "hr-lightmode",
                    dark: "hr-darkmode",
                },
                {
                    light: "hr-lightmode-dashed",
                    dark: "hr-darkmode-dashed"
                },
                {
                    light: "profile-img-lightmode",
                    dark: "profile-img-darkmode"
                },
                {
                    light: "bundle-wrapper-light",
                    dark: "bundle-wrapper-dark"
                },
                {
                    light: "carousel-lightmode",
                    dark: "carousel-darkmode"
                }
            ]

            if (isDarkmode) {
                // Set lightmode
                $('body').css('background-color', 'hsl(210, 40%, 98%)');
                switchAttributes.forEach(item => {
                    $("." + item.dark).removeClass(item.dark).addClass(item.light);
                });
                $("#darkmode-status").html("0");
            }
            else {
                // Set darkmode
                $('body').css('background-color', 'hsl(210, 60%, 2%)');
                switchAttributes.forEach(item => {
                    $("." + item.light).removeClass(item.light).addClass(item.dark);
                });
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
});
