require("./bootstrap");
window.Cookies = require("js-cookie");
window.Vue = require("vue");

jQuery(() => {
    $('[data-toggle="tooltip"]').tooltip();

    // Tutorial modal

    const pathName = window.location.pathname
        .replaceAll("/", "_").split("_")
        .map(item => (isNaN(Number(item)) || item === "") ? item : "*")
        .join("_");

    const cookiePath = `hide_tutorial_${SERVER_DATA.user_id + pathName}`;
    if (!Cookies.getJSON(cookiePath) && !SERVER_DATA.hide_all_tutorials && $(".tutorial-content").html().trim().length) {
        $("#tutorial-modal").addClass("show");
        $("#app").css("filter", "blur(5px)");

        function removeElement() {
            $("#app").css("filter", "none");
            $("#tutorial-modal").removeClass("show");
        }

        $("#tutorial-close").on("click", () => {
            removeElement();
        });

        $("#tutorial-stop-showing").on("click", () => {
            Cookies.set(
                cookiePath,
                1,
                {
                    path: window.location.pathname,
                    expires: 3650,
                    secure: true
                }
            );
            removeElement();
        });

        $("#tutorial-never-show").on("click", () => {
            removeElement();

            axios
                .post("/webapi/settings/tutorials", {
                    tutorials: true
                });
        });
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
