let pass = document.getElementById("password");
let icono = document.getElementById("ojo");

icono.addEventListener("click", () => {
    if (pass.type === "password") {
        pass.type = "text";
        icono.classList.remove("fa-eye");
        icono.classList.add("fa-eye-slash");
    } else {
        pass.type = "password";
        icono.classList.remove("fa-eye-slash");
        icono.classList.add("fa-eye");
    }
});