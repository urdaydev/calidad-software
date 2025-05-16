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
// id_cliente, nombres, a_peterno, a_materno
$sql = "SELECT cliente.id_cliente, persona.tipo_doc, persona.n_doc, persona.nombres, persona.a_paterno, persona.a_materno, cliente.estado FROM cliente inner join persona on cliente.id_persona = persona.id_persona where cliente.estado = 1";

$query = mysqli_query($con, $sql);
$id_usuario = $_SESSION['id_usuario'];
$nombre_tienda = getData($con, "SELECT nom_tienda FROM Tienda;", 'nom_tienda');
$nombres = getData($con, "SELECT nombres FROM persona inner join usuario on persona.id_persona = usuario.id_persona where usuario.id_usuario = $id_usuario;", 'nombres');
$tipo_usuario = getData($con, "SELECT nom_tipo_acceso FROM TipoAcceso inner join usuario on TipoAcceso.id_tipo_acceso = usuario.id_tipo_acceso where usuario.id_usuario = $id_usuario;", 'nom_tipo_acceso');


include("../utils/vars.php");

$items_nav = getItemsNav($items_nav, 'clientes');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <?php include("../head_module.php"); ?>
  <title> Clientes | Minimarket</title>
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
          <h3 class="title">Clientes</h3>
          <div class="search">
            <span class="bx bx-search"></span>
            <input type="text" placeholder="Buscar..." id="search-rows">
          </div>
          <div class="btn-container">
            <a class="btn btn-add">
              <span class="text">
                Nuevo Cliente
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
                <th>Tipo Doc.</th>
                <th>N° Documento</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody id="tbody-rows">
              <?php while ($row = mysqli_fetch_array($query)): ?>
                <tr>
                  <td><?= $row['id_cliente'] ?></td>
                  <td><?= $row['tipo_doc'] ?></td>
                  <td><?= $row['n_doc'] ?></td>
                  <td><?= $row['nombres'] ?></td>
                  <td><?= $row['a_paterno'] ?></td>
                  <td><?= $row['a_materno'] ?></td>
                  <td><a class="cliente-update btn-edit">Editar</a></td>
                  <td><a href="./delete/cliente.php?id=<?= $row['id_cliente'] ?>" class="cliente-delete">Eliminar</a></td>
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
          <span class="title">Crear Cliente</span>
          <span class="close">&times;</span>
        </div>
        <div class="body">
          <form action="./create/cliente.php" method="POST" class="form">
            <div class="form-control">
              <label for="tipo_doc">Tipo de Documento</label>
              <select name="tipo_doc" id="tipo_doc" required>
                <option value="DNI">DNI</option>
                <option value="CE">Carnet de Extranjería</option>
              </select>
            </div>
            <div class="form-control">
              <label for="n_doc">Número de Documento</label>
              <input type="text" placeholder="Ingrese número de documento" required name="n_doc" id="n_doc">
            </div>
            <div class="form-control">
              <label for="">Nombres</label>
              <input type="text" placeholder="Nombres del cliente" required name="nombres" id="nombres">
            </div>
            <div class="form-control">
              <label for="">Apellido Paterno</label>
              <input type="text" placeholder="Apellido Paterno del cliente" required name="a_paterno" id="a_paterno">
            </div>
            <div class="form-control">
              <label for="">Apellido Materno</label>
              <input type="text" placeholder="Apellido Materno del cliente" required name="a_materno" id="a_materno">
            </div>
            <div class="form-control">
              <label for="">Fecha de Nacimiento</label>
              <input type="date" required name="f_nacimiento" id="f_nacimiento">
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
          <span class="title">Editar Cliente</span>
          <span class="close">&times;</span>
        </div>
        <div class="body">
          <form class="form">
            <div class="form-control">
              <label for="tipo_doc">Tipo de Documento</label>
              <select name="tipo_doc" id="tipo_doc" required>
                <option value="DNI">DNI</option>
                <option value="CE">Carnet de Extranjería</option>
              </select>
            </div>
            <div class="form-control">
              <label for="n_doc">Número de Documento</label>
              <input type="text" placeholder="Ingrese número de documento" required name="n_doc" id="n_doc">
            </div>
            <div class="form-control">
              <label for="">Nombres</label>
              <input type="text" placeholder="Nombres del cliente" required name="nombres" id="nombres">
            </div>
            <div class="form-control">
              <label for="">Apellido Paterno</label>
              <input type="text" placeholder="Apellido Paterno del cliente" required name="a_paterno" id="a_paterno">
            </div>
            <div class="form-control">
              <label for="">Apellido Materno</label>
              <input type="text" placeholder="Apellido Materno del cliente" required name="a_materno" id="a_materno">
            </div>
            <div class="form-control">
              <label for="">Fecha de Nacimiento</label>
              <input type="date" required name="f_nacimiento" id="f_nacimiento">
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
  // Función para calcular la fecha máxima (18 años atrás desde hoy)
  function getMaxDate() {
    const today = new Date();
    today.setFullYear(today.getFullYear() - 18);
    return today.toISOString().split('T')[0];
  }

  // Establecer la fecha máxima en los inputs de fecha
  document.addEventListener('DOMContentLoaded', function() {
    const createDateInput = document.querySelector('.modalCreate #f_nacimiento');
    const updateDateInput = document.querySelector('.modalUpdate #f_nacimiento');

    const maxDate = getMaxDate();
    createDateInput.max = maxDate;
    updateDateInput.max = maxDate;
  });

  // Alerts del servidor
  // categoria.php?error=emptyField"
  // categoria.php?error=exist"
  // categoria.php?error=dbError"
  // categoria.php?success=create"
  // categoria.php?success=update"
  // categoria.php?success=delete"
  const urlParams = new URLSearchParams(window.location.search);
  const success = urlParams.get('success');
  const error = urlParams.get('error');
  const successMessages = {
    'create': 'Cliente creado exitosamente',
    'update': 'Cliente actualizado exitosamente',
    'delete': 'Cliente eliminado exitosamente'
  };
  const errorMessages = {
    'emptyField': 'Por favor, llene todos los campos',
    'exist': 'La categoría ya existe',
    'dbError': 'Error al conectar con la base de datos',
    'underage': 'No se pueden registrar clientes menores de edad'
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
<script src="../modal-js/data_clientes.js"></script>
<script src="../js/search_rows.js"></script>
<script>
  // Función para calcular la edad
  function calcularEdad(fechaNacimiento) {
    const hoy = new Date();
    const nacimiento = new Date(fechaNacimiento);
    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const mes = hoy.getMonth() - nacimiento.getMonth();

    if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
      edad--;
    }

    return edad;
  }

  // Validación de edad al enviar el formulario de creación
  document.querySelector('.modalCreate form').addEventListener('submit', function(e) {
    const fechaNacimiento = document.querySelector('.modalCreate #f_nacimiento').value;
    const edad = calcularEdad(fechaNacimiento);

    if (edad < 18) {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'No se pueden registrar clientes menores de edad',
        text: 'El cliente debe tener al menos 18 años.',
        showConfirmButton: true
      });
    }
  });

  // Validación de edad al actualizar
  document.querySelector('#btn-update').addEventListener('click', function(e) {
    const fechaNacimiento = document.querySelector('.modalUpdate #f_nacimiento').value;
    const edad = calcularEdad(fechaNacimiento);

    if (edad < 18) {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'No se pueden registrar clientes menores de edad',
        text: 'El cliente debe tener al menos 18 años.',
        showConfirmButton: true
      });
      return;
    }
    // Aquí continuaría el código existente para la actualización
  });

  const inputDniCreate = document.querySelector('.modalCreate #n_doc');
  const tipoDocCreate = document.querySelector('.modalCreate #tipo_doc');
  const inputDniUpdate = document.querySelector('.modalUpdate #n_doc');
  const tipoDocUpdate = document.querySelector('.modalUpdate #tipo_doc');

  // Función para validar documento
  function validateDocument(value, type, isUpdate = false) {
    const dniClean = cleanString(value);
    const modalClass = isUpdate ? '.modalUpdate' : '.modalCreate';

    if (type === 'DNI') {
      if (dniClean.length === 8) {
        if (!isUpdate) {
          const token = 'token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im1hc2FkZTk5NDVAaWJ0cmFkZXMuY29tIn0.HscnB_OWPfEVIzZuHOeMh4OUj4h92U6t46DpPUTutXY';
          const apiUrl = `https://dniruc.apisperu.com/api/v1/dni/${dniClean}?${token}`;
          fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
              const {
                dni,
                nombres,
                apellidoPaterno,
                apellidoMaterno
              } = data;
              document.querySelector(`${modalClass} #nombres`).value = nombres || '';
              document.querySelector(`${modalClass} #a_paterno`).value = apellidoPaterno || '';
              document.querySelector(`${modalClass} #a_materno`).value = apellidoMaterno || '';
              // Si no existe el dni
              if (!dni) {
                Swal.fire({
                  icon: 'error',
                  title: 'DNI no encontrado',
                  showConfirmButton: false,
                  timer: 1500
                })
              }
            })
            .catch(error => {
              console.log(error);
            });
        }
        return true;
      } else {
        Swal.fire({
          icon: 'error',
          title: 'DNI inválido',
          text: 'El DNI debe tener 8 dígitos',
          showConfirmButton: false,
          timer: 1500
        });
        return false;
      }
    } else if (type === 'CE') {
      if (dniClean.length >= 8 && dniClean.length <= 20) {
        return true;
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Carnet de Extranjería inválido',
          text: 'El CE debe tener entre 8 y 20 caracteres',
          showConfirmButton: false,
          timer: 1500
        });
        return false;
      }
    }
  }

  // Validación para el modal de crear
  inputDniCreate.addEventListener('change', (e) => {
    const value = e.target.value;
    const type = tipoDocCreate.value;
    validateDocument(value, type, false);
  });

  // Validación para el modal de editar
  inputDniUpdate.addEventListener('change', (e) => {
    const value = e.target.value;
    const type = tipoDocUpdate.value;
    validateDocument(value, type, true);
  });

  // Limpiar campos cuando se cambia el tipo de documento en crear
  tipoDocCreate.addEventListener('change', () => {
    document.querySelector('.modalCreate #n_doc').value = '';
    document.querySelector('.modalCreate #nombres').value = '';
    document.querySelector('.modalCreate #a_paterno').value = '';
    document.querySelector('.modalCreate #a_materno').value = '';
  });

  // Limpiar campos cuando se cambia el tipo de documento en editar
  tipoDocUpdate.addEventListener('change', () => {
    document.querySelector('.modalUpdate #n_doc').value = '';
  });

  // funcion para quitar los espacios en blanco, puntos y comas
  function cleanString(string) {
    string = string.replace(/\s/g, '');
    string = string.replace(/\./g, '');
    string = string.replace(/\,/g, '');
    return string;
  }
</script>

</html>