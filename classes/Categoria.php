<?php

namespace App\Classes;

class Categoria
{
  private $con;

  public function __construct($conexion)
  {
    $this->con = $conexion;
  }

  public function obtenerTodas()
  {
    $sql = "SELECT id_categoria, nom_categoria, descripcion FROM categoria";
    $result = $this->con->query($sql);

    if (!$result) {
      return false;
    }

    $categorias = array();
    while ($row = $result->fetch_assoc()) {
      $categorias[] = array(
        'id' => $row['id_categoria'],
        'nombre' => $row['nom_categoria'],
        'descripcion' => $row['descripcion']
      );
    }

    return $categorias;
  }

  public function obtenerPorId($id)
  {
    $sql = "SELECT id_categoria, nom_categoria, descripcion FROM categoria WHERE id_categoria = ?";
    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
      return null;
    }

    $row = $result->fetch_assoc();
    return array(
      'id' => $row['id_categoria'],
      'nombre' => $row['nom_categoria'],
      'descripcion' => $row['descripcion']
    );
  }
}
