<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Classes\Cliente;
use mysqli;

require_once __DIR__ . '/../../classes/Cliente.php';

/**
 * Pruebas de Caja Blanca para la clase Cliente
 * 
 * Este conjunto de pruebas implementa técnicas de prueba de caja blanca
 * para verificar la lógica interna y los flujos de control de la clase Cliente.
 * 
 * Técnicas implementadas:
 * - Prueba de camino básico
 * - Prueba de bucle
 * - Prueba de validación de datos
 */
class WhiteBoxTest extends TestCase
{
  /**
   * Test 1: Prueba de camino básico para buscarClientePorDocumento
   * 
   * Objetivo: Verificar el manejo de documentos vacíos en la búsqueda de clientes.
   * Técnica: Prueba de camino básico - verifica el primer punto de decisión.
   * 
   * Caso de prueba:
   * - Entrada: Documento vacío ("")
   * - Salida esperada: null
   * 
   * Cobertura:
   * - Verifica la validación inicial de entrada
   * - Asegura el manejo correcto de casos nulos
   */
  public function testBuscarClientePorDocumento()
  {
    $mockDb = $this->getMockBuilder(mysqli::class)
      ->disableOriginalConstructor()
      ->getMock();

    $cliente = new Cliente($mockDb);
    $resultado = $cliente->buscarClientePorDocumento("");
    $this->assertNull($resultado, "Debería retornar null para documento vacío");

    echo "\n✓ Test buscarClientePorDocumento completado exitosamente: Se validó correctamente el manejo de documento vacío\n";
  }

  /**
   * Test 2: Prueba de bucle para listarClientesActivos
   * 
   * Objetivo: Verificar el comportamiento del método cuando no hay clientes activos.
   * Técnica: Prueba de bucle - verifica el caso de lista vacía.
   * 
   * Caso de prueba:
   * - Condición: Base de datos retorna false
   * - Salida esperada: Array vacío
   * 
   * Cobertura:
   * - Verifica el manejo de consultas sin resultados
   * - Asegura la inicialización correcta del array de retorno
   */
  public function testListarClientesActivos()
  {
    $mockDb = $this->getMockBuilder(mysqli::class)
      ->disableOriginalConstructor()
      ->onlyMethods(['query'])
      ->getMock();

    $mockDb->expects($this->once())
      ->method('query')
      ->willReturn(false);

    $cliente = new Cliente($mockDb);
    $resultado = $cliente->listarClientesActivos();

    $this->assertIsArray($resultado);
    $this->assertEmpty($resultado, "Debería retornar un array vacío cuando no hay resultados");

    echo "\n✓ Test listarClientesActivos completado exitosamente: Se verificó el manejo correcto de lista vacía\n";
  }

  /**
   * Test 3: Prueba de validación para obtenerClientePorId
   * 
   * Objetivo: Verificar la validación de IDs inválidos en la búsqueda de clientes.
   * Técnica: Prueba de condición y valores límite.
   * 
   * Casos de prueba:
   * 1. ID = 0
   *    - Entrada: 0
   *    - Salida esperada: null
   *    - Justificación: Valor límite inferior
   * 
   * 2. ID Negativo
   *    - Entrada: -1
   *    - Salida esperada: null
   *    - Justificación: Valor inválido negativo
   * 
   * 3. ID No Numérico
   *    - Entrada: "abc"
   *    - Salida esperada: null
   *    - Justificación: Tipo de dato inválido
   * 
   * Cobertura:
   * - Verifica la validación de tipos de datos
   * - Asegura el manejo de valores límite
   * - Valida el manejo de tipos incorrectos
   */
  public function testObtenerClientePorIdInvalido()
  {
    $mockDb = $this->getMockBuilder(mysqli::class)
      ->disableOriginalConstructor()
      ->getMock();

    $cliente = new Cliente($mockDb);

    $resultado = $cliente->obtenerClientePorId(0);
    $this->assertNull($resultado, "Debería retornar null para ID = 0");

    $resultado = $cliente->obtenerClientePorId(-1);
    $this->assertNull($resultado, "Debería retornar null para ID negativo");

    $resultado = $cliente->obtenerClientePorId("abc");
    $this->assertNull($resultado, "Debería retornar null para ID no numérico");

    echo "\n✓ Test obtenerClientePorIdInvalido completado exitosamente: Se validaron correctamente todos los casos de IDs inválidos\n";
  }
}
