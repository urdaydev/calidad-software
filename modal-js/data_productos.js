let btnEditModal = document.querySelectorAll(".btn-edit");
const btn_update = document.querySelector("#btn-update");

let idProductUpdate = 0;

btnEditModal.forEach((btn) => {
  btn.addEventListener("click", () => {
    const id = btn.parentElement.parentElement.firstElementChild.textContent;
    idProductUpdate = id;
    console.log(id);
    fetch("../views/apis/producto.php?id=" + id)
      .then((response) => {
        return response.json();
      })
      .then((data) => {
        const idProducto = data.id_producto;
        idProductUpdate = idProducto;
        const {
          id_proveedor: idProveedor,
          id_categoria: idCategoria,
          nom_producto: nombre,
          descripcion,
          precio,
          stock,
          stock_minimo,
          fecha_vencimiento,
          subcategorias,
        } = data;
        function setSelectedOption(
          selector,
          idValue,
          modalClass = ".modalUpdate"
        ) {
          const options = document.querySelectorAll(
            `${modalClass} select#${selector} option`
          );
          options.forEach((option) => {
            option.removeAttribute("selected");
            if (option.value == idValue) {
              option.setAttribute("selected", "selected");
            }
          });
        }
        // Uso de la función
        setSelectedOption("categoria", idCategoria);
        setSelectedOption("proveedor", idProveedor);

        // Pre-seleccionar subcategorías
        const subcategoriasSelect = document.querySelector(
          ".modalUpdate #update_subcategorias"
        );
        const subcategoriasOptions = subcategoriasSelect.options;
        for (let i = 0; i < subcategoriasOptions.length; i++) {
          subcategoriasOptions[i].selected = subcategorias.some(
            (sub) => sub.id_subcategoria == subcategoriasOptions[i].value
          );
        }

        const nombreInput = document.querySelector(".modalUpdate #nombre");
        const descripcionInput = document.querySelector(
          ".modalUpdate #descripcion"
        );
        const precioInput = document.querySelector(".modalUpdate #precio");
        const stockInput = document.querySelector(".modalUpdate #stock");
        const stockMinimoInput = document.querySelector(
          ".modalUpdate #stock_minimo"
        );
        const fechaVencimientoInput = document.querySelector(
          ".modalUpdate #fecha_vencimiento"
        );
        nombreInput.value = nombre;
        descripcionInput.value = descripcion;
        precioInput.value = precio;
        stockInput.value = stock;
        stockMinimoInput.value = stock_minimo;
        fechaVencimientoInput.value = fecha_vencimiento;
      });
  });
});
// Enviar datos del formulario de actualizar
btn_update.addEventListener("click", () => {
  const modalUpdate = document.querySelector(".modalUpdate");
  modalUpdate.style.display = "none";
  const nombre = document.querySelector(".modalUpdate #nombre").value;
  const descripcion = document.querySelector(".modalUpdate #descripcion").value;
  const precio = document.querySelector(".modalUpdate #precio").value;
  const stock = document.querySelector(".modalUpdate #stock").value;
  const stockMinimo = document.querySelector(
    ".modalUpdate #stock_minimo"
  ).value;
  const idProveedor = document.querySelector(".modalUpdate #proveedor").value;
  const idCategoria = document.querySelector(".modalUpdate #categoria").value;
  const fechaVencimiento = document.querySelector(
    ".modalUpdate #fecha_vencimiento"
  ).value;

  // Obtener subcategorías seleccionadas
  const subcategoriasSelect = document.querySelector(
    ".modalUpdate #update_subcategorias"
  );
  const selectedSubcategorias = Array.from(
    subcategoriasSelect.selectedOptions
  ).map((option) => option.value);

  // input de tipo file
  const imagen = document.querySelector(".modalUpdate #imagen").files[0];
  console.log(imagen);

  const formData = new FormData();
  formData.append("id_producto", idProductUpdate);
  formData.append("id_proveedor", idProveedor);
  formData.append("id_categoria", idCategoria);
  formData.append("nom_producto", nombre);
  formData.append("imagen", imagen);
  formData.append("descripcion", descripcion);
  formData.append("precio", precio);
  formData.append("stock", stock);
  formData.append("stock_minimo", stockMinimo);
  formData.append("fecha_vencimiento", fechaVencimiento);

  // Adjuntar subcategorías al FormData
  selectedSubcategorias.forEach((subId) => {
    formData.append("subcategorias[]", subId);
  });

  fetch("../views/apis/producto.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      // Si la respuesta es exitosa y se actualizan los datos
      // al hacer click en aceptar se recarga la página

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
              window.location.href = "../views/productos.php";
              console.log("Confirmado");
            }
          });
      } else if (data.error) {
        swal.fire({
          title: "Error",
          text: "Los datos no se actualizaron correctamente",
          icon: "error",
          button: "Aceptar",
        });
      }
    });
});
