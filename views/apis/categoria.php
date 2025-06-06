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
    exit;
  }
  // Validar los datos
  if (!isset($data->id_categoria, $data->nombre, $data->descripcion)) {
    // Cambiar el código de respuesta
    http_response_code(400); // Bad Request
    // Enviar respuesta JSON
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Parámetros incompletos']);
    exit;
  }
  $id = $data->id_categoria;
  $nombre = $data->nombre;
  $descripcion = $data->descripcion;

  $id = removeQuotes($id);
  $nombre = removeQuotes($nombre);
  $descripcion = removeQuotes($descripcion);
  // Actualizar la categoría
  $sql = "UPDATE categoria SET nom_categoria = ?, descripcion = ? WHERE id_categoria = ?";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("ssi", $nombre, $descripcion, $id);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    http_response_code(200); // OK
    // Enviar respuesta JSON
    header('Content-Type: application/json');
    echo json_encode(['success' => 'Categoría actualizada']);
    exit;
  } else {
    http_response_code(500); // Internal Server Error
    // Enviar respuesta JSON
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error al actualizar la categoría']);
  }

  $stmt->close();
} else {
  // Obtener todas las categorías si no se especifica un ID
  if (!isset($_GET['id'])) {
    $sql = "SELECT id_categoria, nom_categoria, descripcion FROM categoria";
    $result = $con->query($sql);

    if ($result) {
      $categorias = array();
      while ($row = $result->fetch_assoc()) {
        $categorias[] = array(
          'id' => $row['id_categoria'],
          'nombre' => $row['nom_categoria'],
          'descripcion' => $row['descripcion']
        );
      }

      // Enviar respuesta JSON
      header('Content-Type: application/json');
      echo json_encode($categorias);
      exit;
    } else {
      // Error al obtener categorías
      http_response_code(500);
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Error al obtener las categorías']);
      exit;
    }
  }
  // Obtener datos para mostrar
  if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT nom_categoria, descripcion FROM categoria WHERE id_categoria = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $nombre = $row['nom_categoria'];
      $descripcion = $row['descripcion'];

      // Enviar respuesta JSON
      header('Content-Type: application/json');
      echo json_encode([
        'nombre' => $nombre,
        'descripcion' => $descripcion
      ]);
    } else {
      // Categoría no encontrada
      http_response_code(404); // Not Found
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Categoría no encontrada']);
    }

    $stmt->close();
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
