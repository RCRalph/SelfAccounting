require("./bootstrap");
window.Cookies = require("js-cookie");
window.Vue = require("vue");

jQuery(() => {
    $('[data-toggle="tooltip"]').tooltip();

    // Tutorial modal
    if (!Cookies.getJSON("hide_tutorial")) {
        $("#tutorial-modal").addClass("show");
        $("#app").css("filter", "blur(5px)")

        console.log("Next time tutorial will be hidden");
        /*Cookies.set("hide_tutorial", true, {
            path: "",
            expires: 3650,
            secure: true
        });*/
    }

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
