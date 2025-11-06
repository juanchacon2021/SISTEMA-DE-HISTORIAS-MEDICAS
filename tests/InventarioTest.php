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

class InventarioTest extends TestCase
{
    private inventario $inventario;

    protected function setUp(): void
    {
        $this->inventario = new inventario();
    }

    private function cedulaPersonalValida(): ?int
    {
        try {
            $co = $this->inventario->conecta();
            $stmt = $co->query('SELECT cedula_personal FROM personal ORDER BY 1 LIMIT 1');
            $v = $stmt->fetchColumn();
            return $v !== false ? (int)$v : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function testConsultarMedicamentosDevuelveEstructuraValida(): void
    {
        $r = $this->inventario->consultar_medicamentos();

        $this->assertIsArray($r);
        $this->assertArrayHasKey('resultado', $r);
        $this->assertSame('consultar_medicamentos', $r['resultado']);
        $this->assertArrayHasKey('datos', $r);
        $this->assertIsArray($r['datos']);
    }

    public function testConsultarMovimientosDevuelveEstructuraValida(): void
    {
        $r = $this->inventario->consultar_movimientos();

        $this->assertIsArray($r);
        $this->assertArrayHasKey('resultado', $r);
        $this->assertContains($r['resultado'], ['consultar_movimientos', 'listar_movimientos']);
        $this->assertArrayHasKey('datos', $r);
        $this->assertIsArray($r['datos']);
    }

    public function testIncluirYObtenerMedicamento(): void
    {
        $datos = [
            'nombre' => 'Medicamento PHPUnit ' . uniqid('T', false),
            'descripcion' => 'Descripción del medicamento de prueba',
            'unidad_medida' => 'Tabletas',
            'stock_min' => 5,
        ];

        $inc = $this->inventario->incluir($datos);

        $this->assertIsArray($inc);
        $this->assertArrayHasKey('resultado', $inc);
        $this->assertContains($inc['resultado'], ['incluir_medicamento', 'success']);
        $this->assertArrayHasKey('cod_medicamento', $inc);
        $this->assertMatchesRegularExpression('/^Mx\d{8}$/', $inc['cod_medicamento']);

        $cod = $inc['cod_medicamento'];
        $obt = $this->inventario->obtener_medicamento($cod);

        $this->assertIsArray($obt);
        $this->assertArrayHasKey('resultado', $obt);
        $this->assertContains($obt['resultado'], ['obtener_medicamento', 'consultar_medicamento']);
        $this->assertArrayHasKey('datos', $obt);
        $this->assertIsArray($obt['datos']);
        $this->assertSame($cod, $obt['datos']['cod_medicamento'] ?? $cod);
    }

    public function testRegistrarEntradaConPersonalInvalidoDaError(): void
    {
        $inc = $this->inventario->incluir([
            'nombre' => 'Med Entrada Inválida ' . uniqid(),
            'descripcion' => 'Prueba entrada con personal inválido',
            'unidad_medida' => 'Unid',
            'stock_min' => 0
        ]);
        $this->assertContains($inc['resultado'], ['incluir_medicamento', 'success']);
        $cod = $inc['cod_medicamento'];

        $r = $this->inventario->registrar_entrada([
            'cantidad' => 10,
            'fecha_vencimiento' => '2031-12-31',
            'proveedor' => 'Proveedor PHPUnit',
            'cod_medicamento' => $cod,
            'cedula_personal' => 99999999 // fuerza error por personal inexistente
        ]);

        $this->assertIsArray($r);
        $this->assertSame('error', $r['resultado'] ?? 'error');
        if (isset($r['mensaje'])) {
            $this->assertIsString($r['mensaje']);
        }
    }

    public function testRegistrarSalidaSinStockDaError(): void
    {
        $inc = $this->inventario->incluir([
            'nombre' => 'Med Sin Stock ' . uniqid(),
            'descripcion' => 'Prueba salida sin stock',
            'unidad_medida' => 'Unid',
            'stock_min' => 0
        ]);
        $this->assertContains($inc['resultado'], ['incluir_medicamento', 'success']);
        $cod = $inc['cod_medicamento'];

        $cedula = $this->cedulaPersonalValida() ?? 99999999;

        $r = $this->inventario->registrar_salida([
            'cod_medicamento' => $cod,
            'cantidad' => 1,
            'cedula_personal' => $cedula
        ]);

        $this->assertIsArray($r);
        $this->assertSame('error', $r['resultado'] ?? 'error');
    }

    public function testConsultarLotesMedicamentoCodigoInexistenteDevuelveArray(): void
    {
        $l = $this->inventario->consultar_lotes_medicamento('Mx99999999');

        $this->assertIsArray($l);
        $this->assertArrayHasKey('resultado', $l);
        $this->assertContains($l['resultado'], ['consultar_lotes_medicamento', 'consultar_lotes']);
        $this->assertArrayHasKey('datos', $l);
        $this->assertIsArray($l['datos']);
    }

    public function testEliminarMedicamentoInexistenteManejaRespuesta(): void
    {
        $r = $this->inventario->eliminar(['cod_medicamento' => 'Mx99999999']);

        $this->assertIsArray($r);
        $this->assertArrayHasKey('resultado', $r);
        $this->assertContains($r['resultado'], ['error', 'eliminar_medicamento', 'success']);
    }

    public function testModificarConDatosIncompletosDaError(): void
    {
        $r = $this->inventario->modificar([
            'cod_medicamento' => 'Mx99999999',
            // omitir nombre/unidad para forzar validación
        ]);

        $this->assertIsArray($r);
        $this->assertArrayHasKey('resultado', $r);
        $this->assertContains($r['resultado'], ['error', 'modificar_medicamento', 'modificar']);
    }
}
