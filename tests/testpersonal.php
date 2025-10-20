<?php
use PHPUnit\Framework\TestCase;
use Shm\Shm\modelo\personal;


class testPersonal extends TestCase{
    
    private $personal;
    private $datosPrueba;

    public function setUp(): void {
        $this->personal = new personal();
        
        // Datos de prueba que se usarán en múltiples tests
        $this->datosPrueba = [
            'cedula_personal' => 30000001,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'correo' => 'juan.perez@test.com',
            'cargo' => 'Doctor',
            'telefonos' => ['0412-1234567', '0424-9876543']
        ];
    }

    public function testConsultarReturnsArray(){
        $resultado = $this->personal->consultar();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
        
        // Verificar que los datos tienen la estructura esperada
        if (!empty($resultado['datos'])) {
            $primerRegistro = $resultado['datos'][0];
            $this->assertArrayHasKey('cedula_personal', $primerRegistro);
            $this->assertArrayHasKey('nombre', $primerRegistro);
            $this->assertArrayHasKey('apellido', $primerRegistro);
            $this->assertArrayHasKey('correo', $primerRegistro);
            $this->assertArrayHasKey('cargo', $primerRegistro);
        }
    }

    public function testObtenerDoctoresReturnsArray(){
        $resultado = $this->personal->obtener_doctores();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
        
        // Verificar estructura de datos de doctores
        if (!empty($resultado['datos'])) {
            $primerDoctor = $resultado['datos'][0];
            $this->assertArrayHasKey('cedula_personal', $primerDoctor);
            $this->assertArrayHasKey('nombre_completo', $primerDoctor);
        }
    }

    public function testIncluirPersonal(){
        $datosIncluir = array_merge($this->datosPrueba, ['accion' => 'incluir']);
        
        $resultado = $this->personal->gestionar_personal($datosIncluir);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('incluir', $resultado['resultado']);
        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertEquals('Registro Incluido', $resultado['mensaje']);
    }

    public function testIncluirPersonalConTelefonosStringJSON(){
        $datosIncluir = $this->datosPrueba;
        $datosIncluir['cedula_personal'] = 30000002;
        $datosIncluir['telefonos'] = json_encode(['0416-5555555', '0426-6666666']);
        $datosIncluir['accion'] = 'incluir';
        
        $resultado = $this->personal->gestionar_personal($datosIncluir);

        $this->assertIsArray($resultado);
        $this->assertEquals('incluir', $resultado['resultado']);
        $this->assertEquals('Registro Incluido', $resultado['mensaje']);
    }

    public function testIncluirPersonalConTelefonoStringSimple(){
        $datosIncluir = $this->datosPrueba;
        $datosIncluir['cedula_personal'] = 30000003;
        $datosIncluir['telefonos'] = '0412-9999999';
        $datosIncluir['accion'] = 'incluir';
        
        $resultado = $this->personal->gestionar_personal($datosIncluir);

        $this->assertIsArray($resultado);
        $this->assertEquals('incluir', $resultado['resultado']);
        $this->assertEquals('Registro Incluido', $resultado['mensaje']);
    }

    public function testModificarPersonal(){
        // Primero incluir un registro para luego modificarlo
        $datosIncluir = $this->datosPrueba;
        $datosIncluir['cedula_personal'] = 30000004;
        $datosIncluir['accion'] = 'incluir';
        $this->personal->gestionar_personal($datosIncluir);

        // Ahora modificar
        $datosModificar = [
            'cedula_personal' => 30000004,
            'nombre' => 'Carlos',
            'apellido' => 'González',
            'correo' => 'carlos.gonzalez@test.com',
            'cargo' => 'Enfermero',
            'telefonos' => ['0414-7777777'],
            'accion' => 'modificar'
        ];

        $resultado = $this->personal->gestionar_personal($datosModificar);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('modificar', $resultado['resultado']);
        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertEquals('Registro Modificado', $resultado['mensaje']);
    }

    public function testEliminarPersonal(){
        // Primero incluir un registro para luego eliminarlo
        $datosIncluir = $this->datosPrueba;
        $datosIncluir['cedula_personal'] = 30000005;
        $datosIncluir['accion'] = 'incluir';
        $this->personal->gestionar_personal($datosIncluir);

        // Ahora eliminar
        $datosEliminar = [
            'cedula_personal' => 30000005,
            'accion' => 'eliminar'
        ];

        $resultado = $this->personal->gestionar_personal($datosEliminar);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('eliminar', $resultado['resultado']);
        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertEquals('Registro Eliminado', $resultado['mensaje']);
    }

    public function testAccionNoValida(){
        $datos = [
            'cedula_personal' => 30000006,
            'accion' => 'accion_no_valida'
        ];

        $resultado = $this->personal->gestionar_personal($datos);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('error', $resultado['resultado']);
        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertEquals('Acción no válida', $resultado['mensaje']);
    }

    public function testObtenerUsuarioPersonal(){
        // Primero incluir un usuario de prueba
        $datosIncluir = $this->datosPrueba;
        $datosIncluir['cedula_personal'] = 30000007;
        $datosIncluir['accion'] = 'incluir';
        $this->personal->gestionar_personal($datosIncluir);

        // Probar el método estático
        $usuario = personal::obtenerUsuarioPersonal(30000007);

        $this->assertIsArray($usuario);
        $this->assertArrayHasKey('nombre', $usuario);
        $this->assertArrayHasKey('apellido', $usuario);
        $this->assertEquals('Juan', $usuario['nombre']);
        $this->assertEquals('Pérez', $usuario['apellido']);
    }

    public function testObtenerUsuarioPersonalNoExistente(){
        $usuario = personal::obtenerUsuarioPersonal(99999999);

        $this->assertIsArray($usuario);
        $this->assertArrayHasKey('nombre', $usuario);
        $this->assertArrayHasKey('apellido', $usuario);
        $this->assertEquals('Desconocido', $usuario['nombre']);
        $this->assertEquals('', $usuario['apellido']);
    }

    // Limpieza después de todas las pruebas
    public static function tearDownAfterClass(): void {
        $personal = new personal();
        
        // Eliminar todos los registros de prueba creados
        $cedulasPrueba = [30000001, 30000002, 30000003, 30000004, 30000007];
        
        foreach ($cedulasPrueba as $cedula) {
            try {
                $datosEliminar = [
                    'cedula_personal' => $cedula,
                    'accion' => 'eliminar'
                ];
                $personal->gestionar_personal($datosEliminar);
            } catch (Exception $e) {
                // Ignorar errores en limpieza
            }
        }
    }
}