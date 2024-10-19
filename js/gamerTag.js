document.getElementById('alias-usuario').addEventListener('input', function() {
    const alias = this.value;
    const aliasMessage = document.getElementById('aliasMessage');

    // Definir la longitud mínima del alias
    const longitudMinima = 5;

    // Verificar si el alias no está vacío y cumple con la longitud mínima
    if (alias.trim().length < longitudMinima) {
        aliasMessage.textContent = `El alias debe tener al menos ${longitudMinima} caracteres.`;
        aliasMessage.style.color = "red";

        return;
    } else {
        // Limpiar el mensaje si la longitud es suficiente
        aliasMessage.textContent = "";

        // Enviar alias al servidor usando fetch
        fetch('./php/register_gamerTag.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({  alias })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                aliasMessage.textContent = "Alias guardado correctamente";
                aliasMessage.style.color = "green";
            } else {
                aliasMessage.textContent = "Error al guardar el alias";
                aliasMessage.style.color = "red";
            }
        })
        .catch(error => {
            console.error('Error:', error);
            aliasMessage.textContent = "Ocurrió un error al intentar guardar el alias";
            aliasMessage.style.color = "red";
        });
    }
});
