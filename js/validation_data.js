const dniInput = document.getElementById('dni');

dniInput.addEventListener('change', (event) => {
    let dniValue = event.target.value;
    dniValue = cleanString(dniValue);
    if (dniValue.length != 8) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'El DNI debe tener 8 dígitos'
            })
        return;
    } else {
        const apiUrl = `https://dniruc.apisperu.com/api/v1/dni/${dniValue}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im1hc2FkZTk5NDVAaWJ0cmFkZXMuY29tIn0.HscnB_OWPfEVIzZuHOeMh4OUj4h92U6t46DpPUTutXY`;
        fetch(apiUrl)
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            // Accede a los nombres y apellidos desde el objeto JSON recibido
            const nombres = data.nombres || "";
            const apellidos = data.apellidoPaterno + " " + data.apellidoMaterno || "";
    
            // Modifico los valores de los campos de nombres y apellidos
           if (nombres != "" && apellidos != "") {
                document.getElementById('nombres').value = nombres;
                document.getElementById('apellidos').value = apellidos;
           } else {
            document.getElementById('nombres') = "";
            document.getElementById('apellidos') = "";
           }
        })
        .catch((error) => {
            console.error("Error:", error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'No se pudo obtener los datos del DNI ingresado'
                })
        });
    }
});


const rucInput = document.getElementById('ruc');

rucInput.addEventListener('change', (event) => {
    let rucValue = event.target.value;
    rucValue = cleanString(rucValue);
    if (rucValue.length != 11) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'El RUC debe tener 11 dígitos'
            })
        return;
    } else {
        const apiUrl = `https://dniruc.apisperu.com/api/v1/ruc/${rucValue}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im1hcmNhbG9heXphZUBnbWFpbC5jb20ifQ.nz_IvsXd6uWlpSzS6YQyx0TDVSQFpkHYqp0E5o2jf10`;

    fetch(apiUrl)
    .then((response) => {
        return response.json();
    })
    .then((data) => {
        
        const razonSocial = data.razonSocial || "";
        const nombreComercial = data.nombreComercial || "";
        const direccion = data.direccion || "";

       if (razonSocial != "") {
            const inputRazonSocial = document.getElementById('razon_social');
            inputRazonSocial.value = razonSocial;

            if(nombreComercial != null) {
                const inputNombreComercial = document.getElementById('nombre_tienda');
                inputNombreComercial.value = nombreComercial;
            }
            if (direccion != null) {
                const inputDireccion = document.getElementById('direccion_tienda');
                inputDireccion.value = direccion;
            }
       } else {
        document.getElementById('razon_social') = "";
        document.getElementById('nombre_tienda') = "";
       }
    })
    .catch((error) => {
        console.error("Error:", error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'No se pudo obtener los datos del RUC ingresado'
            })
    });
    }
});


// funcion para quitar los espacios en blanco, puntos y comas
function cleanString(string) {
    string = string.replace(/\s/g, '');
    string = string.replace(/\./g, '');
    string = string.replace(/\,/g, '');
    return string;
}