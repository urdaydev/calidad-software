<?php

namespace App\Classes;

class Producto
{
  private $con;

  public function __construct($conexion)
  {
    $this->con = $conexion;
  }

  public function listarProductosActivos()
  {
    $sql = "SELECT 
            producto.id_producto,
            categoria.id_categoria,
            categoria.nom_categoria,
            producto.nom_producto,
            producto.imagen,
            producto.descripcion,
            producto.precio 
            FROM producto 
            INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
            WHERE producto.estado = 1";

    $result = $this->con->query($sql);

    if (!$result) {
      return [];
    }

    $productos = array();
    while ($row = $result->fetch_assoc()) {
      $productos[] = array(
        'id' => (int)$row['id_producto'],
        'categoria_id' => (int)$row['id_categoria'],
        'categoria_nombre' => $row['nom_categoria'],
        'nombre' => $row['nom_producto'],
        'imagen' => $row['imagen'],
        'descripcion' => $row['descripcion'],
        'precio' => (float)$row['precio']
      );
    }

    return $productos;
  }

  public function buscarProductoPorId($id)
  {
    if (!is_numeric($id) || $id <= 0) {
      return null;
    }

    $sql = "SELECT * FROM producto WHERE id_producto = ?";
    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
      return null;
    }

    $row = $result->fetch_assoc();
    return array(
      'id' => (int)$row['id_producto'],
      'proveedor_id' => (int)$row['id_proveedor'],
      'categoria_id' => (int)$row['id_categoria'],
      'marca_id' => (int)$row['id_marca'],
      'nombre' => $row['nom_producto'],
      'imagen' => $row['imagen'],
      'descripcion' => $row['descripcion'],
      'precio' => (float)$row['precio'],
      'stock' => (int)$row['stock'],
      'stock_minimo' => (int)$row['stock_minimo'],
      'fecha_registro' => $row['fecha_registro'],
      'estado' => (bool)$row['estado']
    );
  }
}
