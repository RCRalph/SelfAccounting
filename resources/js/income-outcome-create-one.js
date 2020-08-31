jQuery(() => {
    if (!$("#date").val()) {
        $("#date").val(new Date().toLocaleDateString('en-ZA').split("/").join("-"))
    }

    $(".value-counting").on("input", () => {
        $("#value").val(Math.round($("#price").val() * $("#amount").val() * 100) / 100)
    })
});
