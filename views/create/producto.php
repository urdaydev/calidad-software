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

// Check if the fields are not empty
if (empty($nombre) || empty($descripcion) || empty($precio) || empty($stock) || empty($stock_minimo) || empty($imagen)) {
  header("Location: ../productos.php?error=emptyField");
  exit;
} else {
  require_once('../../config/db.php');
  require_once('../../config/conexion.php');
  // Check if the provider already exists
  $sql = "SELECT * FROM producto WHERE nom_producto = '$nombre'";
  $query = mysqli_query($con, $sql);
  $result = mysqli_fetch_array($query);

  if ($result > 0) {
    header("Location: ../productos.php?error=exist");
    exit;
  } else {
    $sql = "INSERT INTO `producto` (`id_categoria`, `id_proveedor`, `nom_producto`, `imagen`, `descripcion`, `precio`, `stock`, `stock_minimo`) VALUES ('$id_categoria', '$id_proveedor', '$nombre', 'ruta/imagen', '$descripcion', '$precio', '$stock', '$stock_minimo')";
    $query = mysqli_query($con, $sql);
    // Obtener el id del producto
    $id_producto = mysqli_insert_id($con);
    // Mover la imagen a la carpeta images/productos

    move_uploaded_file($_FILES['imagen']['tmp_name'], "../../images/productos/$id_producto.jpg");
    $imagen = "images/productos/$id_producto.jpg";
    // Actualizamos la ruta de la imagen en la base de datos
    $sql = "UPDATE producto SET imagen = '$imagen' WHERE id_producto = $id_producto";
    $query = mysqli_query($con, $sql);

    if ($query) {
      header("Location: ../productos.php?success=create");
    } else {
      header("Location: ../productos.php?error=bdError");
    }
  }
}
