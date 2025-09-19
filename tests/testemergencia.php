<?php
use PHPUnit\Framework\TestCase;
use Shm\Shm\modelo\emergencias;
class testemergencia extends TestCase{

    private $resultado;
    public function setUp(): void {
        $this->resultado = new emergencias();
    }

    public function testListadopersonalReturnsArray(){
        $emergencias = new emergencias();
        $resultado = $emergencias->listadopersonal();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('listadopersonal', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
    }


    public function testlistadopacientesReturnsArray(){
        $emergencias = new emergencias();
        $resultado = $emergencias->listadopacientes();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('listadopacientes', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
    }

    public function testIncluirReturnsArray(){
        $emergencias = new emergencias();
        $datos = [
            'horaingreso' => '12:00',
            'fechaingreso' => '2025-09-16',
            'motingreso' => 'Prueba PHPUnit',
            'diagnostico_e' => 'Diagnóstico de prueba',
            'tratamientos' => 'Tratamiento de prueba',
            'cedula_personal' => 20000001, // Usa un valor válido en tu BD
            'cedula_paciente' => 10000001, // Usa un valor válido en tu BD
            'procedimiento' => 'Procedimiento de prueba'
        ];

        $resultado = $emergencias->incluir($datos);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertContains($resultado['resultado'], ['incluir', 'error']);
    }

    public function testExisteReturnsTrueForExistingRecord(){
        $emergencias = new emergencias();
        // Usa los mismos datos que insertaste en testIncluirReturnsArray
        $cedula_paciente = 10000001;
        $cedula_personal = 20000001;
        $fechaingreso = '2025-09-16';
        $horaingreso = '12:00';

        // Usar Reflection para acceder al método privado
        $reflection = new \ReflectionClass($emergencias);
        $method = $reflection->getMethod('existe');
        $method->setAccessible(true);

        $resultado = $method->invoke($emergencias, $cedula_paciente, $cedula_personal, $fechaingreso, $horaingreso);

        $this->assertTrue($resultado);
    }

    public function testModificarReturnsArray(){
        $emergencias = new emergencias();
        // Datos originales (debe existir en la BD, igual al testIncluir)
        $datos = [
            'motingreso' => 'Modificado PHPUnit',
            'diagnostico_e' => 'Diagnóstico modificado',
            'tratamientos' => 'Tratamiento modificado',
            'procedimiento' => 'Procedimiento modificado',
            'cedula_paciente' => 10000001,
            'cedula_personal' => 20000001,
            'fechaingreso' => '2025-09-16',
            'horaingreso' => '12:00',
            'old_cedula_paciente' => 10000001,
            'old_cedula_personal' => 20000001,
            'old_fechaingreso' => '2025-09-16',
            'old_horaingreso' => '12:00'
        ];

        $resultado = $emergencias->modificar($datos);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertContains($resultado['resultado'], ['modificar', 'error']);
    }    

    public function testEliminarReturnsArray(){
        $emergencias = new emergencias();
        $datos = [
            'cedula_paciente' => 10000001,
            'cedula_personal' => 20000001,
            'fechaingreso' => '2025-09-16',
            'horaingreso' => '12:00'
        ];

        $resultado = $emergencias->eliminar($datos);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertContains($resultado['resultado'], ['eliminar', 'error']);
    }


}