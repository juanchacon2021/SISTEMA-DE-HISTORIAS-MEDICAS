<?php
/*
███╗  ██████╗ 
███║ ██╔═══██╗
███║ ██║   ██║
███║ ██║   ██║
███║ ╚██████╔╝
╚══╝  ╚═════╝ 
*/
use PHPUnit\Framework\TestCase;
use Shm\Shm\modelo\inventario;

class testInventario extends TestCase{

    private $inventario;
    
    public function setUp(): void {
        $this->inventario = new inventario();
    }

    
    public function testconsultar_medicamentos(){
        $inventario = new inventario();
        $resultado = $inventario->consultar_medicamentos();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('consultar_medicamentos', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
    }

    public function testConsultarMovimientosReturnsArray(){
        $inventario = new inventario();
        $resultado = $inventario->consultar_movimientos();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('consultar_movimientos', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
        $this->assertNotEmpty($resultado['datos']); // verifica que no esté vacío

    }


}
