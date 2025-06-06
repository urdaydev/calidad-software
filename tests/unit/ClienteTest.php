<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Classes\Cliente;
use mysqli;

class ClienteTest extends TestCase
{
  private $conexion;
  private $cliente;

  protected function setUp(): void
  {
    $this->conexion = new mysqli(
      'localhost',
      'root',
      '',
      'bd_cordon_y_la_rosa'
    );

    $this->cliente = new Cliente($this->conexion);
  }

  public function testBuscarClientePorDNIRetornaInformacionCompleta()
  {
    $cliente = $this->cliente->buscarClientePorDocumento('12345678');

    $this->assertIsArray($cliente, "El método debe retornar un array con la información del cliente");
    $this->assertArrayHasKey('id', $cliente, "El cliente debe tener un campo 'id'");
    $this->assertArrayHasKey('tipo_documento', $cliente, "El cliente debe tener un campo 'tipo_documento'");
    $this->assertArrayHasKey('numero_documento', $cliente, "El cliente debe tener un campo 'numero_documento'");
    $this->assertArrayHasKey('nombres', $cliente, "El cliente debe tener un campo 'nombres'");
    $this->assertArrayHasKey('apellido_paterno', $cliente, "El cliente debe tener un campo 'apellido_paterno'");

    fwrite(STDOUT, "\n✓ Test búsqueda por DNI completado exitosamente: Se obtuvo la información completa del cliente\n");
  }

  public function testVerificarDatosPersonalesDeClienteRegistrado()
  {
    $cliente = $this->cliente->obtenerClientePorId(1);

    $this->assertIsArray($cliente, "El método debe retornar un array con los datos personales del cliente");
    $this->assertArrayHasKey('nombres', $cliente, "El cliente debe tener un campo 'nombres'");
    $this->assertArrayHasKey('apellido_paterno', $cliente, "El cliente debe tener un campo 'apellido_paterno'");
    $this->assertArrayHasKey('apellido_materno', $cliente, "El cliente debe tener un campo 'apellido_materno'");
    $this->assertArrayHasKey('fecha_nacimiento', $cliente, "El cliente debe tener un campo 'fecha_nacimiento'");

    fwrite(STDOUT, "\n✓ Test verificación de datos personales completado exitosamente: Se confirmaron todos los campos requeridos\n");
  }

  public function testListarClientesActivosConDatosCompletos()
  {
    $clientes = $this->cliente->listarClientesActivos();

    $this->assertIsArray($clientes, "El método debe retornar un array de clientes activos");

    if (count($clientes) > 0) {
      $primerCliente = $clientes[0];
      $this->assertArrayHasKey('id', $primerCliente, "Cada cliente debe tener un campo 'id'");
      $this->assertArrayHasKey('tipo_documento', $primerCliente, "Cada cliente debe tener un campo 'tipo_documento'");
      $this->assertArrayHasKey('numero_documento', $primerCliente, "Cada cliente debe tener un campo 'numero_documento'");
      $this->assertArrayHasKey('nombres', $primerCliente, "Cada cliente debe tener un campo 'nombres'");
    }

    fwrite(STDOUT, "\n✓ Test listado de clientes activos completado exitosamente: Se verificó la estructura de datos completa\n");
  }

  protected function tearDown(): void
  {
    $this->conexion->close();
  }
}
