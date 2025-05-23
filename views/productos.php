<?php

session_start();
if (!isset($_SESSION['user_login_status']) and $_SESSION['user_login_status'] != 1) {
  header("location: login.php");
  exit;
}

/* Connect To Database*/
require_once("../config/db.php"); //Contiene las variables de configuracion para conectar a la base de datos
require_once("../config/conexion.php"); //Contiene funcion que conecta a la base de datos
require_once("../utils/functions.php");
$sql = " SELECT producto.id_producto, categoria.nom_categoria, producto.nom_producto, producto.descripcion, producto.precio, producto.stock, producto.stock_minimo, producto.imagen FROM producto inner join categoria on producto.id_categoria = categoria.id_categoria WHERE producto.estado = 1 ORDER BY producto.id_producto DESC;";
$query = mysqli_query($con, $sql);
$id_usuario = $_SESSION['id_usuario'];
$nombre_tienda = getData($con, "SELECT nom_tienda FROM Tienda;", 'nom_tienda');
$nombres = getData($con, "SELECT nombres FROM persona inner join usuario on persona.id_persona = usuario.id_persona where usuario.id_usuario = $id_usuario;", 'nombres');
$tipo_usuario = getData($con, "SELECT nom_tipo_acceso FROM TipoAcceso inner join usuario on TipoAcceso.id_tipo_acceso = usuario.id_tipo_acceso where usuario.id_usuario = $id_usuario;", 'nom_tipo_acceso');


include("../utils/vars.php");

$items_nav = getItemsNav($items_nav, 'productos');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <?php include("../head_module.php"); ?>
  <title> Productos | Minimarket</title>
  <!----======== CSS ======== -->
  <link rel="stylesheet" href="../css/dashboard.css">
  <!----===== Boxicons CSS ===== -->
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <style>
    .stock-alert-container {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      height: 100%;
      position: relative;
    }

    .stock-alert {
      color: #dc3545;
      font-weight: bold;
      background-color: rgba(220, 53, 69, 0.1);
      padding: 5px 8px;
      border-radius: 4px;
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      position: relative;
    }

    .stock-alert::before {
      content: "¡Alerta! Stock bajo el mínimo";
      position: absolute;
      bottom: 100%;
      left: 50%;
      transform: translateX(-50%) translateY(-2px);
      background-color: #dc3545;
      color: white;
      padding: 8px 12px;
      border-radius: 4px;
      font-size: 0.9em;
      white-space: nowrap;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .stock-alert:hover::before {
      opacity: 1;
      visibility: visible;
      transform: translateX(-50%) translateY(-4px);
    }

    .stock-alert i {
      color: #dc3545;
      animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
      0% {
        opacity: 1;
      }

      50% {
        opacity: 0.5;
      }

      100% {
        opacity: 1;
      }
    }

    /* Estilos para los botones */
    .btn-edit,
    .producto-delete {
      padding: 6px 12px;
      border-radius: 4px;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      display: inline-block;
    }

    .btn-edit {
      background-color: #0d6efd;
      color: white;
    }

    .btn-edit:hover {
      background-color: #0b5ed7;
      transform: translateY(-1px);
    }

    .producto-delete {
      background-color: #dc3545;
      color: white;
    }

    .producto-delete:hover {
      background-color: #bb2d3b;
      transform: translateY(-1px);
    }

    /* Centrado de contenido en celdas */
    td {
      text-align: center;
      vertical-align: middle;
    }

    /* Ajuste para la imagen */
    td img {
      display: block;
      margin: 0 auto;
    }
  </style>
</head>

<body>
  <nav class="sidebar close">
    <header>
      <div class="image-text">
        <span class="image">
          <!--<img src="logo.png" alt="">-->
          <img src=<?= "../" . $_SESSION['imagen'] ?> alt="">
        </span>

        <div class="text logo-text">
          <span class="name">
            <?php
            echo $nombres;
            ?>
          </span>
          <span class="role">
            <?php
            echo $tipo_usuario;
            ?>

          </span>
        </div>
      </div>
      <i class='bx bx-chevron-right toggle'></i>
    </header>

    <div class="menu-bar">
      <div class="menu">

        <li class="search-box">
          <i class='bx bx-search icon'></i>
          <input type="text" placeholder="Search..." id="search-sections">
        </li>

        <?php include("../menu-links_module.php"); ?>

        <div class="bottom-content">
          <li class="">
            <a href="../login.php?logout">
              <i class='bx bx-log-out icon'></i>
              <span class="text nav-text">Logout</span>

            </a>
          </li>

          <li class="mode">
            <div class="sun-moon">
              <i class='bx bx-moon icon moon'></i>
              <i class='bx bx-sun icon sun'></i>
            </div>
            <span class="mode-text text">Dark mode</span>

            <div class="toggle-switch">
              <span class="switch"></span>
            </div>
          </li>

        </div>
      </div>
  </nav>
  <section class="home">
    <div class="text">
      <?php
      echo $nombre_tienda;
      ?>
    </div>
    <div class="container">
      <div class="card">
        <div class="card-header">
          <h3 class="title">Productos</h3>
          <div class="search">
            <span class="bx bx-search"></span>
            <input type="text" placeholder="Buscar..." id="search-rows">
          </div>
          <div class="btn-container">
            <a class="btn btn-add">
              <span class="text">
                Nueva Producto
              </span>
              <i class='bx bx-plus'></i>
            </a>
          </div>
        </div>
        <div class="card-body">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Categoria</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Stock Mínimo</th>
                <th>Imagen</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody id="tbody-rows">
              <?php while ($row = mysqli_fetch_array($query)): ?>
                <tr>
                  <td><?= $row['id_producto'] ?></td>
                  <td><?= $row['nom_categoria'] ?></td>
                  <td><?= $row['nom_producto'] ?></td>
                  <td><?= $row['descripcion'] ?></td>
                  <td><?= $row['precio'] ?></td>
                  <td>
                    <div class="stock-alert-container">
                      <div class="<?= $row['stock'] <= $row['stock_minimo'] ? 'stock-alert' : '' ?>">
                        <?= $row['stock'] ?>
                        <?php if ($row['stock'] <= $row['stock_minimo']): ?>
                          <i class='bx bxs-error-circle' title="¡Alerta! Stock bajo el mínimo"></i>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>
                  <td><?= $row['stock_minimo'] ?></td>
                  <td><img src="<?= "../" . $row['imagen'] ?>" alt="" width="100px"></td>
                  <td>
                    <a class="producto-update btn-edit">
                      Editar
                    </a>
                  </td>
                  <td>
                    <a href="./delete/producto.php?id=<?= $row['id_producto'] ?>" class="producto-delete">
                      Eliminar
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>

        </div>
      </div>
    </div>
    <div class="modal modalCreate">
      <div class="modal-content">
        <div class="header">
          <span class="title">Crear Producto</span>
          <span class="close">&times;</span>
        </div>
        <div class="body">
          <form action="./create/producto.php" class="form" method="post" enctype="multipart/form-data">
            <div class="form-control">
              <label for="">Categoria</label>
              <select name="categoria" id="categoria" required>
                <option value="" selected disabled>Seleccione una categoría</option>
                <?php
                $sql = "SELECT * FROM categoria WHERE estado = 1;";
                $query = mysqli_query($con, $sql);
                while ($row = mysqli_fetch_array($query)) {
                  echo '<option value="' . $row['id_categoria'] . '">' . $row['nom_categoria'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-control">
              <label for="">Proveedor</label>
              <select name="proveedor" id="proveedor" required>
                <option value="" selected disabled>Seleccione un proveedor</option>
                <?php
                $sql = "SELECT * FROM proveedor inner join empresa on proveedor.id_empresa = empresa.id_empresa where proveedor.estado = 1;";
                $query = mysqli_query($con, $sql);
                while ($row = mysqli_fetch_array($query)) {
                  echo '<option value="' . $row['id_proveedor'] . '">' . $row['razon_social'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-control">
              <label for="">Nombre</label>
              <input type="text" placeholder="Nombre del producto" required name="nombre" id="nombre">
            </div>
            <div class="form-control text-area">
              <label for="">Descripción</label>
              <textarea name="descripcion" required placeholder="Descripción del producto" value="" id="descripcion"></textarea>
            </div>
            <div class="form-control">
              <label for="">Precio</label>
              <input type="number" step="any" min="0" placeholder="Precio del producto" required name="precio" id="precio">
            </div>
            <div class="form-control">
              <label for="">Stock</label>
              <input type="number" min="0" placeholder="Stock del producto" required name="stock" id="stock">
            </div>
            <div class="form-control">
              <label for="">Stock Mínimo</label>
              <input type="number" min="0" placeholder="Stock mínimo del producto" required name="stock_minimo" id="stock_minimo">
            </div>
            <div class="form-control">
              <label for="">Imagen</label>
              <input type="file" required name="imagen" id="imagen">
            </div>
            <div class="btns-container">
              <btn class="btn btn-cancel">Cancelar</btn>
              <input type="submit" class="btn btn-add" value="Crear" id="btn-create">
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="modal modalUpdate">
      <div class="modal-content">
        <div class="header">
          <span class="title">Editar Producto</span>
          <span class="close">&times;</span>
        </div>
        <div class="body">
          <form class="form">
            <div class="form-control">
              <label for="">Categoria</label>
              <select name="categoria" id="categoria" required>
                <option value="" selected disabled>Seleccione una categoría</option>
                <?php
                $sql = "SELECT * FROM categoria;";
                $query = mysqli_query($con, $sql);
                while ($row = mysqli_fetch_array($query)) {
                  echo '<option value="' . $row['id_categoria'] . '">' . $row['nom_categoria'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-control">
              <label for="">Proveedor</label>
              <select name="proveedor" id="proveedor" required>
                <option value="" selected disabled>Seleccione un proveedor</option>
                <?php
                $sql = "SELECT * FROM proveedor inner join empresa on proveedor.id_empresa = empresa.id_empresa;";
                $query = mysqli_query($con, $sql);
                while ($row = mysqli_fetch_array($query)) {
                  echo '<option value="' . $row['id_proveedor'] . '">' . $row['razon_social'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-control">
              <label for="">Nombre</label>
              <input type="text" placeholder="Nombre del producto" required name="nombre" id="nombre">
            </div>
            <div class="form-control text-area">
              <label for="">Descripción</label>
              <textarea name="descripcion" required placeholder="Descripción del producto" value="" id="descripcion"></textarea>
            </div>
            <div class="form-control">
              <label for="">Precio</label>
              <input type="number" placeholder="Precio del producto" step="any" min="0" required name="precio" id="precio">
            </div>
            <div class="form-control">
              <label for="">Stock</label>
              <input type="number" min="0" placeholder="Stock del producto" required name="stock" id="stock">
            </div>
            <div class="form-control">
              <label for="">Stock Mínimo</label>
              <input type="number" min="0" placeholder="Stock mínimo del producto" required name="stock_minimo" id="stock_minimo">
            </div>
            <div class="form-control">
              <label for="">Imagen</label>
              <input type="file" name="imagen" id="imagen">
            </div>
            <div class="btns-container">
              <btn class="btn btn-cancel">Cancelar</btn>
              <input type="button" class="btn btn-add" value="Actualizar" id="btn-update">
            </div>
          </form>
        </div>
      </div>

    </div>
  </section>
</body>

<script src="../js/theme-home.js">
</script>
<script src="../js/search_sections.js">
</script>
<script src="../modal-js/modal.js">
</script>
<script>
  const urlParams = new URLSearchParams(window.location.search);
  const success = urlParams.get('success');
  const error = urlParams.get('error');
  const errorMessages = {
    'emptyField': 'Por favor, llene todos los campos',
    'exist': 'La categoría ya existe',
    'dbError': 'Error al conectar con la base de datos'
  };

  const successMessages = {
    'create': 'Producto creado exitosamente',
    'update': 'Producto actualizado exitosamente',
    'delete': 'Producto eliminado exitosamente'
  };

  if (successMessages[success]) {
    Swal.fire({
      icon: 'success',
      title: successMessages[success],
      showConfirmButton: false,
      timer: 1500
    })
  }

  if (errorMessages[error]) {
    Swal.fire({
      icon: 'error',
      title: errorMessages[error],
      showConfirmButton: false,
      timer: 1500
    })
  }
</script>
<script src="../js/test.js"></script>
<script type="module" src="../js/ventas.js"></script>
<script src="../js/validation_dni.js"></script>
<script src="../modal-js/delete-product-modal.js"></script>
<script src="../modal-js/data_productos.js"></script>
<script src="../js/search_rows.js"></script>

</html>