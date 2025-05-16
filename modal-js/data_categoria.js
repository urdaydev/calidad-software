let btnEditModal = document.querySelectorAll('.btn-edit');
const btn_update = document.querySelector('#btn-update');

let idCategoriaUpdate = 0;
btnEditModal.forEach((btn) => {
    btn.addEventListener('click', () => {
        const id = btn.parentElement.parentElement.firstElementChild.textContent;
        idCategoriaUpdate = id;
        fetch('../views/apis/categoria.php?id=' + id)
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            // Inputs 
            const nombre = document.querySelector('.modalUpdate #nombre');
            const descripcion = document.querySelector('.modalUpdate #descripcion');
            nombre.value = data.nombre;
            descripcion.value = data.descripcion;
        })
    });
});
// Enviar datos del formulario de actualizar
btn_update.addEventListener('click', () => {
    const modalUpdate = document.querySelector('.modalUpdate');
    modalUpdate.style.display = 'none';
    const nombre = document.querySelector('.modalUpdate #nombre').value;
    const descripcion = document.querySelector('.modalUpdate #descripcion').value;
    const data = {
        id_categoria: idCategoriaUpdate,
        nombre: nombre,
        descripcion: descripcion
    }
    fetch('../views/apis/categoria.php', {
        method: 'PUT',
        body: JSON.stringify(data)
    })
    .then((response) => {
        return response.json();
    })
    .then((data) => {
       // Si la respuesta es exitosa y se actualizan los datos
       // al hacer click en aceptar se recarga la página

        if (data.success) {
            swal.fire({
                title: "Actualizado",
                text: "Los datos se actualizaron correctamente",
                icon: "success",
                button: "Aceptar",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../views/categorias.php";
                    console.log('Confirmado');
                }
            }
            );
            
        } else if(data.error) {
            swal.fire({
                title: "Error",
                text: "Los datos no se actualizaron correctamente",
                icon: "error",
                button: "Aceptar",
            });
        }
    })
});