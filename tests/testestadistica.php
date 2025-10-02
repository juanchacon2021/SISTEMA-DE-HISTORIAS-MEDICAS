<?php
use PHPUnit\Framework\TestCase;
use Shm\Shm\modelo\estadisticas;

class testEstadistica extends TestCase{

    private $estadisticas;

    public function setUp(): void {
        $this->estadisticas = new estadisticas();
    }

    public function testConsultarReturnsArrayWithValidStructure(){
        $resultado = $this->estadisticas->consultar();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertArrayHasKey('totalHistorias', $resultado);
        $this->assertArrayHasKey('distribucionEdad', $resultado);
        $this->assertIsArray($resultado['distribucionEdad']);
        
        // Verificar estructura de distribucionEdad
        $distribucion = $resultado['distribucionEdad'];
        $this->assertArrayHasKey('Ninos', $distribucion);
        $this->assertArrayHasKey('Adolescentes', $distribucion);
        $this->assertArrayHasKey('Adultos', $distribucion);
        $this->assertArrayHasKey('AdultosMayores', $distribucion);
    }

    public function testConsultarCronicosReturnsArrayWithValidStructure(){
        $resultado = $this->estadisticas->consultarCronicos();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('consultarCronicos', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
        
        // Verificar que cada elemento del array tenga la estructura esperada
        if (!empty($resultado['datos'])) {
            $primerElemento = $resultado['datos'][0];
            $this->assertArrayHasKey('nombre_patologia', $primerElemento);
            $this->assertArrayHasKey('pacientes', $primerElemento);
        }
    }

    public function testMedicamentosPorVencerReturnsArrayWithValidStructure(){
        $resultado = $this->estadisticas->medicamentosPorVencer();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('medicamentosPorVencer', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
        
        // Verificar estructura de cada medicamento
        if (!empty($resultado['datos'])) {
            $primerMedicamento = $resultado['datos'][0];
            $this->assertArrayHasKey('cod_lote', $primerMedicamento);
            $this->assertArrayHasKey('medicamento', $primerMedicamento);
            $this->assertArrayHasKey('unidad_medida', $primerMedicamento);
            $this->assertArrayHasKey('fecha_vencimiento', $primerMedicamento);
            $this->assertArrayHasKey('cantidad', $primerMedicamento);
        }
    }

    public function testTotalesGeneralesReturnsArrayWithValidStructure(){
        $resultado = $this->estadisticas->totalesGenerales();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('totales_generales', $resultado['resultado']);
        $this->assertArrayHasKey('totales', $resultado);
        $this->assertIsArray($resultado['totales']);
        
        // Verificar estructura de totales
        $totales = $resultado['totales'];
        $this->assertArrayHasKey('total_historias', $totales);
        $this->assertArrayHasKey('total_cronicos', $totales);
        $this->assertArrayHasKey('total_emergencias', $totales);
        $this->assertArrayHasKey('total_consultas', $totales);
        $this->assertArrayHasKey('cantidad_lotes_con_existencia', $totales);
        $this->assertArrayHasKey('total_de_medicamentos', $totales);
    }

    public function testEmergenciasPorDiaMesActualReturnsArrayWithValidStructure(){
        // Simular parámetros POST
        $_POST['mes'] = '12';
        $_POST['anio'] = '2024';

        $resultado = $this->estadisticas->emergenciasPorDiaMesActual();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertContains($resultado['resultado'], ['ok', 'error']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
        $this->assertArrayHasKey('mes', $resultado);
        $this->assertArrayHasKey('anio', $resultado);
        
        // Verificar estructura de datos si hay resultados
        if (!empty($resultado['datos']) && $resultado['resultado'] === 'ok') {
            $primerDia = $resultado['datos'][0];
            $this->assertArrayHasKey('dia', $primerDia);
            $this->assertArrayHasKey('total', $primerDia);
        }
    }

    public function testConsultasPorDiaMesActualReturnsArrayWithValidStructure(){
        // Simular parámetros POST
        $_POST['mes'] = '12';
        $_POST['anio'] = '2024';

        $resultado = $this->estadisticas->consultasPorDiaMesActual();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertContains($resultado['resultado'], ['ok', 'error']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
        $this->assertArrayHasKey('mes', $resultado);
        $this->assertArrayHasKey('anio', $resultado);
        
        // Verificar estructura de datos si hay resultados
        if (!empty($resultado['datos']) && $resultado['resultado'] === 'ok') {
            $primerDia = $resultado['datos'][0];
            $this->assertArrayHasKey('dia', $primerDia);
            $this->assertArrayHasKey('total', $primerDia);
        }
    }

    public function testMesConMasEmergenciasReturnsArrayWithValidStructure(){
        $resultado = $this->estadisticas->mesConMasEmergencias();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertContains($resultado['resultado'], ['ok', 'error']);
        
        if ($resultado['resultado'] === 'ok') {
            $this->assertArrayHasKey('mes', $resultado);
            $this->assertArrayHasKey('anio', $resultado);
            $this->assertArrayHasKey('total', $resultado);
            
            // Verificar tipos de datos
            if ($resultado['mes'] !== null) {
                $this->assertIsNumeric($resultado['mes']);
            }
            if ($resultado['anio'] !== null) {
                $this->assertIsNumeric($resultado['anio']);
            }
            $this->assertIsNumeric($resultado['total']);
        }
    }

    public function testMedicamentosMasUsadosReturnsArrayWithValidStructure(){
        $resultado = $this->estadisticas->medicamentosMasUsados();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('medicamentosMasUsados', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
        
        // Verificar estructura de cada medicamento
        if (!empty($resultado['datos'])) {
            $primerMedicamento = $resultado['datos'][0];
            $this->assertArrayHasKey('medicamento', $primerMedicamento);
            $this->assertArrayHasKey('cantidad_total', $primerMedicamento);
        }
    }

    public function testMesConMasConsultasReturnsArrayWithValidStructure(){
        $resultado = $this->estadisticas->mesConMasConsultas();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertContains($resultado['resultado'], ['ok', 'error']);
        
        if ($resultado['resultado'] === 'ok') {
            $this->assertArrayHasKey('mes', $resultado);
            $this->assertArrayHasKey('anio', $resultado);
            $this->assertArrayHasKey('total', $resultado);
            
            // Verificar tipos de datos
            if ($resultado['mes'] !== null) {
                $this->assertIsNumeric($resultado['mes']);
            }
            if ($resultado['anio'] !== null) {
                $this->assertIsNumeric($resultado['anio']);
            }
            $this->assertIsNumeric($resultado['total']);
        }
    }

    public function testEmergenciasPorDiaMesActualWithoutPostParameters(){
        // Limpiar parámetros POST para probar valores por defecto
        unset($_POST['mes']);
        unset($_POST['anio']);

        $resultado = $this->estadisticas->emergenciasPorDiaMesActual();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertArrayHasKey('mes', $resultado);
        $this->assertArrayHasKey('anio', $resultado);
        
        // Debería usar el mes y año actual
        $this->assertEquals(date('m'), $resultado['mes']);
        $this->assertEquals(date('Y'), $resultado['anio']);
    }

    public function testConsultasPorDiaMesActualWithoutPostParameters(){
        // Limpiar parámetros POST para probar valores por defecto
        unset($_POST['mes']);
        unset($_POST['anio']);

        $resultado = $this->estadisticas->consultasPorDiaMesActual();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertArrayHasKey('mes', $resultado);
        $this->assertArrayHasKey('anio', $resultado);
        
        // Debería usar el mes y año actual
        $this->assertEquals(date('m'), $resultado['mes']);
        $this->assertEquals(date('Y'), $resultado['anio']);
    }
}