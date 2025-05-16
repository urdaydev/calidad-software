let btnEditModal = document.querySelectorAll('.btn-edit');
const btn_update = document.querySelector('#btn-update');

let idClientUpdate = 0;
btnEditModal.forEach((btn) => {
    btn.addEventListener('click', () => {

        const id = btn.parentElement.parentElement.firstElementChild.textContent;
        idClientUpdate = id;
        console.log(id);
        fetch('../views/apis/cliente.php?id=' + id)
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            const {id_cliente, dni, nombres, a_paterno, a_materno} = data;

            const dniInput = document.querySelector('.modalUpdate #dni');
            const nombresInput = document.querySelector('.modalUpdate #nombres');
            const a_paternoInput = document.querySelector('.modalUpdate #a_paterno');
            const a_maternoInput = document.querySelector('.modalUpdate #a_materno');

            dniInput.value = dni || '';
            nombresInput.value = nombres || '';
            a_paternoInput.value = a_paterno || '';
            a_maternoInput.value = a_materno || '';  
        })

    });
});
// Enviar datos del formulario de actualizar
btn_update.addEventListener('click', () => {
    const modalUpdate = document.querySelector('.modalUpdate');
    modalUpdate.style.display = 'none';

    const dni = document.querySelector('.modalUpdate #dni').value;
    const nombres = document.querySelector('.modalUpdate #nombres').value;
    const a_paterno = document.querySelector('.modalUpdate #a_paterno').value;
    const a_materno = document.querySelector('.modalUpdate #a_materno').value;
    
    const data = {
        id_cliente: idClientUpdate,
        dni: dni,
        nombres: nombres,
        a_paterno: a_paterno,
        a_materno: a_materno
    }

    fetch('../views/apis/cliente.php', {
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
                    window.location.href = "../views/clientes.php";
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