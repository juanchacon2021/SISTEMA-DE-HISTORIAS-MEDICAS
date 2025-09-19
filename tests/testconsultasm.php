<?php
use PHPUnit\Framework\TestCase;
use Shm\Shm\modelo\consultasm;
use Shm\Shm\modelo\observaciones;

class testconsultasm extends TestCase{

    private $consultasm;
    private $observaciones;
    private static $cod_consulta;
    private static $cod_observacion;

    public function setUp(): void {
        $this->consultasm = new consultasm();
        $this->observaciones = new observaciones();
    }

    public function testListadopersonalReturnsArray(){
        $resultado = $this->consultasm->listadopersonal();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('listadopersonal', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
    }

    public function testListadopacientesReturnsArray(){
        $resultado = $this->consultasm->listadopacientes();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('listadopacientes', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
    }

    public function testConsultarReturnsArray(){
        $resultado = $this->consultasm->consultar();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
    }

  
    public function testIncluirConsultaGeneraCodigoCorrectoYGuarda(){
        $datos = [
            'fechaconsulta' => '2025-09-18',
            'Horaconsulta' => '09:00',
            'consulta' => 'Consulta PHPUnit',
            'diagnostico' => 'Diagnóstico PHPUnit',
            'tratamientos' => 'Tratamiento PHPUnit',
            'cedula_personal' => 20000001,
            'cedula_paciente' => 10000001
        ];
        $observaciones = [
            ['cod_observacion' => 'Ox00000002', 'observacion' => 'Observación 1 de prueba'],
            ['cod_observacion' => 'Ox00000003', 'observacion' => 'Observación 2 de prueba']
        ];
        $resultado = $this->consultasm->incluir($datos, $observaciones);

        // Guarda el código para otros tests
        self::$cod_consulta = $resultado['cod_consulta'];
    }

    public function testExisteReturnsTrueForExistingRecord(){
        // Usa los mismos datos que insertaste en testIncluirConsultaGeneraCodigoCorrectoYGuarda
        $cod_consulta = 'Cx00000001';

        $reflection = new \ReflectionClass($this->consultasm);
        $method = $reflection->getMethod('existe');
        $method->setAccessible(true);

        $existe = $method->invoke($this->consultasm, $cod_consulta);

        $this->assertTrue($existe);
    }


   public function testModificarConsultaModificaDatosDeIncluir(){
        // Usa el código generado en el test de inclusión
        $cod_consulta = self::$cod_consulta;

        $datos_modificar = [
            'cod_consulta' => $cod_consulta,
            'fechaconsulta' => '2025-09-19',
            'Horaconsulta' => '10:30',
            'consulta' => 'Consulta modificada',
            'diagnostico' => 'Diagnóstico modificado',
            'tratamientos' => 'Tratamiento modificado',
            'cedula_personal' => 20000001,
            'cedula_paciente' => 10000001
        ];
        $observaciones_modificar = [
            ['cod_observacion' => 'Ox00000003', 'observacion' => 'Observación modificada']
        ];

        $resultado_modificar = $this->consultasm->modificar($datos_modificar, $observaciones_modificar);

        $this->assertIsArray($resultado_modificar);
        $this->assertEquals('modificar', $resultado_modificar['resultado']);
        $this->assertEquals('Registro Modificado', $resultado_modificar['mensaje']);
        $this->assertArrayHasKey('cod_consulta', $resultado_modificar);
        $this->assertEquals($cod_consulta, $resultado_modificar['cod_consulta']);
    }

    public function testEliminarConsultaEliminaObservacionesYConsulta(){
        // Usa el código generado en el test de inclusión
        $cod_consulta = self::$cod_consulta;

        $datos_eliminar = [
            'cod_consulta' => $cod_consulta
        ];

        $resultado_eliminar = $this->consultasm->eliminar($datos_eliminar);

        $this->assertIsArray($resultado_eliminar);
        $this->assertEquals('eliminar', $resultado_eliminar['resultado']);
        $this->assertEquals('Registro Eliminado', $resultado_eliminar['mensaje']);

        // Verifica que ya no existe la consulta
        $reflection = new \ReflectionClass($this->consultasm);
        $method = $reflection->getMethod('existe');
        $method->setAccessible(true);
        $existe = $method->invoke($this->consultasm, $cod_consulta);
        $this->assertFalse($existe, "La consulta y sus observaciones deberían haber sido eliminadas.");
    }

    //este test es para las operaciones CRUD de consultas medicas

    public function testListadoObservacionesReturnsArray(){
        $resultado = $this->observaciones->listado_observaciones();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('listado_observaciones', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
    }

    public function testIncluir2AgregaObservacionCorrectamente(){
        $datos = [
            'nom_observaciones' => 'Observación de prueba PHPUnit'
        ];

        $resultado = $this->observaciones->incluir2($datos);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('agregar', $resultado['resultado']);
        $this->assertEquals('Registro Incluido', $resultado['mensaje']);
        $this->assertArrayHasKey('cod_observacion', $resultado);
        $this->assertMatchesRegularExpression('/^Ox\d{8}$/', $resultado['cod_observacion']);

        // Guarda el código para otros tests
        self::$cod_observacion = $resultado['cod_observacion'];
    }


    public function testExiste2ReturnsTrueForExistingObservacion(){
        // Usa el código generado en el test anterior
        $cod_observacion = self::$cod_observacion;

        $reflection = new \ReflectionClass($this->observaciones);
        $method = $reflection->getMethod('existe2');
        $method->setAccessible(true);

        $existe = $method->invoke($this->observaciones, $cod_observacion);

        $this->assertTrue($existe, "La observación con código $cod_observacion debería existir.");
    }
    public function testModificar2ActualizaObservacionCorrectamente(){
        // Usa el código generado en el test de inclusión
        $cod_observacion = self::$cod_observacion;

        $datos_modificar = [
            'cod_observacion' => $cod_observacion,
            'nom_observaciones' => 'Observación modificada PHPUnit'
        ];

        $resultado_modificar = $this->observaciones->modificar2($datos_modificar);

        $this->assertIsArray($resultado_modificar);
        $this->assertEquals('actualizar', $resultado_modificar['resultado']);
        $this->assertEquals('Registro Modificado', $resultado_modificar['mensaje']);

        // Opcional: verifica que el cambio se realizó consultando el registro
        $co = $this->observaciones->conecta();
        $stmt = $co->prepare("SELECT nom_observaciones FROM tipo_observacion WHERE cod_observacion = ?");
        $stmt->execute([$cod_observacion]);
        $nom_observaciones = $stmt->fetchColumn();
        $this->assertEquals('Observación modificada PHPUnit', $nom_observaciones);
    }

    public function testEliminar2EliminaObservacionCorrectamente(){
    // Usa el código generado en el test de inclusión
    $cod_observacion = self::$cod_observacion;

    $datos_eliminar = [
        'cod_observacion' => $cod_observacion
    ];

    $resultado_eliminar = $this->observaciones->eliminar2($datos_eliminar);

    $this->assertIsArray($resultado_eliminar);
    $this->assertEquals('descartar', $resultado_eliminar['resultado']);
    $this->assertEquals('Registro Eliminado', $resultado_eliminar['mensaje']);

    // Verifica que ya no existe la observación
    $reflection = new \ReflectionClass($this->observaciones);
    $method = $reflection->getMethod('existe2');
    $method->setAccessible(true);
    $existe = $method->invoke($this->observaciones, $cod_observacion);
    $this->assertFalse($existe, "La observación con código $cod_observacion debería haber sido eliminada.");
}
  



   
}