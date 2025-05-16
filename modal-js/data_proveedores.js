let btnEditModal = document.querySelectorAll('.btn-edit');
const btn_update = document.querySelector('#btn-update');

let idProveedorUpdate = 0;
btnEditModal.forEach((btn) => {
    btn.addEventListener('click', () => {
        const id = btn.parentElement.parentElement.firstElementChild.innerHTML;
        idProveedorUpdate = id;
        fetch('../views/apis/proveedor.php?id=' + id)
        .then((response) => {
            
            return response.json();
        })
        .then((data) => {
            const ruc = document.querySelector('.modalUpdate #ruc');
            const razon_social = document.querySelector('.modalUpdate #razon_social');
            const telefono = document.querySelector('.modalUpdate #telefono');
            const correo = document.querySelector('.modalUpdate #correo');
            const direccion = document.querySelector('.modalUpdate #direccion');
            ruc.value = data.ruc;
            razon_social.value = data.razon_social;
            telefono.value = data.telefono;
            correo.value = data.email;
            direccion.value = data.direccion;
        })
        .then((error) => {
            console.log(error);
        });
    });
});
// Enviar datos del formulario de actualizar
btn_update.addEventListener('click', () => {
    const modalUpdate = document.querySelector('.modalUpdate');
    modalUpdate.style.display = 'none';
    const ruc = document.querySelector('.modalUpdate #ruc').value;
    const razon_social = document.querySelector('.modalUpdate #razon_social').value;
    const telefono = document.querySelector('.modalUpdate #telefono').value;
    const correo = document.querySelector('.modalUpdate #correo').value;
    const direccion = document.querySelector('.modalUpdate #direccion').value;
    const data = {
        id_proveedor: idProveedorUpdate,
        ruc: ruc,
        razon_social: razon_social,
        telefono: telefono,
        direccion: direccion,
        email: correo
    }
    console.log(data);
    fetch('../views/apis/proveedor.php', {
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
                    location.reload();
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