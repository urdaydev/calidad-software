<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Classes\Categoria;
use mysqli;

class CategoriaTest extends TestCase
{
  private $conexion;
  private $categoria;

  protected function setUp(): void
  {
    // Configurar la conexión a la base de datos de pruebas
    $this->conexion = new mysqli(
      'localhost',     // host
      'root',         // usuario
      '',            // contraseña
      'bd_cordon_y_la_rosa'  // base de datos
    );

    $this->categoria = new Categoria($this->conexion);
  }

  public function testObtenerTodasRetornaArray()
  {
    $categorias = $this->categoria->obtenerTodas();

    // Verificar que el resultado es un array
    $this->assertIsArray($categorias);

    // Si hay categorías, verificar la estructura del primer elemento
    if (count($categorias) > 0) {
      $primeraCategoria = $categorias[0];
      $this->assertArrayHasKey('id', $primeraCategoria);
      $this->assertArrayHasKey('nombre', $primeraCategoria);
      $this->assertArrayHasKey('descripcion', $primeraCategoria);
    }
  }

  public function testObtenerPorIdExistente()
  {
    // Asumiendo que existe una categoría con ID 6
    $categoria = $this->categoria->obtenerPorId(6);

    $this->assertIsArray($categoria);
    $this->assertArrayHasKey('id', $categoria);
    $this->assertArrayHasKey('nombre', $categoria);
    $this->assertArrayHasKey('descripcion', $categoria);
  }

  public function testObtenerPorIdInexistente()
  {
    // Intentar obtener una categoría que no existe (ID 99999)
    $categoria = $this->categoria->obtenerPorId(99999);

    $this->assertNull($categoria);
  }

  protected function tearDown(): void
  {
    // Cerrar la conexión después de las pruebas
    $this->conexion->close();
  }
}
