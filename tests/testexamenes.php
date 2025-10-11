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
use Shm\Shm\modelo\examenes;

class testExamenes extends TestCase{
    
    private $examenes;
    private static $cod_examen_tipo;
    private static $datos_registro = [];

    public function setUp(): void {
        $this->examenes = new examenes();
    }

    public function testIncluirTipoExamenRegistraCorrectamente(){
        $datos = [
            'accion' => 'incluir_tipo',
            'nombre_examen' => 'Examen de prueba PHPUnit',
            'descripcion_examen' => 'Descripción de examen de prueba'
        ];

        $resultado = $this->examenes->gestionar_tipo($datos);

        $this->assertIsArray($resultado);
        $this->assertEquals('success', $resultado['resultado']);
        $this->assertEquals('Tipo de examen registrado exitosamente', $resultado['mensaje']);
        $this->assertNotEmpty($resultado['cod_examen']);
        $this->assertMatchesRegularExpression('/^Ex\d{8}$/', $resultado['cod_examen']);

        // Guarda el código para el siguiente test
        self::$cod_examen_tipo = $resultado['cod_examen'];
    }

    public function testModificarTipoExamenActualizaCorrectamente(){
        $cod_examen = self::$cod_examen_tipo;

        $datos_modificar = [
            'accion' => 'modificar_tipo',
            'cod_examen' => $cod_examen,
            'nombre_examen' => 'Examen modificado PHPUnit',
            'descripcion_examen' => 'Descripción modificada'
        ];
        $resultado_modificar = $this->examenes->gestionar_tipo($datos_modificar);

        $this->assertIsArray($resultado_modificar);
        $this->assertEquals('modificar', $resultado_modificar['resultado']);
        $this->assertEquals('Tipo de examen actualizado exitosamente', $resultado_modificar['mensaje']);

        // Opcional: verifica que el cambio se realizó consultando el registro
        $co = $this->examenes->conecta();
        $stmt = $co->prepare("SELECT nombre_examen, descripcion_examen FROM tipo_de_examen WHERE cod_examen = ?");
        $stmt->execute([$cod_examen]);
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals('Examen modificado PHPUnit', $registro['nombre_examen']);
        $this->assertEquals('Descripción modificada', $registro['descripcion_examen']);
    }

/////////////////////////
/*
    public function testIncluirRegistroExamenCreaRegistroCorrectamente(){
        $cod_examen = self::$cod_examen_tipo;

        $datos = [
            'accion' => 'incluir_registro',
            'fecha_e' => '2025-10-01',
            'hora_e' => '08:30',
            'cedula_paciente' => 10000001, // Debe existir en tu BD
            'cod_examen' => $cod_examen,
            'observacion_examen' => 'Observación de examen PHPUnit'
        ];

        $resultado = $this->examenes->gestionar_registro($datos);

        $this->assertIsArray($resultado);
        $this->assertEquals('incluir', $resultado['resultado']);
        $this->assertEquals('Registro de examen creado exitosamente', $resultado['mensaje']);

        // Guarda los datos para los siguientes tests
        self::$datos_registro = $datos;

        // Verifica que el registro existe en la base de datos
        $co = $this->examenes->conecta();
        $stmt = $co->prepare("SELECT * FROM examen WHERE cedula_paciente = ? AND cod_examen = ? AND fecha_e = ?");
        $stmt->execute([
            $datos['cedula_paciente'],
            $datos['cod_examen'],
            $datos['fecha_e']
        ]);
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($registro, "El registro de examen debería existir en la base de datos.");
    }

    public function testEliminarRegistroExamenEliminaCorrectamente(){
        $datos = self::$datos_registro;
        $this->assertNotEmpty($datos, 'No hay registro de examen para eliminar');

        $datos_eliminar = [
            'accion' => 'eliminar_registro',
            'fecha_e' => $datos['fecha_e'],
            'cedula_paciente' => $datos['cedula_paciente'],
            'cod_examen' => $datos['cod_examen']
        ];

        $resultado_eliminar = $this->examenes->gestionar_registro($datos_eliminar);

        $this->assertIsArray($resultado_eliminar);
        $this->assertEquals('eliminar', $resultado_eliminar['resultado']);
        $this->assertEquals('Registro de examen eliminado exitosamente', $resultado_eliminar['mensaje']);

        // Verifica que el registro ya no existe en la base de datos
        $co = $this->examenes->conecta();
        $stmt = $co->prepare("SELECT COUNT(*) FROM examen WHERE cedula_paciente = ? AND cod_examen = ? AND fecha_e = ?");
        $stmt->execute([
            $datos_eliminar['cedula_paciente'],
            $datos_eliminar['cod_examen'],
            $datos_eliminar['fecha_e']
        ]);
        $total = $stmt->fetchColumn();
        $this->assertEquals(0, $total, "El registro de examen debería haber sido eliminado.");
    }

  

  

  
*/
/////////////////////////




    public function testEliminarTipoExamenEliminaCorrectamente(){
        $cod_examen = self::$cod_examen_tipo;

        $datos_eliminar = [
            'accion' => 'eliminar_tipo',
            'cod_examen' => $cod_examen
        ];
        $resultado_eliminar = $this->examenes->gestionar_tipo($datos_eliminar);

        $this->assertIsArray($resultado_eliminar);
        $this->assertEquals('eliminar', $resultado_eliminar['resultado']);
        $this->assertEquals('Tipo de examen eliminado exitosamente', $resultado_eliminar['mensaje']);

        // Verifica que el registro ya no existe en la base de datos
        $co = $this->examenes->conecta();
        $stmt = $co->prepare("SELECT COUNT(*) FROM tipo_de_examen WHERE cod_examen = ?");
        $stmt->execute([$cod_examen]);
        $total = $stmt->fetchColumn();
        $this->assertEquals(0, $total, "El tipo de examen debería haber sido eliminado.");
    }
    
    public function testConsultarTiposRetornaArrayCorrectamente(){
        $resultado = $this->examenes->consultar_tipos();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);

        // Opcional: verifica que cada registro tiene las claves esperadas
        foreach ($resultado['datos'] as $tipo) {
            $this->assertArrayHasKey('cod_examen', $tipo);
            $this->assertArrayHasKey('nombre_examen', $tipo);
            $this->assertArrayHasKey('descripcion_examen', $tipo);
        }
    }

    public function testObtenerTiposSelectRetornaArrayCorrectamente(){
        $resultado = $this->examenes->obtener_tipos_select();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);

        // Opcional: verifica que cada registro tiene las claves esperadas
        foreach ($resultado['datos'] as $tipo) {
            $this->assertArrayHasKey('cod_examen', $tipo);
            $this->assertArrayHasKey('nombre_examen', $tipo);
        }
    }

}