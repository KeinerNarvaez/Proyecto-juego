document.getElementById('crear_cuenta').addEventListener('submit', function(event) {
    event.preventDefault(); 

    const formData = new FormData(this);

    fetch('../crear_cuenta.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); 
    })
    .catch(error => {
        console.error('Error:', error);
    });
});