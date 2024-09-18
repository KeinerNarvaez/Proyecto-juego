const buttons = document.querySelectorAll(".text-boton");
const audio = document.getElementById("clickSound");

buttons.forEach(button => {
    button.addEventListener("click", () => {
        audio.play();
    });
});
