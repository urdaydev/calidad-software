<?php
header('Content-Type: application/json');

session_start();
if (!isset($_SESSION['user_login_status']) || $_SESSION['user_login_status'] != 1) {
  echo json_encode(['success' => false, 'message' => 'Not authorized']);
  exit;
}

require_once("../../config/db.php");
require_once("../../config/conexion.php");
require_once("../../classes/Subcategoria.php");

$subcategoriaManager = new App\Classes\Subcategoria($con);
$response = ['success' => false, 'message' => 'Invalid action'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET' && isset($_GET['action'])) {
  $action = $_GET['action'];
  if ($action == 'get_tipo' && isset($_GET['id'])) {
    $data = $subcategoriaManager->obtenerTipoSubcategoriaPorId((int)$_GET['id']);
    if ($data) {
      $response = ['success' => true, 'data' => $data];
    } else {
      $response['message'] = 'Tipo no encontrado.';
    }
  } elseif ($action == 'get_sub' && isset($_GET['id'])) {
    $data = $subcategoriaManager->obtenerSubcategoriaPorId((int)$_GET['id']);
    if ($data) {
      $response = ['success' => true, 'data' => $data];
    } else {
      $response['message'] = 'Subcategoría no encontrada.';
    }
  }
} elseif ($method === 'POST' && isset($_POST['action'])) {
  $action = $_POST['action'];

  switch ($action) {
    case 'add_tipo':
      if (!empty($_POST['nombre_tipo'])) {
        if ($subcategoriaManager->crearTipoSubcategoria(trim($_POST['nombre_tipo']))) {
          $response = ['success' => true];
        } else {
          $response['message'] = 'Error al crear el tipo.';
        }
      } else {
        $response['message'] = 'El nombre no puede estar vacío.';
      }
      break;

    case 'edit_tipo':
      if (!empty($_POST['id_subcategoria_tipo']) && !empty($_POST['nombre_tipo'])) {
        if ($subcategoriaManager->editarTipoSubcategoria((int)$_POST['id_subcategoria_tipo'], trim($_POST['nombre_tipo']))) {
          $response = ['success' => true];
        } else {
          $response['message'] = 'Error al editar el tipo.';
        }
      } else {
        $response['message'] = 'Datos incompletos.';
      }
      break;

    case 'delete_tipo':
      if (!empty($_POST['id_subcategoria_tipo'])) {
        if ($subcategoriaManager->eliminarTipoSubcategoria((int)$_POST['id_subcategoria_tipo'])) {
          $response = ['success' => true];
        } else {
          $response['message'] = 'Error al eliminar el tipo.';
        }
      } else {
        $response['message'] = 'ID no proporcionado.';
      }
      break;

    case 'add_subcategoria':
      if (!empty($_POST['id_subcategoria_tipo']) && !empty($_POST['valor'])) {
        if ($subcategoriaManager->crearSubcategoria((int)$_POST['id_subcategoria_tipo'], trim($_POST['valor']))) {
          $response = ['success' => true];
        } else {
          $response['message'] = 'Error al crear la subcategoría.';
        }
      } else {
        $response['message'] = 'Datos incompletos.';
      }
      break;

    case 'edit_subcategoria':
      if (!empty($_POST['id_subcategoria']) && !empty($_POST['id_subcategoria_tipo']) && !empty($_POST['valor'])) {
        if ($subcategoriaManager->editarSubcategoria((int)$_POST['id_subcategoria'], (int)$_POST['id_subcategoria_tipo'], trim($_POST['valor']))) {
          $response = ['success' => true];
        } else {
          $response['message'] = 'Error al editar la subcategoría.';
        }
      } else {
        $response['message'] = 'Datos incompletos.';
      }
      break;

    case 'delete_subcategoria':
      if (!empty($_POST['id_subcategoria'])) {
        if ($subcategoriaManager->eliminarSubcategoria((int)$_POST['id_subcategoria'])) {
          $response = ['success' => true];
        } else {
          $response['message'] = 'Error al eliminar la subcategoría.';
        }
      } else {
        $response['message'] = 'ID no proporcionado.';
      }
      break;
  }
}

echo json_encode($response);
