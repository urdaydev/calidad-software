import { Cart } from "./Cart.js";

const ShoppingCart = new Cart();

const cart = document.querySelector(".content-cart");
const cartClose = document.querySelector(".close-cart");
const cartOpen = document.querySelector(".open-cart");
cartClose.addEventListener("click", () => {
  console.log("click");
  /* Agregar fade in en animation a cart*/
  // Agregar estilos a cart
  cart.style.animation = "fadeOut 0.5s ease forwards";
  setTimeout(() => {
    cart.classList.remove("active");
  }, 500);
});

cartOpen.addEventListener("click", () => {
  console.log("click");
  cart.classList.add("active");
  cart.style.animation = "fadeIn 0.5s ease";
});
let productos = [];
// Obtener todos los productos
fetch("../views/apis/producto.php?all")
  .then((response) => {
    return response.json();
  })
  .then((data) => {
    productos = data;
    // Mostrar los productos en el DOM
    productos.sort(() => Math.random() - 0.5);
    mostrarProductos(productos);
  });
const cards_container = document.querySelector(".cards-container");

function mostrarProductos(productos) {
  cards_container.innerHTML = "";
  // desordenar los productos

  productos.forEach((producto) => {
    const card = document.createElement("div");
    card.classList.add("card");
    card.setAttribute("id", producto.id_producto);
    card.innerHTML = `
        <div class="card__image">
            <img src="../${producto.imagen}" alt="">
        </div>
        <div class="card__content">
            <h3 class="card__title">${producto.nom_producto}</h3>
            <span class="card__category">${producto.nom_categoria}</span>
            <span class="card__description">${producto.descripcion}</span>
            <span class="card__price">S/ ${producto.precio}</span>
        </div>
        <div class="card__footer">
            <button class="btn btn-primary btn-add-cart">Agregar 
            <i class="bx bx-cart-alt"></i>
            </button>
            
        </div>
    `;
    cards_container.appendChild(card);
    const btnAddCart = card.querySelector(".btn-add-cart");
    btnAddCart.addEventListener("click", () => {
      // Agregar el producto al carrito
      agregarProducto(producto);
    });
  });
}

// Obtener el id categoria de un select para filtrar los productos
const selectCategoria = document.querySelector("#selectCategorias");
selectCategoria.addEventListener("change", () => {
  if (selectCategoria.value == "0") {
    mostrarProductos(productos);
    return;
  }
  let productosFiltrados = [...productos].filter(
    (producto) => producto.id_categoria == selectCategoria.value
  );
  mostrarProductos(productosFiltrados);
});
const inputSearch = document.querySelector("#search-cards-productos");
inputSearch.addEventListener("keyup", (e) => {
  // Filtrar los productos por nombre, descripcion, categoria
  if (e.target.value === "") {
    mostrarProductos(productos);
    return;
  }
  if (selectCategoria.value === "0") {
    // Ordenar de forma aleatoria los productos
    mostrarProductos(
      [...productos].filter(
        (producto) =>
          producto.nom_producto
            .toLowerCase()
            .includes(e.target.value.toLowerCase()) ||
          producto.descripcion
            .toLowerCase()
            .includes(e.target.value.toLowerCase()) ||
          producto.nom_categoria
            .toLowerCase()
            .includes(e.target.value.toLowerCase())
      )
    ).sort(() => Math.random() - 0.5);
    return;
  }
  let productosFiltrados = [...productos].filter(
    (producto) => producto.id_categoria == selectCategoria.value
  );
  productosFiltrados = productosFiltrados
    .filter(
      (producto) =>
        producto.nom_producto
          .toLowerCase()
          .includes(e.target.value.toLowerCase()) ||
        producto.descripcion
          .toLowerCase()
          .includes(e.target.value.toLowerCase()) ||
        producto.nom_categoria
          .toLowerCase()
          .includes(e.target.value.toLowerCase())
    )
    .sort(() => Math.random() - 0.5);
  mostrarProductos(productosFiltrados);
});

const agregarProducto = (producto) => {
  const isAdd = ShoppingCart.addProduct(producto);
  if (!isAdd) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "El producto ya está en el carrito",
    });
    return false;
  }
  const contentCartTotal = document.querySelector(".content-cart__total");
  contentCartTotal.classList.remove("inactive");

  const quantity_products = document.querySelector(".quantity-products");
  quantity_products.innerHTML = ShoppingCart.getProducts().length;
  const icon_empty_card = document.querySelector(".icon-empty_card");
  icon_empty_card.classList.add("inactive");
  const empty_text = document.querySelector(".empty-card_text");
  empty_text.classList.add("inactive");

  const dni_container_text = document.querySelector(".dni-container-text");
  dni_container_text.classList.remove("inactive");
  const content_input_container = document.querySelector(
    ".content-cart__input-container"
  );
  content_input_container.classList.remove("inactive");

  const content_card_product = document.createElement("div");
  content_card_product.classList.add("content-card-product");

  // Creamos la imagen
  const imgProducto = document.createElement("img");
  imgProducto.src = `../${producto.imagen}`;
  imgProducto.classList.add("content-card-product__image");
  // Agregamos la imagen al div
  content_card_product.appendChild(imgProducto);

  // Creamos el div info
  const divInfo = document.createElement("div");
  divInfo.classList.add("content-card-product__info");
  // Creamos el h3
  divInfo.innerHTML = `
        <h3 class="content-card-product__title">${producto.nom_producto}</h3>
        <p class="content-card-product__price">P.U: S/ ${producto.precio}</p>
    `;
  // Creamos los botones
  const divBtns = document.createElement("div");
  divBtns.classList.add("btns");
  divInfo.append(divBtns);
  // Creamos el boton
  const btnPlus = document.createElement("i");
  btnPlus.classList.add("bx", "bx-plus-circle", "btn-plus");

  const btnMinus = document.createElement("i");
  btnMinus.classList.add("bx", "bx-minus-circle", "btn-minus");
  const btnDelete = document.createElement("i");

  btnDelete.classList.add("bx", "bx-trash", "btn-delete");

  // Agregamos los botones al div
  divBtns.append(btnMinus);
  divBtns.innerHTML += `<p class="content-card-product__quantity">1</p>`;
  divBtns.append(btnPlus);

  const btnPlusDOM = divBtns.querySelector(".btn-plus");
  const btnMinusDOM = divBtns.querySelector(".btn-minus");

  btnPlusDOM.addEventListener("click", (e) => {
    console.log("click");
    const pQuantity = divBtns.querySelector(".content-card-product__quantity");
    pQuantity.innerHTML = parseInt(pQuantity.innerHTML) + 1;
    const pTotal = divInfo.querySelector(".content-card-product__total");
    pTotal.innerHTML = `
        Total: S/ ${(producto.precio * parseInt(pQuantity.innerHTML)).toFixed(
          2
        )}`;
    ShoppingCart.aumentarCantidad(producto.id_producto);
    contentCartTotal.innerHTML = `Total a pagar: S/ ${ShoppingCart.calculateTotal().toFixed(
      2
    )}`;
  });

  btnMinusDOM.addEventListener("click", () => {
    console.log("click");
    const pQuantity = divBtns.querySelector(".content-card-product__quantity");
    if (parseInt(pQuantity.innerHTML) > 1) {
      pQuantity.innerHTML = parseInt(pQuantity.innerHTML) - 1;
      const pTotal = divInfo.querySelector(".content-card-product__total");
      pTotal.innerHTML = `
            Total: S/ ${(
              producto.precio * parseInt(pQuantity.innerHTML)
            ).toFixed(2)}`;
      ShoppingCart.disminuirCantidad(producto.id_producto);
      contentCartTotal.innerHTML = `Total a pagar: S/ ${ShoppingCart.calculateTotal().toFixed(
        2
      )}`;
    }
  });

  // Creamos el p total
  const pTotal = document.createElement("p");
  pTotal.classList.add("content-card-product__total");

  pTotal.innerHTML = `
        Total: S/ ${producto.precio}
    `;
  // Agregamos el p al div
  divInfo.append(pTotal);

  // Creamos el span
  const span = document.createElement("span");
  span.innerHTML = `
        <i class='bx bx-trash btn-delete'></i>
    `;
  // Agregamos el span al div
  divInfo.append(span);

  const btnDeleteDOM = divInfo.querySelector(".btn-delete");
  btnDeleteDOM.addEventListener("click", () => {
    content_card_product.remove();
    ShoppingCart.removeProduct(producto);
    quantity_products.innerHTML = ShoppingCart.getProducts().length;
    const content_card = document.querySelector(".content-cart__products");
    if (content_card.children.length == 0) {
      const contentCartTotal = document.querySelector(".content-cart__total");
      contentCartTotal.classList.add("inactive");
      const btn_register_sale = document.querySelector(".btn-register-sale");
      btn_register_sale.classList.add("inactive");
      const icon_empty_card = document.querySelector(".icon-empty_card");
      icon_empty_card.classList.remove("inactive");
      const empty_text = document.querySelector(".empty-card_text");
      empty_text.classList.remove("inactive");
      const dni_container_text = document.querySelector(".dni-container-text");
      dni_container_text.classList.add("inactive");
      const content_input_container = document.querySelector(
        ".content-cart__input-container"
      );
      content_input_container.classList.add("inactive");
    }
    Swal.fire({
      icon: "success",
      title: "Producto eliminado del carrito",
      showConfirmButton: false,
      timer: 1500,
    });
    contentCartTotal.innerHTML = `Total a pagar: S/ ${ShoppingCart.calculateTotal().toFixed(
      2
    )}`;
  });

  // Agregamos el div info al div
  content_card_product.append(divInfo);
  // Agregamos el div
  const content_card = document.querySelector(".content-cart__products");
  content_card.append(content_card_product);

  const btn_register_sale = document.querySelector(".btn-register-sale");
  btn_register_sale.classList.remove("inactive");

  contentCartTotal.innerHTML = `Total a pagar: S/ ${ShoppingCart.calculateTotal().toFixed(
    2
  )}`;
  Swal.fire({
    icon: "success",
    title: "Producto agregado al carrito",
    showConfirmButton: false,
    timer: 1500,
  });
};
const btn_register_sale = document.querySelector("#btnRegisterSale");
console.log(btn_register_sale);
btn_register_sale.addEventListener("click", () => {
  // Obtener los productos del carrito
  const products = ShoppingCart.getProducts();
  // Obtener el total a pagar
  const total = ShoppingCart.calculateTotal();
  // Obtener el dni
  const dni = document.querySelector("#dni-cliente").value;
  // Obtener el nombre del cliente
  const cliente_text = document.querySelector('.content-cart__client').outerText;

  if(dni == ''){
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Ingrese el DNI del cliente',
    })
    return;
  } else if(cliente_text == ''){
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'No se encontró el DNI, registre al cliente',
    })
    return;
  }

  const data = {
    products,
    total,
    dni,
    cliente_text,
    nombre_usuario,
  };
  // Enviar los datos al servidor a traves del metodo POST
  const url = "../tickets/ticket.php";
    fetch(url, {
        method: "POST",
        body: JSON.stringify(data),
    })
        .then((response) => {
            console.log(response);
            return response.json();
        })
        .then((data) => {
            const rutaPdf = '../tickets/' + data['path'];
            // Mensaje de éxito, un boton para descargar el pdf y otro para cancelar
            // El boton de cancelar debe cerrar el modal
            Swal.fire({
                icon: 'success',
                title: 'Venta registrada',
                text: '¿Desea descargar el ticket?',
                showCancelButton: true,
                confirmButtonText: `Descargar`,
                cancelButtonText: `Cancelar`,
            }).then((result) => {
                result.isConfirmed && window.open(rutaPdf);
                // Si el usuario da click en cancelar, el modal se cierra
                result.isDismissed && Swal.close();
               if(!result.isDismissed){
                 // Limpiar el carrito
                 ShoppingCart.cleanCart();
                 // Ocultar el total
                 const contentCartTotal = document.querySelector('.content-cart__total');
                 contentCartTotal.classList.add('inactive');
                 // Ocultar el boton de registrar venta
                 btn_register_sale.classList.add('inactive');
                 // Ocultar el icono de carrito vacio
                 const icon_empty_card = document.querySelector('.icon-empty_card');
                 icon_empty_card.classList.remove('inactive');
                 // Ocultar el texto de carrito vacio
                 const empty_text = document.querySelector('.empty-card_text');
                 empty_text.classList.remove('inactive');
                 // Ocultar el input de dni
                 const dni_container_text = document.querySelector('.dni-container-text');
                 dni_container_text.classList.add('inactive');
                 // Ocultar el input de dni
                 const content_input_container = document.querySelector('.content-cart__input-container');
                 content_input_container.classList.add('inactive');
                 // Ocultar el numero de productos
                 const quantity_products = document.querySelector('.quantity-products');
                 quantity_products.innerHTML = '';
                 // Ocultar los productos
                 const content_card = document.querySelector('.content-cart__products');
                 content_card.innerHTML = '';
                 // Ocultar el nombre del cliente
                 const content_cart_client = document.querySelector('.content-cart__client');
                 content_cart_client.innerHTML = '';
               }
            })
        .catch((error) => {
        console.log(error);
        });
    })
});