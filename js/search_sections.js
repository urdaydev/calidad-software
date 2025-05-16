const searchSections = document.querySelector('#search-sections');
// Cada vez que cambie mientras se escribe en el input de búsqueda, se ejecutará la función searchSections
searchSections.addEventListener('keyup', searchSectionsResult);

function searchSectionsResult(event) {
    const searchValue = event.target.value.toLowerCase();
    const sections = document.querySelectorAll('.menu .menu-links .nav-link');
    sections.forEach((section) => {
        const text = section.querySelector('a span').textContent.toLowerCase();
        if (text.indexOf(searchValue) !== -1) {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }
    });
}
