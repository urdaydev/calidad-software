setTimeout(()=>{
    const loading_page = document.querySelector('.loading-page');
    // Agregar animación de salida
    loading_page.classList.add('fade-out');
    // Remover el elemento del DOM
    setTimeout(()=>{
        loading_page.classList.remove('fade-out');
        loading_page.remove();
    }, 1000);
}, 2000);
