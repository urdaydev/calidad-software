const search_dni = document.querySelector('#dni-cliente');
console.log(search_dni);
search_dni.addEventListener('keyup', (e) => {
    // console.log(e.target.value);
    if (e.target.value.length == 8) {
        // console.log("hola");
        fetch('../views/apis/cliente.php?dni=' + e.target.value)
            .then((response) => {
                // console.log(response);
                return response.json();
            })
            .then((data) => {
                const cliente = data.nombres + ' ' + data.a_paterno + ' ' + data.a_materno;
                if (cliente == 'undefined undefined undefined') {
                    throw new Error('No se encontró el DNI');
                }
                const cliente_text = document.querySelector('.content-cart__client');
                cliente_text.innerHTML = cliente.toUpperCase();
                cliente_text.style.textTransform = 'capitalize';
                
            })
            .catch((error) => {
                //Manejo de errores
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No se encontró el DNI, registre al cliente',
                })
            });
    } 
})