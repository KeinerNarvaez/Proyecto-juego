 // Seleccionamos todos los inputs
 const inputs = document.querySelectorAll('.code-input');

 // Iteramos sobre cada input para añadir un listener
 inputs.forEach((input, index) => {
     input.addEventListener('input', () => {
         // Solo avanzamos si el campo no está vacío
         if (input.value.length === 1) {
             // Si no es el último input, avanzamos al siguiente
             if (index < inputs.length - 1) {
                 inputs[index + 1].focus();
             }
         }
     });

     // También gestionamos el evento "keydown" para mover hacia atrás
     input.addEventListener('keydown', (event) => {
         // Si el usuario presiona "Backspace" y el campo está vacío, regresamos al anterior
         if (event.key === 'Backspace' && input.value === '') {
             if (index > 0) {
                 inputs[index - 1].focus();
             }
         }
     });
 });