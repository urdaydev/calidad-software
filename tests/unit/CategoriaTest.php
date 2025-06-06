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
    $this->conexion = new mysqli(
      'localhost',
      'root',
      '',
      'bd_cordon_y_la_rosa'
    );

    $this->categoria = new Categoria($this->conexion);
  }

  public function testObtenerTodasRetornaArray()
  {
    $categorias = $this->categoria->obtenerTodas();

    $this->assertIsArray($categorias);

    if (count($categorias) > 0) {
      $primeraCategoria = $categorias[0];
      $this->assertArrayHasKey('id', $primeraCategoria);
      $this->assertArrayHasKey('nombre', $primeraCategoria);
      $this->assertArrayHasKey('descripcion', $primeraCategoria);
    }
  }

  public function testObtenerPorIdExistente()
  {
    $categoria = $this->categoria->obtenerPorId(6);

    $this->assertIsArray($categoria);
    $this->assertArrayHasKey('id', $categoria);
    $this->assertArrayHasKey('nombre', $categoria);
    $this->assertArrayHasKey('descripcion', $categoria);
  }

  public function testObtenerPorIdInexistente()
  {
    $categoria = $this->categoria->obtenerPorId(99999);

    $this->assertNull($categoria);
  }

  protected function tearDown(): void
  {
    $this->conexion->close();
  }
}
