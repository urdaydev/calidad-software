<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Classes\Producto;
use mysqli;

class ProductoTest extends TestCase
{
  private $conexion;
  private $producto;

  protected function setUp(): void
  {
    $this->conexion = new mysqli(
      'localhost',
      'root',
      '',
      'bd_cordon_y_la_rosa'
    );

    $this->producto = new Producto($this->conexion);
  }

  public function testListarProductosActivosConCategoria()
  {
    $productos = $this->producto->listarProductosActivos();
    $this->assertIsArray($productos, "El método listarProductosActivos() debe retornar un array de productos");

    if (count($productos) > 0) {
      $primerProducto = $productos[0];
      $this->assertArrayHasKey('id', $primerProducto, "El producto debe tener un campo 'id'");
      $this->assertArrayHasKey('categoria_id', $primerProducto, "El producto debe tener un campo 'categoria_id'");
      $this->assertArrayHasKey('categoria_nombre', $primerProducto, "El producto debe tener un campo 'categoria_nombre'");
      $this->assertArrayHasKey('nombre', $primerProducto, "El producto debe tener un campo 'nombre'");
      $this->assertArrayHasKey('precio', $primerProducto, "El producto debe tener un campo 'precio'");
      $this->assertArrayHasKey('fecha_vencimiento', $primerProducto, "El producto debe tener un campo 'fecha_vencimiento'");
    }

    fwrite(STDOUT, "\n✓ Test listarProductosActivos completado exitosamente: Se obtuvieron los productos activos con sus categorías\n");
  }

  public function testBuscarProductoExistenteConDetallesCompletos()
  {
    $producto = $this->producto->buscarProductoPorId(13);
    $this->assertIsArray($producto, "El producto encontrado debe ser un array con todos sus detalles");
    $this->assertArrayHasKey('id', $producto, "El producto debe tener un campo 'id'");
    $this->assertArrayHasKey('nombre', $producto, "El producto debe tener un campo 'nombre'");
    $this->assertArrayHasKey('precio', $producto, "El producto debe tener un campo 'precio'");
    $this->assertArrayHasKey('stock', $producto, "El producto debe tener un campo 'stock'");
    $this->assertArrayHasKey('fecha_vencimiento', $producto, "El producto debe tener un campo 'fecha_vencimiento'");

    fwrite(STDOUT, "\n✓ Test buscarProductoExistente completado exitosamente: Se obtuvo el producto con ID 1 y todos sus detalles\n");
  }

  public function testBuscarProductoConIdInvalidoRetornaNull()
  {
    $productoInexistente = $this->producto->buscarProductoPorId(99999);
    $this->assertNull($productoInexistente, "El método debe retornar null cuando el ID del producto no existe");

    $productoIdNegativo = $this->producto->buscarProductoPorId(-1);
    $this->assertNull($productoIdNegativo, "El método debe retornar null cuando el ID es negativo");

    fwrite(STDOUT, "\n✓ Test buscarProductoInvalido completado exitosamente: Se manejaron correctamente los casos de IDs inválidos\n");
  }

  public function testListarProductosProximosAVencer()
  {
    // Assuming there are products about to expire in the database
    $productos = $this->producto->listarProductosProximosAVencer(30);
    $this->assertIsArray($productos, "El método listarProductosProximosAVencer() debe retornar un array de productos");

    if (count($productos) > 0) {
      $primerProducto = $productos[0];
      $this->assertArrayHasKey('id', $primerProducto, "El producto debe tener un campo 'id'");
      $this->assertArrayHasKey('nombre', $primerProducto, "El producto debe tener un campo 'nombre'");
      $this->assertArrayHasKey('fecha_vencimiento', $primerProducto, "El producto debe tener un campo 'fecha_vencimiento'");
    }

    fwrite(STDOUT, "\n✓ Test listarProductosProximosAVencer completado exitosamente: Se obtuvieron los productos próximos a vencer\n");
  }

  protected function tearDown(): void
  {
    $this->conexion->close();
  }
}
