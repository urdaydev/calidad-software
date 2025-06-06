<?php

namespace App\Classes;

class Proveedor
{
  private $con;

  public function __construct($conexion)
  {
    $this->con = $conexion;
  }

  public function obtenerTodos()
  {
    $sql = "SELECT p.id_proveedor, e.n_ruc, e.razon_social, e.n_telefono, e.direccion, e.email 
                FROM proveedor p 
                INNER JOIN empresa e ON e.id_empresa = p.id_empresa";
    $result = $this->con->query($sql);

    if (!$result) {
      return [];
    }

    $proveedores = array();
    while ($row = $result->fetch_assoc()) {
      $proveedores[] = array(
        'id' => (int)$row['id_proveedor'],
        'ruc' => $row['n_ruc'],
        'razon_social' => $row['razon_social'],
        'telefono' => $row['n_telefono'],
        'direccion' => $row['direccion'],
        'email' => $row['email']
      );
    }

    return $proveedores;
  }

  public function obtenerPorId($id)
  {
    if (!is_numeric($id) || $id <= 0) {
      return null;
    }

    $sql = "SELECT p.id_proveedor, e.n_ruc, e.razon_social, e.n_telefono, e.direccion, e.email 
                FROM proveedor p 
                INNER JOIN empresa e ON e.id_empresa = p.id_empresa 
                WHERE p.id_proveedor = ?";

    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
      return null;
    }

    $row = $result->fetch_assoc();
    return array(
      'id' => (int)$row['id_proveedor'],
      'ruc' => $row['n_ruc'],
      'razon_social' => $row['razon_social'],
      'telefono' => $row['n_telefono'],
      'direccion' => $row['direccion'],
      'email' => $row['email']
    );
  }
}
