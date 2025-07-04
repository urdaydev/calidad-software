<?php

namespace App\Classes;

class Subcategoria
{
  private $con;

  public function __construct($conexion)
  {
    $this->con = $conexion;
  }

  // Métodos para Tipos de Subcategoría
  public function crearTipoSubcategoria($nombre_tipo)
  {
    $sql = "INSERT INTO subcategoria_tipo (nombre_tipo) VALUES (?)";
    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("s", $nombre_tipo);
    return $stmt->execute();
  }

  public function listarTiposSubcategoria()
  {
    $sql = "SELECT * FROM subcategoria_tipo ORDER BY nombre_tipo";
    $result = $this->con->query($sql);
    if (!$result) {
      error_log("Error en la consulta de tipos de subcategoría: " . $this->con->error);
      return [];
    }
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  // Métodos para Subcategorías
  public function crearSubcategoria($id_subcategoria_tipo, $valor)
  {
    $sql = "INSERT INTO subcategoria (id_subcategoria_tipo, valor) VALUES (?, ?)";
    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("is", $id_subcategoria_tipo, $valor);
    return $stmt->execute();
  }

  public function listarSubcategoriasPorTipo($id_subcategoria_tipo)
  {
    $sql = "SELECT * FROM subcategoria WHERE id_subcategoria_tipo = ?";
    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("i", $id_subcategoria_tipo);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function listarSubcategorias()
  {
    $sql = "SELECT sc.id_subcategoria, sct.nombre_tipo, sc.valor, sc.estado 
            FROM subcategoria sc
            INNER JOIN subcategoria_tipo sct ON sc.id_subcategoria_tipo = sct.id_subcategoria_tipo
            ORDER BY sct.nombre_tipo, sc.valor";
    $result = $this->con->query($sql);
    if (!$result) {
      error_log("Error en la consulta de subcategorías: " . $this->con->error);
      return [];
    }
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function obtenerSubcategoriaPorId($id_subcategoria)
  {
    $sql = "SELECT sc.id_subcategoria, sc.valor, sc.id_subcategoria_tipo, sct.nombre_tipo 
            FROM subcategoria sc
            INNER JOIN subcategoria_tipo sct ON sc.id_subcategoria_tipo = sct.id_subcategoria_tipo
            WHERE sc.id_subcategoria = ?";
    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("i", $id_subcategoria);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }

  public function editarSubcategoria($id_subcategoria, $id_subcategoria_tipo, $valor)
  {
    $sql = "UPDATE subcategoria SET id_subcategoria_tipo = ?, valor = ? WHERE id_subcategoria = ?";
    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("isi", $id_subcategoria_tipo, $valor, $id_subcategoria);
    return $stmt->execute();
  }

  public function eliminarSubcategoria($id_subcategoria)
  {
    // Opcional: cambiar a borrado lógico (estado = 0) si se prefiere
    $sql = "DELETE FROM subcategoria WHERE id_subcategoria = ?";
    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("i", $id_subcategoria);
    return $stmt->execute();
  }

  public function obtenerTipoSubcategoriaPorId($id_subcategoria_tipo)
  {
    $sql = "SELECT * FROM subcategoria_tipo WHERE id_subcategoria_tipo = ?";
    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("i", $id_subcategoria_tipo);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }

  public function editarTipoSubcategoria($id_subcategoria_tipo, $nombre_tipo)
  {
    $sql = "UPDATE subcategoria_tipo SET nombre_tipo = ? WHERE id_subcategoria_tipo = ?";
    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("si", $nombre_tipo, $id_subcategoria_tipo);
    return $stmt->execute();
  }

  public function eliminarTipoSubcategoria($id_subcategoria_tipo)
  {
    // Opcional: Considerar el impacto en subcategorías existentes.
    // La restricción ON DELETE CASCADE las eliminará.
    $sql = "DELETE FROM subcategoria_tipo WHERE id_subcategoria_tipo = ?";
    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("i", $id_subcategoria_tipo);
    return $stmt->execute();
  }

  // Métodos para la asignación a productos
  public function asignarSubcategoriaAProducto($id_producto, $id_subcategoria)
  {
    $sql = "INSERT INTO producto_subcategoria (id_producto, id_subcategoria) VALUES (?, ?)";
    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("ii", $id_producto, $id_subcategoria);
    return $stmt->execute();
  }

  public function obtenerSubcategoriasDeProducto($id_producto)
  {
    $sql = "SELECT s.id_subcategoria, s.valor, st.nombre_tipo
                FROM producto_subcategoria ps
                JOIN subcategoria s ON ps.id_subcategoria = s.id_subcategoria
                JOIN subcategoria_tipo st ON s.id_subcategoria_tipo = st.id_subcategoria_tipo
                WHERE ps.id_producto = ?";
    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function eliminarAsignacionSubcategoria($id_producto, $id_subcategoria)
  {
    $sql = "DELETE FROM producto_subcategoria WHERE id_producto = ? AND id_subcategoria = ?";
    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("ii", $id_producto, $id_subcategoria);
    return $stmt->execute();
  }

  public function desasignarTodasSubcategoriasDeProducto($id_producto)
  {
    $sql = "DELETE FROM producto_subcategoria WHERE id_producto = ?";
    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("i", $id_producto);
    return $stmt->execute();
  }
}
