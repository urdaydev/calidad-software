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
require_once("../classes/Producto.php");

$id_usuario = $_SESSION['id_usuario'];
$nombre_tienda = getData($con, "SELECT nom_tienda FROM Tienda;", 'nom_tienda');
$nombres = getData($con, "SELECT nombres FROM persona inner join usuario on persona.id_persona = usuario.id_persona where usuario.id_usuario = $id_usuario;", 'nombres');
$tipo_usuario = getData($con, "SELECT nom_tipo_acceso FROM TipoAcceso inner join usuario on TipoAcceso.id_tipo_acceso = usuario.id_tipo_acceso where usuario.id_usuario = $id_usuario;", 'nom_tipo_acceso');


include("../utils/vars.php");

$items_nav = getItemsNav($items_nav, 'vencimientos');

$producto = new App\Classes\Producto($con);

$productos30dias = $producto->listarProductosProximosAVencer(30);
$productos15dias = $producto->listarProductosProximosAVencer(15);
$productos7dias = $producto->listarProductosProximosAVencer(7);

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <?php include("../head_module.php"); ?>
  <title> Vencimientos | Minimarket</title>
  <!----======== CSS ======== -->
  <link rel="stylesheet" href="../css/dashboard.css">
  <!----===== Boxicons CSS ===== -->
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <style>
    .card-body h2 {
      color: white;
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
          <h3 class="title">Productos Próximos a Vencer</h3>
        </div>
        <div class="card-body">
          <h2>Vencen en 30 días</h2>
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Fecha de Vencimiento</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($productos30dias as $p) : ?>
                <tr>
                  <td><?php echo htmlspecialchars($p['id']); ?></td>
                  <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                  <td><?php echo htmlspecialchars($p['fecha_vencimiento']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

          <h2>Vencen en 15 días</h2>
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Fecha de Vencimiento</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($productos15dias as $p) : ?>
                <tr>
                  <td><?php echo htmlspecialchars($p['id']); ?></td>
                  <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                  <td><?php echo htmlspecialchars($p['fecha_vencimiento']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

          <h2>Vencen en 7 días</h2>
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Fecha de Vencimiento</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($productos7dias as $p) : ?>
                <tr>
                  <td><?php echo htmlspecialchars($p['id']); ?></td>
                  <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                  <td><?php echo htmlspecialchars($p['fecha_vencimiento']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

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