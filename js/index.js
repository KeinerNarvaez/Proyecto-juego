// Funcionamiento del slider/range que este pueda cambiar de color al movimiento del slider
let sliderEfecto = document.getElementById("sliderEfecto");
let sliderVolumen = document.getElementById("sliderVolumen");

sliderEfecto.addEventListener("mousemove", function(){
    let valorSliderEfecto = sliderEfecto.value;
    let color =  'linear-gradient(90deg,red '+ valorSliderEfecto+'%,rgb(183, 177, 177) '+valorSliderEfecto+'%)';
    sliderEfecto.style.background=color;
})

sliderVolumen.addEventListener("mousemove", function(){
    let valorSliderVolumen = sliderVolumen.value;
    let color =  'linear-gradient(90deg,red '+ valorSliderVolumen+'%,rgb(183, 177, 177) '+valorSliderVolumen+'%)';
    sliderVolumen.style.background=color;
});



