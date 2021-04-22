require("./bootstrap");
window.Vue = require("vue");

jQuery(() => {
    $('[data-toggle="tooltip"]').tooltip();

    // Darkmode switcher
    $("#darkmode-switcher").on("click", () => {
        const sunMoon = $("#sun-moon");
        if (sunMoon.html().includes("sun") || sunMoon.html().includes("moon")) {
            const isDarkmode = sunMoon.html().includes("sun");

            if (isDarkmode) {
                // Set lightmode
                $("body").addClass("lightmode");
            }
            else {
                // Set darkmode
                $("body").removeClass("lightmode");
            }

            if ($("#navbar-left-side").children().length) {
                sunMoon.html('<i class="far fa-clock"></i>');
                axios
                    .post("/webapi/settings/darkmode", {
                        darkmode: !isDarkmode
                    })
                    .finally(() => {
                        sunMoon.html(isDarkmode ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>');
                    })
            }
            else {
                sunMoon.html(isDarkmode ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>');
            }
        }
    });
});
