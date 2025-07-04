<?php
header('Content-Type: application/json');

require_once('../../config/db.php');
require_once('../../config/conexion.php');
require_once('../../classes/Producto.php');
require_once('../../classes/Subcategoria.php');

$productoManager = new App\Classes\Producto($con);
$subcategoriaManager = new App\Classes\Subcategoria($con);
$response = ['success' => false, 'message' => 'Invalid Request'];
$method = $_SERVER['REQUEST_METHOD'];

// Obtener datos de un producto para el modal de edición
if ($method === 'GET' && isset($_GET['id'])) {
  $id = (int)$_GET['id'];
  $producto = $productoManager->buscarProductoPorId($id);
  if ($producto) {
    // Las subcategorías ya están incluidas por el método buscarProductoPorId
    echo json_encode($producto);
    exit;
  } else {
    $response['message'] = 'Producto no encontrado';
  }
}

// Actualizar un producto
if ($method === 'POST' && isset($_POST['id_producto'])) {
  $id_producto = (int)$_POST['id_producto'];
  $id_proveedor = (int)$_POST['id_proveedor'];
  $id_categoria = (int)$_POST['id_categoria'];
  $nom_producto = mysqli_real_escape_string($con, $_POST['nom_producto']);
  $descripcion = mysqli_real_escape_string($con, $_POST['descripcion']);
  $precio = (float)$_POST['precio'];
  $stock = (int)$_POST['stock'];
  $stock_minimo = (int)$_POST['stock_minimo'];
  $fecha_vencimiento = $_POST['fecha_vencimiento'];
  $subcategorias = $_POST['subcategorias'] ?? [];

  $sql = "UPDATE producto SET 
                id_proveedor = ?, 
                id_categoria = ?, 
                nom_producto = ?, 
                descripcion = ?, 
                precio = ?, 
                stock = ?, 
                stock_minimo = ?, 
                fecha_vencimiento = ? 
            WHERE id_producto = ?";

  $stmt = $con->prepare($sql);
  $stmt->bind_param("iissdisss", $id_proveedor, $id_categoria, $nom_producto, $descripcion, $precio, $stock, $stock_minimo, $fecha_vencimiento, $id_producto);

  if ($stmt->execute()) {
    // Manejo de imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
      $ruta_imagen = "../../images/productos/$id_producto.jpg";
      move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen);
      $imagen_db = "images/productos/$id_producto.jpg";
      $sql_update_img = "UPDATE producto SET imagen = ? WHERE id_producto = ?";
      $stmt_img = $con->prepare($sql_update_img);
      $stmt_img->bind_param("si", $imagen_db, $id_producto);
      $stmt_img->execute();
    }

    // Actualizar subcategorías
    $subcategoriaManager->desasignarTodasSubcategoriasDeProducto($id_producto);
    if (!empty($subcategorias) && is_array($subcategorias)) {
      foreach ($subcategorias as $id_subcategoria) {
        $subcategoriaManager->asignarSubcategoriaAProducto($id_producto, (int)$id_subcategoria);
      }
    }

    $response = ['success' => true];
  } else {
    $response['message'] = "Error al actualizar el producto: " . $stmt->error;
  }
} else {
  // Si no es GET con id ni POST con id_producto, puede ser otra operación
  // o un error. Se mantiene la respuesta por defecto.
}

echo json_encode($response);
