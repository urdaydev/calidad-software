<?php
// Api de productos
include_once '../../config/db.php';
include_once '../../config/conexion.php';

// Obtener los datos para la actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $id_producto = $_POST['id_producto'];
  $id_proveedor = $_POST['id_proveedor'];
  $id_categoria = $_POST['id_categoria'];
  $nom_producto = $_POST['nom_producto'];
  $descripcion = $_POST['descripcion'];
  $precio = $_POST['precio'];
  $stock = $_POST['stock'];
  $stock_minimo = $_POST['stock_minimo'];
  $fecha_vencimiento = $_POST['fecha_vencimiento'];

  $id_producto = removeQuotes($id_producto);
  $id_proveedor = removeQuotes($id_proveedor);
  $id_categoria = removeQuotes($id_categoria);
  $nom_producto = removeQuotes($nom_producto);
  $descripcion = removeQuotes($descripcion);
  $precio = removeQuotes($precio);
  $stock = removeQuotes($stock);
  $stock_minimo = removeQuotes($stock_minimo);
  $fecha_vencimiento = removeQuotes($fecha_vencimiento);

  // Validar los datos
  if (!isset($id_producto, $id_proveedor, $id_categoria, $nom_producto, $descripcion, $precio, $stock, $stock_minimo, $fecha_vencimiento)) {
    // Cambiar el código de respuesta
    http_response_code(400); // Bad Request
    // Enviar respuesta JSON
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Parámetros incompletos']);
    exit;
  }
  // Verificar si existe el producto
  $sql = "SELECT * FROM producto WHERE id_producto = ?";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("i", $id_producto);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows === 0) {
    // Producto no existe
    http_response_code(404); // Not Found
    // Enviar respuesta JSON
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Producto no encontrado']);
    exit;
  } // Verificar si envio el archivo imagen que es opcional
  // Valido si envio la imagen
  if (isset($_FILES['imagen'])) {
    // Obtengo la imagen
    $imagen = $_FILES['imagen'];
    // Reemplazo la imagen anterior con la nueva
    $dir_imagen_prev = "../../images/productos/$id_producto.jpg";
    if (file_exists($dir_imagen_prev)) {
      unlink($dir_imagen_prev);
    }
    // Guardo la nueva imagen
    move_uploaded_file($imagen['tmp_name'], "../../images/productos/$id_producto.jpg");
    // Actualizamos la ruta de la imagen en la base de datos
    $sql = "UPDATE producto SET id_proveedor = ?, id_categoria = ?, nom_producto = ?, descripcion = ?, precio = ?, stock = ?, stock_minimo = ?, fecha_vencimiento = ? WHERE id_producto = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iisssiisi", $id_proveedor, $id_categoria, $nom_producto, $descripcion, $precio, $stock, $stock_minimo, $fecha_vencimiento, $id_producto);
  }
  // Si no envio la imagen
  else if (!isset($_FILES['imagen'])) {
    // Actualizar la categoría
    $sql = "UPDATE producto SET id_proveedor = ?, id_categoria = ?, nom_producto = ?, descripcion = ?, precio = ?, stock = ?, stock_minimo = ?, fecha_vencimiento = ? WHERE id_producto = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iisssiisi", $id_proveedor, $id_categoria, $nom_producto, $descripcion, $precio, $stock, $stock_minimo, $fecha_vencimiento, $id_producto);
  }
  if ($stmt->execute()) {
    // La consulta se ejecutó correctamente
    // Cambiar el código de respuesta
    http_response_code(200); // OK
    // Enviar respuesta JSON
    header('Content-Type: application/json');
    echo json_encode(['success' => 'Producto actualizado correctamente']);
  } else {
    // La consulta falló
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error al ejecutar la consulta']);
    exit;
  }
  $stmt->close();
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Obtener datos para mostrar
  if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM producto WHERE id_producto = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

      // id_producto INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      // -- Llave foranea
      // id_categoria INT NOT NULL,
      // id_proveedor INT,
      // id_marca INT,
      // -- Atributos
      // nom_producto VARCHAR(50) NOT NULL,
      // imagen VARCHAR(250) NOT NULL,
      // descripcion VARCHAR(250) NOT NULL,
      // precio DECIMAL(6,2) NOT NULL,
      // stock INT NOT NULL,
      // fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
      // estado BINARY NOT NULL DEFAULT 1,

      $row = $result->fetch_assoc();
      $id_proveedor = $row['id_proveedor'];
      $id_categoria = $row['id_categoria'];
      $id_marca = $row['id_marca'];
      $nom_producto = $row['nom_producto'];
      $imagen = $row['imagen'];
      $descripcion = $row['descripcion'];
      $precio = $row['precio'];
      $stock = $row['stock'];
      $stock_minimo = $row['stock_minimo'];
      $fecha_registro = $row['fecha_registro'];
      $fecha_vencimiento = $row['fecha_vencimiento'];
      $estado = $row['estado'];

      // Enviar respuesta JSON
      header('Content-Type: application/json');
      echo json_encode([
        'id_producto' => $id,
        'id_proveedor' => $id_proveedor,
        'id_categoria' => $id_categoria,
        'id_marca' => $id_marca,
        'nom_producto' => $nom_producto,
        'imagen' => $imagen,
        'descripcion' => $descripcion,
        'precio' => $precio,
        'stock' => $stock,
        'stock_minimo' => $stock_minimo,
        'fecha_registro' => $fecha_registro,
        'fecha_vencimiento' => $fecha_vencimiento,
        'estado' => $estado
      ]);
    } else {
      // Categoría no encontrada
      http_response_code(404); // Not Found
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Producto no encontrado']);
    }

    $stmt->close();
  } else if (isset($_GET['all'])) {
    // Obtener todas los productos, y devolver un JSON con todos los productos
    $sql = "SELECT 
        producto.id_producto,
        categoria.id_categoria,
        categoria.nom_categoria,
        producto.nom_producto,
        producto.imagen,
        producto.descripcion,
        producto.precio 
        FROM producto inner join categoria on producto.id_categoria = categoria.id_categoria
        WHERE producto.estado = 1";
    $result = $con->query($sql);
    // Obtener los datos de la tabla y guardarlos en un array
    $productos = [];
    while ($row = $result->fetch_assoc()) {
      $productos[] = $row;
    }
    // Enviar respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($productos);
  } else {
    // Enviar un bad request
    http_response_code(400); // Bad Request
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Bad Request']);
  }
}
function removeQuotes($string)
{
  $string = str_replace("'", "", $string);
  $string = str_replace('"', '', $string);
  return $string;
}

$con->close();
