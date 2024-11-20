function toggleLanguageDropdown() {
    const selector = document.getElementById('languageSelector');
    selector.classList.toggle('open');
}

function changeLanguage(lang) {
    // Guarda el idioma seleccionado en localStorage
    localStorage.setItem('selectedLanguage', lang);

    // Define la ruta del archivo según el idioma seleccionado
    const filePath = `./JSON/${lang}.json`;
    
    fetch(filePath)
        .then((response) => response.json())
        .then((translations) => {
            // Encuentra los elementos con la clave de traducción
            const elements = document.querySelectorAll('[data-translate-key]');
            elements.forEach((element) => {
                const key = element.getAttribute('data-translate-key');
                if (translations[key]) {
                    element.textContent = translations[key];
                }
            });
            
            // Actualiza la bandera y el idioma visual
            const currentFlag = document.getElementById('currentFlag');
            const currentLanguage = document.getElementById('currentLanguage');
            
            if (lang === 'es') {
                currentFlag.src = './Assest/colombua.jpg';
                currentLanguage.textContent = 'Español';
            } else if (lang === 'en') {
                currentFlag.src = './Assest/estados unidos.jpg';
                currentLanguage.textContent = 'English';
            }
        })
        .catch((error) => console.error('Error al cargar las traducciones:', error));
}

// Función para cargar el idioma al iniciar la página
function initializeLanguage() {
    // Recupera el idioma guardado en localStorage o usa 'es' por defecto
    const savedLanguage = localStorage.getItem('selectedLanguage') || 'es';
    changeLanguage(savedLanguage);
}

// Cierra el menú desplegable al hacer clic fuera
document.addEventListener('click', function (event) {
    const selector = document.getElementById('languageSelector');
    if (!selector.contains(event.target)) {
        selector.classList.remove('open');
    }
});

// Inicializa el idioma cuando se carga la página
initializeLanguage();
