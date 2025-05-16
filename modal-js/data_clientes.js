// Función para calcular la fecha máxima (18 años atrás desde hoy)
function getMaxDate() {
  const today = new Date();
  today.setFullYear(today.getFullYear() - 18);
  return today.toISOString().split("T")[0];
}

let btnEditModal = document.querySelectorAll(".btn-edit");
const btn_update = document.querySelector("#btn-update");

let idClientUpdate = 0;
btnEditModal.forEach((btn) => {
  btn.addEventListener("click", () => {
    const id = btn.parentElement.parentElement.firstElementChild.textContent;
    idClientUpdate = id;
    fetch("../views/apis/cliente.php?id=" + id)
      .then((response) => response.json())
      .then((data) => {
        if (data.error) {
          console.error(data.error);
          return;
        }

        const {
          id_cliente,
          tipo_doc,
          n_doc,
          nombres,
          a_paterno,
          a_materno,
          f_nacimiento,
        } = data;

        const tipoDocInput = document.querySelector(".modalUpdate #tipo_doc");
        const nDocInput = document.querySelector(".modalUpdate #n_doc");
        const nombresInput = document.querySelector(".modalUpdate #nombres");
        const a_paternoInput = document.querySelector(
          ".modalUpdate #a_paterno"
        );
        const a_maternoInput = document.querySelector(
          ".modalUpdate #a_materno"
        );
        const f_nacimientoInput = document.querySelector(
          ".modalUpdate #f_nacimiento"
        );

        // Establecer la fecha máxima
        f_nacimientoInput.max = getMaxDate();

        tipoDocInput.value = tipo_doc || "";
        nDocInput.value = n_doc || "";
        nombresInput.value = nombres || "";
        a_paternoInput.value = a_paterno || "";
        a_maternoInput.value = a_materno || "";
        f_nacimientoInput.value = f_nacimiento || "";
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  });
});

// Enviar datos del formulario de actualizar
btn_update.addEventListener("click", () => {
  const tipo_doc = document.querySelector(".modalUpdate #tipo_doc").value;
  const n_doc = document.querySelector(".modalUpdate #n_doc").value;
  const nombres = document.querySelector(".modalUpdate #nombres").value;
  const a_paterno = document.querySelector(".modalUpdate #a_paterno").value;
  const a_materno = document.querySelector(".modalUpdate #a_materno").value;
  const f_nacimiento = document.querySelector(
    ".modalUpdate #f_nacimiento"
  ).value;

  // Validar documento según tipo
  const docClean = n_doc
    .replace(/\s/g, "")
    .replace(/\./g, "")
    .replace(/\,/g, "");
  if (tipo_doc === "DNI" && docClean.length !== 8) {
    swal.fire({
      title: "Error",
      text: "El DNI debe tener 8 dígitos",
      icon: "error",
      showConfirmButton: true,
    });
    return;
  } else if (
    tipo_doc === "CE" &&
    (docClean.length < 8 || docClean.length > 20)
  ) {
    swal.fire({
      title: "Error",
      text: "El CE debe tener entre 8 y 20 caracteres",
      icon: "error",
      showConfirmButton: true,
    });
    return;
  }

  // Validar edad antes de enviar
  const hoy = new Date();
  const nacimiento = new Date(f_nacimiento);
  let edad = hoy.getFullYear() - nacimiento.getFullYear();
  const mes = hoy.getMonth() - nacimiento.getMonth();

  if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
    edad--;
  }

  if (edad < 18) {
    swal.fire({
      title: "Error",
      text: "No se pueden registrar clientes menores de edad",
      icon: "error",
      showConfirmButton: true,
    });
    return;
  }

  const modalUpdate = document.querySelector(".modalUpdate");
  modalUpdate.style.display = "none";

  const data = {
    id_cliente: idClientUpdate,
    tipo_doc: tipo_doc,
    n_doc: n_doc,
    nombres: nombres,
    a_paterno: a_paterno,
    a_materno: a_materno,
    f_nacimiento: f_nacimiento,
  };

  fetch("../views/apis/cliente.php", {
    method: "PUT",
    body: JSON.stringify(data),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        swal
          .fire({
            title: "Actualizado",
            text: "Los datos se actualizaron correctamente",
            icon: "success",
            button: "Aceptar",
          })
          .then((result) => {
            if (result.isConfirmed) {
              window.location.href = "../views/clientes.php";
            }
          });
      } else if (data.error) {
        swal.fire({
          title: "Error",
          text: data.error,
          icon: "error",
          button: "Aceptar",
        });
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      swal.fire({
        title: "Error",
        text: "Hubo un error al actualizar los datos",
        icon: "error",
        button: "Aceptar",
      });
    });
});

// Validación en tiempo real de la fecha de nacimiento en el modal de edición
document
  .querySelector(".modalUpdate #f_nacimiento")
  .addEventListener("change", (e) => {
    const f_nacimiento = e.target.value;
    const hoy = new Date();
    const nacimiento = new Date(f_nacimiento);
    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const mes = hoy.getMonth() - nacimiento.getMonth();

    if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
      edad--;
    }

    if (edad < 18) {
      swal.fire({
        title: "Error",
        text: "No se pueden registrar clientes menores de edad",
        icon: "error",
        showConfirmButton: true,
      });
      e.target.value = ""; // Limpiar el campo
    }
  });

// Validación en tiempo real de la fecha de nacimiento en el modal de creación
document
  .querySelector(".modalCreate #f_nacimiento")
  .addEventListener("change", (e) => {
    const f_nacimiento = e.target.value;
    const hoy = new Date();
    const nacimiento = new Date(f_nacimiento);
    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const mes = hoy.getMonth() - nacimiento.getMonth();

    if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
      edad--;
    }

    if (edad < 18) {
      swal.fire({
        title: "Error",
        text: "No se pueden registrar clientes menores de edad",
        icon: "error",
        showConfirmButton: true,
      });
      e.target.value = ""; // Limpiar el campo
    }
  });

// Limpiar solo el campo de documento al cambiar el tipo
document
  .querySelector(".modalCreate #tipo_doc")
  .addEventListener("change", () => {
    document.querySelector(".modalCreate #n_doc").value = "";
  });

document
  .querySelector(".modalUpdate #tipo_doc")
  .addEventListener("change", () => {
    document.querySelector(".modalUpdate #n_doc").value = "";
  });
