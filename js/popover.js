    // Esperar a que el DOM esté completamente cargado antes de inicializar el popover
    document.addEventListener('DOMContentLoaded', function() {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl, {
                html: true, // Habilitar HTML en el contenido del popover
                content: function() {
                    return document.getElementById('chatContent').innerHTML; // Obtener el contenido del chat
                }
            });
        });
    });