document.addEventListener("DOMContentLoaded", function () {
    window.addEventListener("scroll", function () {
        var navbar = document.getElementById("navbar");
        if (window.scrollY > 10) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
    });
});

function togglePassword(fieldId, iconId) {
    let passwordField = document.getElementById(fieldId);
    let icon = document.getElementById(iconId);

    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.textContent = "visibility_off";
    } else {
        passwordField.type = "password";
        icon.textContent = "visibility";
    }
}