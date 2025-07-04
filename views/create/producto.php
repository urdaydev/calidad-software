<?php

// Registrar Producto
$id_categoria = $_POST['categoria'];
$id_proveedor = $_POST['proveedor'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];
$stock_minimo = $_POST['stock_minimo'];
$imagen = $_FILES['imagen']['name'];
$imagen_tmp = $_FILES['imagen']['tmp_name'];
$subcategorias = $_POST['subcategorias'] ?? [];
$imagen_db = "images/productos/default.jpg"; // Imagen por defecto

// Check if the fields are not empty
if (empty($nombre) || empty($descripcion) || empty($precio) || empty($stock) || empty($stock_minimo)) {
  header("Location: ../productos.php?error=emptyField");
  exit;
} else {
  require_once('../../config/db.php');
  require_once('../../config/conexion.php');
  require_once("../../classes/Subcategoria.php");
  $subcategoriaManager = new App\Classes\Subcategoria($con);

  // Check if the provider already exists
  $sql = "SELECT * FROM producto WHERE nom_producto = '$nombre'";
  $query = mysqli_query($con, $sql);
  $result = mysqli_fetch_array($query);

  if ($result > 0) {
    header("Location: ../productos.php?error=exist");
    exit;
  } else {
    // Escapar las entradas del usuario para prevenir inyección SQL
    $nombre = mysqli_real_escape_string($con, $nombre);
    $descripcion = mysqli_real_escape_string($con, $descripcion);
    $id_categoria = (int)$id_categoria;
    $id_proveedor = (int)$id_proveedor;
    $precio = (float)$precio;
    $stock = (int)$stock;
    $stock_minimo = (int)$stock_minimo;

    // Se inserta el producto primero para obtener el ID
    $sql_insert = "INSERT INTO producto (id_categoria, id_proveedor, nom_producto, descripcion, precio, stock, stock_minimo, imagen, fecha_vencimiento) VALUES ('$id_categoria', '$id_proveedor', '$nombre', '$descripcion', '$precio', '$stock', '$stock_minimo', '', NOW())";
    $query_insert = mysqli_query($con, $sql_insert);

    if ($query_insert) {
      $id_producto = mysqli_insert_id($con);

      // Mover la imagen si se proporcionó una
      if (!empty($imagen)) {
        $ruta_imagen = "../../images/productos/$id_producto.jpg";
        if (move_uploaded_file($imagen_tmp, $ruta_imagen)) {
          $imagen_db = "images/productos/$id_producto.jpg";
        }
      }

      // Actualizamos la ruta de la imagen en la base de datos (la subida o la por defecto)
      $sql_update = "UPDATE producto SET imagen = '$imagen_db' WHERE id_producto = $id_producto";
      mysqli_query($con, $sql_update);

      // Asignar subcategorías
      if (!empty($subcategorias) && is_array($subcategorias)) {
        foreach ($subcategorias as $id_subcategoria) {
          $subcategoriaManager->asignarSubcategoriaAProducto($id_producto, (int)$id_subcategoria);
        }
      }

      header("Location: ../productos.php?success=create");
    } else {
      header("Location: ../productos.php?error=bdError");
    }
  }
}
