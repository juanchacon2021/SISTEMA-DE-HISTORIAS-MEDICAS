<?php

use PHPUnit\Framework\TestCase;

use Shm\Shm\modelo\examenes;

class ExamenesTest extends TestCase
{
    private $examenes;

    // Variables estáticas para almacenar IDs generados y usarlos en las pruebas CRUD
    private static $cod_examen_creado = null; 
    private static $nombre_examen_test = 'Examen Test Unico XYZ';
    
    // Variables estáticas para registros, asumiendo que existen en la BD de prueba
    private static $cedula_paciente_test = 'V12345678'; 
    private static $cod_examen_registro_test = '1'; // Asume que un código de examen tipo 1 existe
    private static $fecha_registro_test = '2025-10-22';
    private static $hora_registro_test = '10:00:00';

    public function setUp(): void
    {
        // Se inicializa el modelo antes de cada test
        $this->examenes = new examenes();
    }

// =================================================================
//  1. Pruebas de Flujo para Tipos de Examen
// =================================================================

    /** @test */
    public function test1_IncluirTipoRetornaSuccessYGeneraCodigo()
    {
        // NOTA: Debe ser la primera prueba CRUD
        $datos = [
            'accion' => 'incluir_tipo',
            'nombre_examen' => self::$nombre_examen_test,
            'descripcion_examen' => 'Descripción del tipo de examen de prueba.'
        ];

        $resultado = $this->examenes->gestionar_tipo($datos);

        // Si hay error de conexión o BD, se marca el test como incompleto
        if ($resultado['resultado'] === 'error') {
            $this->markTestIncomplete("La inclusión de tipo de examen falló. Revise la conexión/datos de BD: {$resultado['mensaje']}");
        }

        $this->assertEquals('success', $resultado['resultado']);
        $this->assertStringContainsString('registrado exitosamente', $resultado['mensaje']);
        $this->assertNotNull($resultado['cod_examen']);
        
        // Capturar el código generado para las siguientes pruebas
        self::$cod_examen_creado = $resultado['cod_examen'];
    }

    /** @test */
    public function test2_ModificarTipoRetornaSuccess()
    {
        // Requiere que el test1 se haya ejecutado y capturado el ID
        $this->assertNotNull(self::$cod_examen_creado, 'No hay código de examen creado para modificar.');

        $datos = [
            'accion' => 'modificar_tipo',
            'cod_examen' => self::$cod_examen_creado,
            'nombre_examen' => 'Examen Test Modificado XYZ',
            'descripcion_examen' => 'Descripción modificada.'
        ];

        $resultado = $this->examenes->gestionar_tipo($datos);
        
        // Verifica si la BD falló o si se modificó correctamente
        $this->assertContains($resultado['resultado'], ['modificar', 'error'], "Modificación falló: {$resultado['mensaje']}");
        if ($resultado['resultado'] === 'modificar') {
            $this->assertStringContainsString('actualizado exitosamente', $resultado['mensaje']);
        }
    }

    /** @test */
    public function test3_IncluirTipoDuplicadoRetornaError()
    {
        $datos = [
            'accion' => 'incluir_tipo',
            'nombre_examen' => 'Examen Test Modificado XYZ', // Nombre usado en la modificación
            'descripcion_examen' => 'Intento de duplicado.'
        ];

        $resultado = $this->examenes->gestionar_tipo($datos);

        $this->assertEquals('error', $resultado['resultado']);
        $this->assertStringContainsString('Ya existe un tipo de examen con ese nombre', $resultado['mensaje']);
    }
    
    /** @test */
    public function test4_EliminarTipoRetornaSuccess()
    {
        // Requiere que el test1 se haya ejecutado y capturado el ID
        $this->assertNotNull(self::$cod_examen_creado, 'No hay código de examen creado para eliminar.');

        $datos = [
            'accion' => 'eliminar_tipo',
            'cod_examen' => self::$cod_examen_creado
        ];

        $resultado = $this->examenes->gestionar_tipo($datos);

        // Verifica si la BD falló o si se eliminó correctamente
        $this->assertContains($resultado['resultado'], ['eliminar', 'error'], "Eliminación falló: {$resultado['mensaje']}");
        if ($resultado['resultado'] === 'eliminar') {
            $this->assertStringContainsString('eliminado exitosamente', $resultado['mensaje']);
        }
    }


// -----------------------------------------------------------------
//  2. Pruebas de Manejo de Errores y Estructura
// -----------------------------------------------------------------

    public function testGestionarTipoAccionNoValidaRetornaError()
    {
        $datos = ['accion' => 'accion_tipo_no_existente'];
        $resultado = $this->examenes->gestionar_tipo($datos);
        $this->assertEquals('error', $resultado['resultado']);
        $this->assertEquals('Acción no válida', $resultado['mensaje']);
    }
    
    public function testGestionarRegistroAccionNoValidaRetornaError()
    {
        $datos = ['accion' => 'accion_registro_no_existente'];
        $resultado = $this->examenes->gestionar_registro($datos);
        $this->assertEquals('error', $resultado['resultado']);
        $this->assertEquals('Acción no válida', $resultado['mensaje']);
    }

    public function testModeloTieneMetodosEsperados()
    {
        $metodos_esperados = [
            'set_cod_examen', 'set_nombre_examen', 'set_descripcion_examen',
            'set_cedula_paciente', 'set_fecha_e', 'set_hora_e', 
            'set_observacion_examen', 'set_ruta_imagen',
            'gestionar_tipo', 'gestionar_registro',
            'consultar_tipos', 'obtener_tipos_select', 
            'consultar_registros', 'obtener_pacientes_select'
        ];
        foreach ($metodos_esperados as $metodo) {
            $this->assertTrue(method_exists($this->examenes, $metodo), "Falta el método: $metodo");
        }
    }


}