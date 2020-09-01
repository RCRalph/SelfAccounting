jQuery(() => {
    if (!$("#date").val()) {
        $("#date").val(new Date().toLocaleDateString('en-ZA').split("/").join("-"))
    }

    $(".value-counting").on("input", () => {
        $("#value").val(Math.round($("#price").val() * $("#amount").val() * 100) / 100)
    })

    $("#currency_id").on("change", () => {
        const currentCurrency = $("#currency_id").val();

        $("#category_id, #mean_id")
            .find("option")
            .remove()

        if (categoriesAndMeans.categories[currentCurrency]) {
            for (let [id, name] of Object.entries(categoriesAndMeans.categories[currentCurrency])) {
                $("#category_id")
                    .append('<option value="' + id + '">' + name + '</option>')
            }
        }

        if (categoriesAndMeans.means[currentCurrency]) {
            for (let [id, name] of Object.entries(categoriesAndMeans.means[currentCurrency])) {
                $("#mean_id")
                    .append('<option value="' + id + '">' + name + '</option>')
            }
        }

        $("#category_id, #mean_id")
            .append('<option value="null">N / A</option>')
    })
});
