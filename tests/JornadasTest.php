<?php

use PHPUnit\Framework\TestCase;
use Shm\Shm\modelo\jornadas;

class JornadasTest extends TestCase
{
    private $jornadas;

    public function setUp(): void
    {
        $this->jornadas = new jornadas();
    }

    public function testConsultarJornadasRetornaArrayCorrectamente()
    {
        $resultado = $this->jornadas->consultar();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);

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

    public function testValidacionEmbarazadasConReflection()
    {
        $jornadas = new jornadas();

        $reflection = new ReflectionClass($jornadas);
        $method = $reflection->getMethod('validarPacientes');
        $method->setAccessible(true);

        $jornadas->set_pacientes_masculinos(10);
        $jornadas->set_pacientes_femeninos(5);
        $jornadas->set_pacientes_embarazadas(8);

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

        $jornadas->set_pacientes_masculinos(10);
        $jornadas->set_pacientes_femeninos(15);
        $jornadas->set_pacientes_embarazadas(3);

        $resultado = $method->invoke($jornadas);

        $this->assertTrue($resultado);

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

        $jornadas->set_pacientes_masculinos(10);
        $jornadas->set_pacientes_femeninos(15);
        $jornadas->set_pacientes_embarazadas(0);

        $resultado = $method->invoke($jornadas);

        $this->assertTrue($resultado);

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

        $jornadas->set_pacientes_masculinos(0);
        $jornadas->set_pacientes_femeninos(0);
        $jornadas->set_pacientes_embarazadas(0);

        $resultado = $method->invoke($jornadas);

        $this->assertTrue($resultado);

        $property = $reflection->getProperty('total_pacientes');
        $property->setAccessible(true);
        $total = $property->getValue($jornadas);

        $this->assertEquals(0, $total);
    }

    public function testGestionarJornadaConDatosInvalidos()
    {
        $datos = [
            'accion' => 'incluir',
            'fecha_jornada' => '2025-01-15',
            'ubicacion' => 'Test',
            'pacientes_masculinos' => 10,
            'pacientes_femeninos' => 5,
            'pacientes_embarazadas' => 8, 
            'cedula_responsable' => '12345678'
        ];

        $resultado = $this->jornadas->gestionar_jornada($datos);

        $this->assertIsArray($resultado);

        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('error', $resultado['resultado']);

        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertStringContainsString('embarazadas', $resultado['mensaje']);
    }


    public function testGestionarJornadaConDatosValidosPeroBDInvalida()
    {
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

        $this->assertIsArray($resultado);
    }

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

    public function testSettersAceptanTiposCorrectos()
    {
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

        $this->assertTrue(true);
    }
}
