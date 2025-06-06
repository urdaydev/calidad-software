<?php

namespace App\Classes;

class Cliente
{
  private $con;

  public function __construct($conexion)
  {
    $this->con = $conexion;
  }

  public function buscarClientePorDocumento($numeroDocumento)
  {
    if (empty($numeroDocumento)) {
      return null;
    }

    $sql = "SELECT cliente.id_cliente, persona.tipo_doc, persona.n_doc, 
                       persona.nombres, persona.a_paterno, persona.a_materno, 
                       persona.f_nacimiento, cliente.estado 
                FROM cliente 
                INNER JOIN persona ON cliente.id_persona = persona.id_persona 
                WHERE persona.n_doc = ?";

    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("s", $numeroDocumento);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
      return null;
    }

    $row = $result->fetch_assoc();
    return array(
      'id' => (int)$row['id_cliente'],
      'tipo_documento' => $row['tipo_doc'],
      'numero_documento' => $row['n_doc'],
      'nombres' => $row['nombres'],
      'apellido_paterno' => $row['a_paterno'],
      'apellido_materno' => $row['a_materno'],
      'fecha_nacimiento' => $row['f_nacimiento']
    );
  }

  public function obtenerClientePorId($id)
  {
    if (!is_numeric($id) || $id <= 0) {
      return null;
    }

    $sql = "SELECT cliente.id_cliente, persona.tipo_doc, persona.n_doc, 
                       persona.nombres, persona.a_paterno, persona.a_materno, 
                       persona.f_nacimiento, cliente.estado 
                FROM cliente 
                INNER JOIN persona ON cliente.id_persona = persona.id_persona 
                WHERE cliente.id_cliente = ?";

    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
      return null;
    }

    $row = $result->fetch_assoc();
    return array(
      'id' => (int)$row['id_cliente'],
      'tipo_documento' => $row['tipo_doc'],
      'numero_documento' => $row['n_doc'],
      'nombres' => $row['nombres'],
      'apellido_paterno' => $row['a_paterno'],
      'apellido_materno' => $row['a_materno'],
      'fecha_nacimiento' => $row['f_nacimiento']
    );
  }

  public function listarClientesActivos()
  {
    $sql = "SELECT cliente.id_cliente, persona.tipo_doc, persona.n_doc, 
                       persona.nombres, persona.a_paterno, persona.a_materno, 
                       persona.f_nacimiento 
                FROM cliente 
                INNER JOIN persona ON cliente.id_persona = persona.id_persona 
                WHERE cliente.estado = 1";

    $result = $this->con->query($sql);

    if (!$result) {
      return [];
    }

    $clientes = array();
    while ($row = $result->fetch_assoc()) {
      $clientes[] = array(
        'id' => (int)$row['id_cliente'],
        'tipo_documento' => $row['tipo_doc'],
        'numero_documento' => $row['n_doc'],
        'nombres' => $row['nombres'],
        'apellido_paterno' => $row['a_paterno'],
        'apellido_materno' => $row['a_materno'],
        'fecha_nacimiento' => $row['f_nacimiento']
      );
    }

    return $clientes;
  }
}
