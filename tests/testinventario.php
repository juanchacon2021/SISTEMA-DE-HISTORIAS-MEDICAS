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
    private static $cod_medicamento;
    private static $cod_lote;
    
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

    public function testIncluirMedicamentoGeneraCodigoCorrecto(){
        $datos = [
            'nombre' => 'Medicamento PHPUnit Test',
            'descripcion' => 'Descripción del medicamento de prueba',
            'unidad_medida' => 'Tabletas',
            'stock_min' => 10
        ];

        $resultado = $this->inventario->incluir($datos);

        $this->assertIsArray($resultado);
        $this->assertEquals('incluir_medicamento', $resultado['resultado']);
        $this->assertEquals('Medicamento registrado con éxito', $resultado['mensaje']);
        $this->assertArrayHasKey('cod_medicamento', $resultado);
        $this->assertMatchesRegularExpression('/^Mx\d{8}$/', $resultado['cod_medicamento']);

        // Guarda el código para otros tests
        self::$cod_medicamento = $resultado['cod_medicamento'];
    }


}
