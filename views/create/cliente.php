<?php

// recibir estos datos del formulario
// <div class="form-control">
// <label for="">Dni</label>
// <input type="text" placeholder="Dni del cliente" required name="dni" id="dni">
// </div>
// <div class="form-control">
// <label for="">Nombres</label>
// <input type="text" placeholder="Nombres del cliente" required name="nombres" id="nombres">
// </div>
// <div class="form-control">
// <label for="">Apellido Paterno</label>
// <input type="text" placeholder="Apellido Paterno del cliente" required name="a_paterno" id="a_paterno">
// </div>
// <div class="form-control">
// <label for="">Apellido Materno</label>
// <input type="text" placeholder="Apellido Materno del cliente" required name="a_materno" id="a_materno">
// </div>



// Comprobar que los campos no esten vacios
if (!isset($_POST['dni']) || !isset($_POST['nombres']) || !isset($_POST['a_paterno']) || !isset($_POST['a_materno']) || !isset($_POST['f_nacimiento'])) {
  header("Location: ../clientes.php?error=emptyField");
  exit;
} else {
  require_once('../../config/db.php');
  require_once('../../config/conexion.php');
  $dni = $_POST['dni'];
  $nombres = $_POST['nombres'];
  $a_paterno = $_POST['a_paterno'];
  $a_materno = $_POST['a_materno'];
  $f_nacimiento = $_POST['f_nacimiento'];

  // Validar edad
  $hoy = new DateTime();
  $nacimiento = new DateTime($f_nacimiento);
  $edad = $hoy->diff($nacimiento)->y;

  if ($edad < 18) {
    header("Location: ../clientes.php?error=underage");
    exit;
  }

  // Quitar ' y "" para evitar errores en la base de datos
  $dni = removeQuotes($dni);
  $nombres = removeQuotes($nombres);
  $a_paterno = removeQuotes($a_paterno);
  $a_materno = removeQuotes($a_materno);
  $f_nacimiento = removeQuotes($f_nacimiento);

  // Comprobar que el dni no exista
  $sql = "SELECT * FROM cliente inner join persona on cliente.id_persona = persona.id_persona WHERE dni = '$dni'";
  $query = mysqli_query($con, $sql);
  $result = mysqli_fetch_array($query);

  if ($result > 0) {
    header("Location: ../clientes.php?error=exist");
    exit;
  } else {
    // Insertar la persona en la base de datos
    $sql = "INSERT INTO persona (dni, nombres, a_paterno, a_materno, f_nacimiento) VALUES ('$dni', '$nombres', '$a_paterno', '$a_materno', '$f_nacimiento')";
    $query = mysqli_query($con, $sql);
    // Obtener el id de la persona recien creada
    $sql = "SELECT * FROM persona WHERE dni = '$dni'";
    $query = mysqli_query($con, $sql);
    $result = mysqli_fetch_array($query);
    $id_persona = $result['id_persona'];
    // Insertar el cliente en la base de datos
    $sql = "INSERT INTO cliente (id_persona) VALUES ('$id_persona')";
    $query = mysqli_query($con, $sql);

    if ($query) {
      header("Location: ../clientes.php?success=create");
    } else {
      header("Location: ../clientes.php?error=bdError");
    }
  }
}
function removeQuotes($string)
{
  $string = str_replace("'", "", $string);
  $string = str_replace('"', '', $string);
  return $string;
}
