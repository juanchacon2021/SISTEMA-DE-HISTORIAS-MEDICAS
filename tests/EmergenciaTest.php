<?php
use PHPUnit\Framework\TestCase;
use Shm\Shm\modelo\emergencias;



class EmergenciaTest extends TestCase{

    private $resultado;

    public function setUp(): void {
        $this->resultado = new emergencias();
    }
    public function testListadopersonal(){
        $emergencias = new emergencias();
        $resultado = $emergencias->listadopersonal();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('listadopersonal', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
    }


    public function testlistadopacientes(){
        $emergencias = new emergencias();
        $resultado = $emergencias->listadopacientes();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('listadopacientes', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
    }

    public function testIncluir(){
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
        $this->assertEquals('incluir', $resultado['resultado']);
        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertEquals('Registro Incluido', $resultado['mensaje']);
    }

      public function testIncluirConCedulasInvalidas(){
        $emergencias = new emergencias();

        $datos = [
            'horaingreso' => '12:00',
            'fechaingreso' => '2025-09-16',
            'motingreso' => 'Prueba PHPUnit Error',
            'diagnostico_e' => 'Diagnóstico de prueba',
            'tratamientos' => 'Tratamiento de prueba',
            'cedula_personal' => 99999999, // Cédula que no existe
            'cedula_paciente' => 88888888, // Cédula que no existe
            'procedimiento' => 'Procedimiento de prueba'
        ];

        $resultado = $emergencias->incluir($datos);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('error', $resultado['resultado']);
        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertEquals('Ninguna de las cédulas existe', $resultado['mensaje']); 
    }

      public function testIncluirConRegistroDuplicado(){
        $emergencias = new emergencias();

        // Usar los mismos datos que ya existen
        $datos = [
            'horaingreso' => '12:00',
            'fechaingreso' => '2025-09-16',
            'motingreso' => 'Prueba PHPUnit Duplicado',
            'diagnostico_e' => 'Diagnóstico de prueba',
            'tratamientos' => 'Tratamiento de prueba',
            'cedula_personal' => 20000001,
            'cedula_paciente' => 10000001,
            'procedimiento' => 'Procedimiento de prueba'
        ];

        $resultado = $emergencias->incluir($datos);

        $this->assertIsArray($resultado);
        // Debería retornar 'incluir' con mensaje de que ya existe
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('error', $resultado['resultado']);
        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertEquals('Ya existe el registro con estos datos', $resultado['mensaje']);
    }

    public function testExiste(){
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


    public function testModificar(){
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
        $this->assertEquals('modificar', $resultado['resultado']);
        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertEquals('Registro Modificado', $resultado['mensaje']);
    }

    public function testEliminar(){
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
        $this->assertEquals('eliminar', $resultado['resultado']);
        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertEquals('Registro Eliminado', $resultado['mensaje']);
    } 


}