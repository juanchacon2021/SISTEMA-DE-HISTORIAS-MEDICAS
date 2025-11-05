<?php
use PHPUnit\Framework\TestCase;
use Shm\Shm\modelo\p_cronicos;
use Shm\Shm\modelo\patologias;

class P_cronicoTest extends TestCase{

    private static $cod_patologia;
 
    private $p_cronicos;
    private $patologias;
    

    public function setUp(): void {
        $this->p_cronicos = new p_cronicos();
        $this->patologias = new patologias();
    }

    public function testListadopacientesReturnsArray(){
        $resultado = $this->p_cronicos->listadopacientes();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('listadopacientes', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
        $this->assertNotEmpty($resultado['datos']); // verifica que no esté vacío
    }
        public function testListalistado_patologiasReturnsArray(){
        $resultado = $this->patologias->listado_patologias();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('listado_patologias', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
        $this->assertNotEmpty($resultado['datos']); // verifica que no esté vacío
    }

    public function testConsultarReturnsArrayWithData(){
        $resultado = $this->p_cronicos->consultar();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
    }

    public function testIncluirPatologia(){
        $datos = [
            'nombre_patologia' => 'Diabetes Tipo 2 PHPUnit'
        ];

        $resultado = $this->patologias->incluir2($datos);

        // Verifica que retorna un array
        $this->assertIsArray($resultado);
        
        // Verifica las claves esperadas
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertArrayHasKey('cod_patologia', $resultado);
        
        // Verifica los valores esperados
        $this->assertEquals('agregar', $resultado['resultado']);
        $this->assertEquals('Patología registrada exitosamente', $resultado['mensaje']);

        // Guarda el código para otros tests
        self::$cod_patologia = $resultado['cod_patologia'];
        
        // Verifica adicional que el código no sea null
        $this->assertNotNull($resultado['cod_patologia']);
    }

    public function testModificarPatologia(){
        // Usa el código generado en el test de inclusión
        $cod_patologia = self::$cod_patologia;

        $datos_modificar = [
            'cod_patologia' => $cod_patologia,
            'nombre_patologia' => 'Hipertensión Arterial Modificada PHPUnit'
        ];

        $resultado_modificar = $this->patologias->modificar2($datos_modificar);

        // Verifica que retorna un array
        $this->assertIsArray($resultado_modificar);
        
        // Verifica las claves esperadas
        $this->assertArrayHasKey('resultado', $resultado_modificar);
        $this->assertArrayHasKey('mensaje', $resultado_modificar);
        
        // Verifica los valores de éxito
        $this->assertEquals('actualizar', $resultado_modificar['resultado']);
        $this->assertEquals('Registro Modificado', $resultado_modificar['mensaje']);
    }

    public function testExistePatologia(){
        // Primero asegúrate de que existe una patología usando el código generado
        $cod_patologia = self::$cod_patologia;

        // Usar Reflection para acceder al método privado
        $reflection = new \ReflectionClass($this->patologias);
        $method = $reflection->getMethod('existe2');
        $method->setAccessible(true);

        // Ejecutar el método privado
        $existe = $method->invoke($this->patologias, $cod_patologia);

        $this->assertTrue($existe, "La patología con código $cod_patologia debería existir en la base de datos");
    }

    public function testExisteNombrePatologia(){
        $nombre_existente = 'Hipertensión Arterial Modificada PHPUnit';
        
        $reflection = new \ReflectionClass($this->patologias);
        $method = $reflection->getMethod('existeNombrePatologia');
        $method->setAccessible(true);

        $resultado = $method->invoke($this->patologias, $nombre_existente);

        $this->assertTrue($resultado);
    }

     public function testIncluirPacienteCronico(){
        $cedula_paciente = 10000001;

        $datos = [
            'cedula_paciente' => $cedula_paciente
        ];

        $patologias = [
            [
                'cod_patologia' => self::$cod_patologia,
                'tratamiento' => 'Tratamiento PHPUnit',
                'administracion_t' => 'Cada 8 horas'
            ]
        ];

        $resultado = $this->p_cronicos->incluir($datos, $patologias);

        $this->assertIsArray($resultado);
        $this->assertEquals('incluir', $resultado['resultado']);
        $this->assertEquals('Registro Incluido', $resultado['mensaje']);
    }


    public function testObtenerPatologiasPaciente(){
        $cedula_paciente = 10000001;

        $resultado = $this->p_cronicos->obtener_patologias_paciente($cedula_paciente);

        $this->assertIsArray($resultado);
    }

    public function testModificarPacienteCronicoActualizaPatologias(){
        $cedula_paciente = 10000001;

        $datos_modificar = [
            'cedula_paciente' => $cedula_paciente
        ];

        $patologias_modificar = [
            [
                'cod_patologia' => self::$cod_patologia,
                'tratamiento' => 'Tratamiento modificado PHPUnit',
                'administracion_t' => 'Cada 6 horas'
            ]
        ];

        $resultado_modificar = $this->p_cronicos->modificar($datos_modificar, $patologias_modificar);

        $this->assertIsArray($resultado_modificar);
        $this->assertEquals('modificar', $resultado_modificar['resultado']);
        $this->assertEquals('Registro Modificado', $resultado_modificar['mensaje']);
    }

    public function testExisteReturnsTrueForPacienteConPatologias(){
        $cedula_paciente = 10000001;

        $reflection = new \ReflectionClass($this->p_cronicos);
        $method = $reflection->getMethod('existe');
        $method->setAccessible(true);

        $resultado = $method->invoke($this->p_cronicos, $cedula_paciente);

        $this->assertTrue($resultado);
    }

    public function testEliminarPacienteCronicoEliminaPatologias(){
        $cedula_paciente = 10000001;

        $datos_eliminar = [
            'cedula_paciente' => $cedula_paciente
        ];

        $resultado_eliminar = $this->p_cronicos->eliminar($datos_eliminar);

        $this->assertIsArray($resultado_eliminar);
        $this->assertEquals('eliminar', $resultado_eliminar['resultado']);
        $this->assertEquals('Registro Eliminado', $resultado_eliminar['mensaje']);
    } 



    public function testEliminar2EliminaPatologiaCorrectamente(){
        // Usa el código generado en el test de inclusión
        $cod_patologia = self::$cod_patologia;

        $datos_eliminar = [
            'cod_patologia' => $cod_patologia
        ];

        $resultado_eliminar = $this->patologias->eliminar2($datos_eliminar);

        // Verifica que retorna un array
        $this->assertIsArray($resultado_eliminar);
        
        // Verifica las claves esperadas
        $this->assertArrayHasKey('resultado', $resultado_eliminar);
        $this->assertArrayHasKey('mensaje', $resultado_eliminar);
        
        // Verifica los valores de éxito
        $this->assertEquals('descartar', $resultado_eliminar['resultado']);
        $this->assertEquals('Registro Eliminado', $resultado_eliminar['mensaje']);

    }

  
}