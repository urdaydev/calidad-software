<?php
// Api de categorias
include_once '../../config/db.php';
include_once '../../config/conexion.php';

// Obtener los datos para la actualización
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
  // Obtener los datos
  $data = json_decode(file_get_contents("php://input"));

  if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    // Error al decodificar JSON
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Error al decodificar JSON']);
    exit;
  }

  // 'id_cliente' => $id_cliente,
  // 'tipo_doc' => $tipo_doc,
  // 'n_doc' => $n_doc,
  // 'nombres' => $nombres,
  // 'a_paterno' => $a_paterno,
  // 'a_materno' => $a_materno
  // Validar los datos
  if (!isset($data->id_cliente, $data->tipo_doc, $data->n_doc, $data->nombres, $data->a_paterno, $data->a_materno, $data->f_nacimiento)) {
    // Cambiar el código de respuesta
    http_response_code(400); // Bad Request
    // Enviar respuesta JSON
    echo json_encode(['error' => 'Parámetros incompletos']);
    exit;
  }
  $id_cliente = $data->id_cliente;
  $tipo_doc = $data->tipo_doc;
  $n_doc = $data->n_doc;
  $nombres = $data->nombres;
  $a_paterno = $data->a_paterno;
  $a_materno = $data->a_materno;
  $f_nacimiento = $data->f_nacimiento;

  // Validar edad
  $hoy = new DateTime();
  $nacimiento = new DateTime($f_nacimiento);
  $edad = $hoy->diff($nacimiento)->y;

  if ($edad < 18) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'No se pueden registrar clientes menores de edad']);
    exit;
  }

  // Validar longitud del documento según tipo
  $n_doc_clean = removeQuotes($n_doc);
  if ($tipo_doc === 'DNI' && strlen($n_doc_clean) !== 8) {
    http_response_code(400);
    echo json_encode(['error' => 'El DNI debe tener 8 dígitos']);
    exit;
  } else if ($tipo_doc === 'CE' && (strlen($n_doc_clean) < 8 || strlen($n_doc_clean) > 20)) {
    http_response_code(400);
    echo json_encode(['error' => 'El CE debe tener entre 8 y 20 caracteres']);
    exit;
  }

  $id_cliente = removeQuotes($id_cliente);
  $tipo_doc = removeQuotes($tipo_doc);
  $nombres = removeQuotes($nombres);
  $a_paterno = removeQuotes($a_paterno);
  $a_materno = removeQuotes($a_materno);
  $f_nacimiento = removeQuotes($f_nacimiento);

  // Verificar que el cliente existe
  $sql = "SELECT * FROM persona INNER JOIN cliente ON persona.id_persona = cliente.id_persona WHERE cliente.id_cliente = ?";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("i", $id_cliente);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Cliente no encontrado']);
    exit;
  }

  $row = $result->fetch_assoc();
  $id_persona = $row['id_persona'];

  // Verificar que el nuevo documento no exista (excepto para el mismo cliente)
  $sql = "SELECT * FROM persona INNER JOIN cliente ON persona.id_persona = cliente.id_persona WHERE n_doc = ? AND cliente.id_cliente != ?";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("si", $n_doc_clean, $id_cliente);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    http_response_code(400);
    echo json_encode(['error' => 'El número de documento ya existe']);
    exit;
  }

  // Actualizar datos
  $sql = "UPDATE persona SET tipo_doc = ?, n_doc = ?, nombres = ?, a_paterno = ?, a_materno = ?, f_nacimiento = ? WHERE id_persona = ?";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("ssssssi", $tipo_doc, $n_doc_clean, $nombres, $a_paterno, $a_materno, $f_nacimiento, $id_persona);
  $stmt->execute();

  if ($stmt->affected_rows >= 0) {
    echo json_encode(['success' => 'Cliente actualizado']);
  } else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al actualizar el cliente']);
  }

  $stmt->close();
} else {
  // Obtener datos para mostrar
  if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT cliente.id_cliente, persona.tipo_doc, persona.n_doc, persona.nombres, persona.a_paterno, persona.a_materno, persona.f_nacimiento, cliente.estado FROM cliente inner join persona on cliente.id_persona = persona.id_persona WHERE cliente.id_cliente = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $id_cliente = $row['id_cliente'];
      $tipo_doc = $row['tipo_doc'];
      $n_doc = $row['n_doc'];
      $nombres = $row['nombres'];
      $a_paterno = $row['a_paterno'];
      $a_materno = $row['a_materno'];
      $f_nacimiento = $row['f_nacimiento'];

      // Enviar respuesta JSON
      header('Content-Type: application/json');
      echo json_encode([
        'id_cliente' => $id_cliente,
        'tipo_doc' => $tipo_doc,
        'n_doc' => $n_doc,
        'nombres' => $nombres,
        'a_paterno' => $a_paterno,
        'a_materno' => $a_materno,
        'f_nacimiento' => $f_nacimiento
      ]);
    } else {
      // Cliente no encontrado
      http_response_code(404); // Not Found
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Cliente no encontrado']);
    }

    $stmt->close();
  } else if ($_GET['n_doc']) {
    $n_doc = $_GET['n_doc'];
    $sql = "SELECT cliente.id_cliente, persona.tipo_doc, persona.n_doc, persona.nombres, persona.a_paterno, persona.a_materno, persona.f_nacimiento, cliente.estado FROM cliente inner join persona on cliente.id_persona = persona.id_persona WHERE persona.n_doc = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $n_doc);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $id_cliente = $row['id_cliente'];
      $tipo_doc = $row['tipo_doc'];
      $n_doc = $row['n_doc'];
      $nombres = $row['nombres'];
      $a_paterno = $row['a_paterno'];
      $a_materno = $row['a_materno'];
      $f_nacimiento = $row['f_nacimiento'];
      // Enviar respuesta JSON
      header('Content-Type: application/json');
      echo json_encode([
        'id_cliente' => $id_cliente,
        'tipo_doc' => $tipo_doc,
        'n_doc' => $n_doc,
        'nombres' => $nombres,
        'a_paterno' => $a_paterno,
        'a_materno' => $a_materno,
        'f_nacimiento' => $f_nacimiento
      ]);
    } else {
      // Cliente no encontrado
      http_response_code(404); // Not Found
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Cliente no encontrado']);
    }
  } else {
    // Parámetros incompletos
    http_response_code(400); // Bad Request
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Parámetros incompletos']);
  }
}
function removeQuotes($string)
{
  $string = str_replace("'", "", $string);
  $string = str_replace('"', '', $string);
  return $string;
}

$con->close();
