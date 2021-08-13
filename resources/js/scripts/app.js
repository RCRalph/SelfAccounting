require("./bootstrap");

// Global settings
window.Cookies = require("js-cookie");
window.Vue = require("vue").default;
import Glide from "@glidejs/glide";
window.Glide = Glide;

// Bootstrap tooltips
window.updateTooltips = () => {
    [].slice
        .call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        .map(tooltipTriggerEl => {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
}

document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementsByClassName("glide").length) {
        new Glide(".glide").mount();
    }

    // Tutorial modal
	if (document.getElementById("tutorial-modal-content") && document.getElementById("tutorial-modal-content").innerHTML.trim()) {
		const pathName = window.location.pathname
			.replaceAll("/", "_").split("_")
			.map(item => (isNaN(Number(item)) || item === "") ? item : "*")
			.join("_");

		const cookiePath = `hide_tutorial_${SERVER_DATA.user_id + pathName}`;
		if (!Cookies.getJSON(cookiePath) && !SERVER_DATA.hide_all_tutorials) {
			document.getElementById("tutorial-modal").classList.add("show");
			document.getElementById("app").style.filter = "blur(5px)";

			function removeElement() {
				document.getElementById("tutorial-modal").classList.remove("show");
				document.getElementById("app").style.filter = "none";
			}

			document.getElementById("tutorial-close")
				.addEventListener("click", () => removeElement())

			document.getElementById("tutorial-stop-showing")
				.addEventListener("click", () => {
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
				})

			document.getElementById("tutorial-never-show")
				.addEventListener("click", () => {
					removeElement();

					axios
						.post("/webapi/settings/tutorials", {
							tutorials: true
						});
				})
		}
	}

    // Darkmode switcher
    document.getElementById("darkmode-switcher")
        .addEventListener("click", () => {
            const sunMoon = document.getElementById("sun-moon");
            if (sunMoon.innerHTML.includes("sun") || sunMoon.innerHTML.includes("moon")) {
                const isDarkmode = sunMoon.innerHTML.includes("sun");

                if (isDarkmode) {
                    document.body.classList.add("lightmode");
                }
                else {
                    document.body.classList.remove("lightmode");
                }

                if (document.getElementById("navbar-left-side").children.length) {
                    sunMoon.innerHTML = '<i class="far fa-clock"></i>';
                    axios
                        .post("/webapi/settings/darkmode", {
                            darkmode: !isDarkmode
                        })
                        .finally(() => {
                            sunMoon.innerHTML = isDarkmode ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>';
                        })
                }
                else {
                    sunMoon.innerHTML = isDarkmode ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>';
                }
            }
        });
});
