<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Classes\Proveedor;
use mysqli;

class ProveedorTest extends TestCase
{
  private $conexion;
  private $proveedor;

  protected function setUp(): void
  {
    $this->conexion = new mysqli(
      'localhost',
      'root',
      '',
      'bd_cordon_y_la_rosa'
    );

    $this->proveedor = new Proveedor($this->conexion);
  }

  public function testObtenerTodosRetornaArray()
  {
    $proveedores = $this->proveedor->obtenerTodos();
    $this->assertIsArray($proveedores, "El método obtenerTodos() debe retornar un array de proveedores");

    if (count($proveedores) > 0) {
      $primerProveedor = $proveedores[0];
      $this->assertArrayHasKey('id', $primerProveedor, "El proveedor debe tener un campo 'id'");
      $this->assertArrayHasKey('ruc', $primerProveedor, "El proveedor debe tener un campo 'ruc'");
      $this->assertArrayHasKey('razon_social', $primerProveedor, "El proveedor debe tener un campo 'razon_social'");
    }

    fwrite(STDOUT, "\n✓ Test obtenerTodos completado exitosamente: La lista de proveedores se obtuvo correctamente\n");
  }

  public function testObtenerPorIdExistente()
  {
    $proveedor = $this->proveedor->obtenerPorId(1);
    $this->assertIsArray($proveedor, "El proveedor obtenido debe ser un array");
    $this->assertArrayHasKey('id', $proveedor, "El proveedor debe tener un campo 'id'");
    $this->assertArrayHasKey('ruc', $proveedor, "El proveedor debe tener un campo 'ruc'");
    $this->assertArrayHasKey('razon_social', $proveedor, "El proveedor debe tener un campo 'razon_social'");

    fwrite(STDOUT, "\n✓ Test obtenerPorId completado exitosamente: Se obtuvo el proveedor con ID 1\n");
  }

  public function testObtenerPorIdInexistente()
  {
    $proveedor = $this->proveedor->obtenerPorId(99999);
    $this->assertNull($proveedor, "El método debe retornar null cuando no existe el proveedor");

    fwrite(STDOUT, "\n✓ Test obtenerPorIdInexistente completado exitosamente: Se manejó correctamente el caso de ID inexistente\n");
  }

  protected function tearDown(): void
  {
    $this->conexion->close();
  }
}
