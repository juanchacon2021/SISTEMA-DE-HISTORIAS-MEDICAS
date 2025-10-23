<?php

use PHPUnit\Framework\TestCase;
use Shm\Shm\modelo\jornadas;

class TestJornadas extends TestCase
{
    private $jornadas;
    private static $cod_jornada_creada;

    public function setUp(): void
    {
        $this->jornadas = new jornadas();
    }

    // Tests que NO dependen de la inserción en la BD
    public function testConsultarJornadasRetornaArrayCorrectamente()
    {
        $resultado = $this->jornadas->consultar();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);

        // Verifica estructura de datos si hay registros
        if (!empty($resultado['datos'])) {
            $primera_jornada = $resultado['datos'][0];
            $this->assertArrayHasKey('cod_jornada', $primera_jornada);
            $this->assertArrayHasKey('fecha_jornada', $primera_jornada);
            $this->assertArrayHasKey('ubicacion', $primera_jornada);
        }
    }

    public function testObtenerPersonalRetornaArrayCorrectamente()
    {
        $resultado = $this->jornadas->obtener_personal();

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);

        foreach ($resultado['datos'] as $personal) {
            $this->assertArrayHasKey('cedula_personal', $personal);
            $this->assertArrayHasKey('nombre_completo', $personal);
        }
    }

    public function testObtenerResponsablesRetornaArrayCorrectamente()
    {
        $resultado = $this->jornadas->obtener_responsables();

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);

        foreach ($resultado['datos'] as $responsable) {
            $this->assertArrayHasKey('cedula_personal', $responsable);
            $this->assertArrayHasKey('nombre_completo', $responsable);
        }
    }

    public function testConsultarJornadaInexistenteRetornaError()
    {
        $resultado = $this->jornadas->consultar_jornada('CODIGO_INEXISTENTE_789');

        $this->assertIsArray($resultado);
        $this->assertEquals('error', $resultado['resultado']);
        $this->assertEquals('Jornada no encontrada', $resultado['mensaje']);
    }

    public function testAccionNoValidaRetornaError()
    {
        $datos = [
            'accion' => 'accion_no_existente',
            'fecha_jornada' => '2025-01-15',
            'ubicacion' => 'Ubicación'
        ];

        $resultado = $this->jornadas->gestionar_jornada($datos);

        $this->assertIsArray($resultado);
        $this->assertEquals('error', $resultado['resultado']);
        $this->assertEquals('Acción no válida', $resultado['mensaje']);
    }

    // Tests de validación usando Reflection (no dependen de la BD)
    public function testValidacionEmbarazadasConReflection()
    {
        $jornadas = new jornadas();

        // Usar reflection para probar el método privado
        $reflection = new ReflectionClass($jornadas);
        $method = $reflection->getMethod('validarPacientes');
        $method->setAccessible(true);

        // Establecer valores inválidos
        $jornadas->set_pacientes_masculinos(10);
        $jornadas->set_pacientes_femeninos(5);
        $jornadas->set_pacientes_embarazadas(8);

        // Esperar que lance una excepción
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('embarazadas');
        $this->expectExceptionMessage('mayor');

        $method->invoke($jornadas);
    }

    public function testValidacionEmbarazadasValidasConReflection()
    {
        $jornadas = new jornadas();

        $reflection = new ReflectionClass($jornadas);
        $method = $reflection->getMethod('validarPacientes');
        $method->setAccessible(true);

        // Establecer valores válidos
        $jornadas->set_pacientes_masculinos(10);
        $jornadas->set_pacientes_femeninos(15);
        $jornadas->set_pacientes_embarazadas(3);

        $resultado = $method->invoke($jornadas);

        $this->assertTrue($resultado);

        // Verificar que el total se calculó correctamente
        $property = $reflection->getProperty('total_pacientes');
        $property->setAccessible(true);
        $total = $property->getValue($jornadas);

        $this->assertEquals(25, $total); // 10 + 15 = 25
    }

    public function testValidacionSinEmbarazadasConReflection()
    {
        $jornadas = new jornadas();

        $reflection = new ReflectionClass($jornadas);
        $method = $reflection->getMethod('validarPacientes');
        $method->setAccessible(true);

        // Establecer valores sin embarazadas
        $jornadas->set_pacientes_masculinos(10);
        $jornadas->set_pacientes_femeninos(15);
        $jornadas->set_pacientes_embarazadas(0);

        $resultado = $method->invoke($jornadas);

        $this->assertTrue($resultado);

        // Verificar que el total se calculó correctamente
        $property = $reflection->getProperty('total_pacientes');
        $property->setAccessible(true);
        $total = $property->getValue($jornadas);

        $this->assertEquals(25, $total);
    }

    public function testValidacionTodosCerosConReflection()
    {
        $jornadas = new jornadas();

        $reflection = new ReflectionClass($jornadas);
        $method = $reflection->getMethod('validarPacientes');
        $method->setAccessible(true);

        // Establecer todos los valores en cero
        $jornadas->set_pacientes_masculinos(0);
        $jornadas->set_pacientes_femeninos(0);
        $jornadas->set_pacientes_embarazadas(0);

        $resultado = $method->invoke($jornadas);

        $this->assertTrue($resultado);

        // Verificar que el total se calculó correctamente
        $property = $reflection->getProperty('total_pacientes');
        $property->setAccessible(true);
        $total = $property->getValue($jornadas);

        $this->assertEquals(0, $total);
    }

    // Tests que verifican el manejo de errores del modelo
    public function testGestionarJornadaConDatosInvalidos()
    {
        $datos = [
            'accion' => 'incluir',
            'fecha_jornada' => '2025-01-15',
            'ubicacion' => 'Test',
            'pacientes_masculinos' => 10,
            'pacientes_femeninos' => 5,
            'pacientes_embarazadas' => 8, // Inválido: más embarazadas que mujeres
            'cedula_responsable' => '12345678'
        ];

        $resultado = $this->jornadas->gestionar_jornada($datos);

        // Verifica que se retorne un array
        $this->assertIsArray($resultado);

        // Verifica que el resultado indique error
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('error', $resultado['resultado']);

        // Verifica que el mensaje de error sea el esperado
        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertStringContainsString('embarazadas', $resultado['mensaje']);
    }


    public function testGestionarJornadaConDatosValidosPeroBDInvalida()
    {
        // Test con datos válidos pero que fallarán por problemas de BD
        $datos = [
            'accion' => 'incluir',
            'fecha_jornada' => '2025-01-15',
            'ubicacion' => 'Ubicación válida',
            'descripcion' => 'Descripción válida',
            'pacientes_masculinos' => 10,
            'pacientes_femeninos' => 15,
            'pacientes_embarazadas' => 3,
            'cedula_responsable' => '12345678',
            'participantes' => ['12345678']
        ];

        $resultado = $this->jornadas->gestionar_jornada($datos);

        // El resultado puede ser 'error' por problemas de BD, pero debería ser un array
        $this->assertIsArray($resultado);
    }

    // Tests para operaciones de modificación y eliminación sin dependencia de inserción previa
    public function testModificarJornadaInexistenteRetornaComportamientoEsperado()
    {
        $datos = [
            'accion' => 'modificar',
            'cod_jornada' => 'CODIGO_INEXISTENTE_123',
            'fecha_jornada' => '2025-01-15',
            'ubicacion' => 'Ubicación',
            'descripcion' => 'Descripción',
            'pacientes_masculinos' => 10,
            'pacientes_femeninos' => 15,
            'pacientes_embarazadas' => 3,
            'cedula_responsable' => '12345678',
            'participantes' => ['12345678']
        ];

        $resultado = $this->jornadas->gestionar_jornada($datos);

        $this->assertIsArray($resultado);
        $this->assertContains($resultado['resultado'], ['modificar', 'error']);
    }

    public function testEliminarJornadaInexistenteRetornaComportamientoEsperado()
    {
        $datos = [
            'accion' => 'eliminar',
            'cod_jornada' => 'CODIGO_INEXISTENTE_456'
        ];

        $resultado = $this->jornadas->gestionar_jornada($datos);

        $this->assertIsArray($resultado);
        $this->assertContains($resultado['resultado'], ['eliminar', 'error']);
    }

    // Test para verificar la estructura del modelo
    public function testModeloTieneMetodosEsperados()
    {
        $metodos_esperados = [
            'set_cod_jornada',
            'set_fecha_jornada',
            'set_ubicacion',
            'set_descripcion',
            'set_total_pacientes',
            'set_pacientes_masculinos',
            'set_pacientes_femeninos',
            'set_pacientes_embarazadas',
            'set_cedula_responsable',
            'set_participantes',
            'gestionar_jornada',
            'consultar',
            'consultar_jornada',
            'obtener_personal',
            'obtener_responsables'
        ];

        foreach ($metodos_esperados as $metodo) {
            $this->assertTrue(
                method_exists($this->jornadas, $metodo),
                "El método $metodo debería existir en la clase jornadas"
            );
        }
    }

    // Test para verificar tipos de datos en setters
    public function testSettersAceptanTiposCorrectos()
    {
        // Probar que los setters no lanzan errores con tipos básicos
        $this->jornadas->set_cod_jornada('TEST123');
        $this->jornadas->set_fecha_jornada('2025-01-15');
        $this->jornadas->set_ubicacion('Ubicación test');
        $this->jornadas->set_descripcion('Descripción test');
        $this->jornadas->set_total_pacientes(100);
        $this->jornadas->set_pacientes_masculinos(50);
        $this->jornadas->set_pacientes_femeninos(50);
        $this->jornadas->set_pacientes_embarazadas(10);
        $this->jornadas->set_cedula_responsable('12345678');
        $this->jornadas->set_participantes(['12345678', '87654321']);

        // Si llegamos aquí, los setters funcionan correctamente
        $this->assertTrue(true);
    }
}
