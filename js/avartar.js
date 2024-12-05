document.addEventListener('DOMContentLoaded', function () {
    fetch('./php/avatar.php', {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('accesorios');

        data.forEach(item => {
            // Crear un elemento de imagen
            const img = document.createElement('img');
            img.src = `data:image/png;base64,${item.imagen_base64}`;
            img.id = item.imagen_base64;
            img.alt = 'Avatar';
            img.classList.add('avatar-img'); 

            container.appendChild(img);
        });
    })
    .catch(error => console.error('Error al obtener los avatares:', error));
});
