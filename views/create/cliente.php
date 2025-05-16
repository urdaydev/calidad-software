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
if (empty($_POST['tipo_doc']) || empty($_POST['n_doc']) || empty($_POST['nombres']) || empty($_POST['a_paterno']) || empty($_POST['a_materno']) || empty($_POST['f_nacimiento'])) {
  header("Location: ../clientes.php?error=emptyField");
  exit;
}

require_once('../../config/db.php');
require_once('../../config/conexion.php');

$tipo_doc = $_POST['tipo_doc'];
$n_doc = $_POST['n_doc'];
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

// Validar longitud del documento según tipo
$n_doc_clean = removeQuotes($n_doc);
if ($tipo_doc === 'DNI' && strlen($n_doc_clean) !== 8) {
  header("Location: ../clientes.php?error=invalidDoc&type=dni");
  exit;
} else if ($tipo_doc === 'CE' && (strlen($n_doc_clean) < 8 || strlen($n_doc_clean) > 20)) {
  header("Location: ../clientes.php?error=invalidDoc&type=ce");
  exit;
}

// Quitar caracteres especiales
$tipo_doc = removeQuotes($tipo_doc);
$nombres = removeQuotes($nombres);
$a_paterno = removeQuotes($a_paterno);
$a_materno = removeQuotes($a_materno);
$f_nacimiento = removeQuotes($f_nacimiento);

// Comprobar que el documento no exista
$sql = "SELECT * FROM cliente inner join persona on cliente.id_persona = persona.id_persona WHERE n_doc = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $n_doc_clean);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  header("Location: ../clientes.php?error=exist");
  exit;
}

// Insertar la persona en la base de datos
$sql = "INSERT INTO persona (tipo_doc, n_doc, nombres, a_paterno, a_materno, f_nacimiento) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("ssssss", $tipo_doc, $n_doc_clean, $nombres, $a_paterno, $a_materno, $f_nacimiento);
$stmt->execute();

if ($stmt->affected_rows > 0) {
  // Obtener el id de la persona recién creada
  $id_persona = $stmt->insert_id;

  // Insertar el cliente en la base de datos
  $sql = "INSERT INTO cliente (id_persona) VALUES (?)";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("i", $id_persona);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    header("Location: ../clientes.php?success=create");
  } else {
    header("Location: ../clientes.php?error=dbError");
  }
} else {
  header("Location: ../clientes.php?error=dbError");
}

$stmt->close();
$con->close();

function removeQuotes($string)
{
  $string = str_replace("'", "", $string);
  $string = str_replace('"', '', $string);
  return $string;
}
