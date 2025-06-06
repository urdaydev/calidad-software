<?php

session_start();
if (!isset($_SESSION['user_login_status']) and $_SESSION['user_login_status'] != 1) {
  header("location: ../login.php");
  exit;
}

/* Connect To Database*/
require_once("../config/db.php"); //Contiene las variables de configuracion para conectar a la base de datos
require_once("../config/conexion.php"); //Contiene funcion que conecta a la base de datos

require_once("../utils/functions.php");
$id_usuario = $_SESSION['id_usuario'];
$nombre_tienda = getData($con, "SELECT nom_tienda FROM Tienda;", 'nom_tienda');
$nombres = getData($con, "SELECT nombres FROM persona inner join usuario on persona.id_persona = usuario.id_persona where usuario.id_usuario = $id_usuario;", 'nombres');
$tipo_usuario = getData($con, "SELECT nom_tipo_acceso FROM TipoAcceso inner join usuario on TipoAcceso.id_tipo_acceso = usuario.id_tipo_acceso where usuario.id_usuario = $id_usuario;", 'nom_tipo_acceso');


include("../utils/vars.php");
$items_nav = getItemsNav($items_nav, 'home');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <?php include("../head_module.php"); ?>
  <link rel="icon" type="image/png" href="../images/sistema/logo.png">
  <title> Home | Minimarket</title>
  <!----======== CSS ======== -->
  <link rel="stylesheet" href="../css/dashboard.css">
  <!----===== Boxicons CSS ===== -->
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
  <nav class="sidebar close">
    <header>
      <div class="image-text">
        <span class="image">
          <!--<img src="logo.png" alt="">-->
          <img src=<?= '../' . $_SESSION['imagen'] ?> alt="">
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
      echo "Bienvenido a " . $nombre_tienda;
      ?>
    </div>
    <div style="text-align: center; margin-top: 20px;">
      <img src="../images/sistema/logo.png" alt="Logo" style="max-width: 200px;">
    </div>
    <h1 class="title">
      Información
    </h1>
    <div class="cards_info">

      <div class="card">
        <div class="text">
          Numero de productos
        </div>
        <div class="number">
          <p>
            <?php
            $num_productos = getData($con, "SELECT count(*) as num_productos FROM Producto where estado = 1;", 'num_productos');
            echo $num_productos;
            ?>
          </p>
          <i class='bx bx-box icon'></i>
        </div>
      </div>
      <div class="card">
        <div class="text">
          Numero de ventas
        </div>
        <div class="number">
          <p>
            20
          </p>
          <i class='bx bx-cart icon'></i>
        </div>
      </div>
      <div class="card">
        <div class="text">
          Numero de usuarios

        </div>
        <div class="number">
          <p>
            <?php
            $num_usuarios = getData($con, "SELECT count(*) as num_usuarios FROM Usuario;", 'num_usuarios');
            echo $num_usuarios;
            ?>
          </p>

          <i class='bx bx-user icon'></i>
        </div>
      </div>

      <div class="card">
        <div class="text">
          Numero de proveedores
        </div>
        <div class="number">
          <p>
            <?php
            $num_proveedores = getData($con, "SELECT count(*) as num_proveedores FROM Proveedor where estado = 1;", 'num_proveedores');
            echo $num_proveedores;
            ?>
          </p>
          <i class='bx bx-store-alt icon'></i>
        </div>
      </div>
      <div class="card">
        <div class="text">
          Numero de categorias
        </div>
        <div class="number">
          <p>
            <?php
            $num_categorias = getData($con, "SELECT count(*) as num_categorias FROM Categoria where estado = 1;", 'num_categorias');
            echo $num_categorias;
            ?>
          </p>

          <i class='bx bx-list-check icon'></i>
        </div>
      </div>


    </div>
  </section>
</body>
<script src="../js/theme-home.js">
</script>
<script src="../js/search_sections.js">
</script>

</html>