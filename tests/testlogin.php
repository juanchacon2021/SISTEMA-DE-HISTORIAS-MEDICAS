<?php
use PHPUnit\Framework\TestCase;
use Shm\Shm\modelo\login;

class testLogin extends TestCase{

    private $login;

    public function setUp(): void {
        $this->login = new login();
        // Iniciar session para las pruebas
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function tearDown(): void {
        // Limpiar session después de cada prueba
        session_destroy();
    }

    public function testSettersAndGettersWorkCorrectly(){
        $cedula = '32014004';
        $clave = 'Dino1234';

        $this->login->set_cedula($cedula);
        $this->login->set_clave($clave);

        $this->assertEquals($cedula, $this->login->get_cedula());
        $this->assertEquals($clave, $this->login->get_clave());
    }

    public function testExisteWithValidCredentials(){
        $this->login->set_cedula('32014004');
        $this->login->set_clave('Dino1234');

        $resultado = $this->login->existe();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('existe', $resultado['resultado']);
        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertArrayHasKey('usuario', $resultado);
        $this->assertArrayHasKey('nombre', $resultado);
        $this->assertArrayHasKey('cedula_personal', $resultado);
        $this->assertArrayHasKey('permisos', $resultado);
        
        // Verificar estructura de permisos
        $this->assertIsArray($resultado['permisos']);
        $this->assertArrayHasKey('modulos', $resultado['permisos']);
        $this->assertArrayHasKey('acciones', $resultado['permisos']);
        $this->assertIsArray($resultado['permisos']['modulos']);
        $this->assertIsArray($resultado['permisos']['acciones']);

        // Verificar que se estableció la sesión
        $this->assertArrayHasKey('usuario', $_SESSION);
        $this->assertEquals('32014004', $_SESSION['usuario']);
    }

    public function testExisteWithInvalidCedula(){
        $this->login->set_cedula('99999999'); // Cédula que no existe
        $this->login->set_clave('Dino1234');

        $resultado = $this->login->existe();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('noexiste', $resultado['resultado']);
        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertEquals('Usuario no encontrado', $resultado['mensaje']);
    }

    public function testExisteWithInvalidPassword(){
        $this->login->set_cedula('32014004');
        $this->login->set_clave('ClaveIncorrecta');

        $resultado = $this->login->existe();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('noexiste', $resultado['resultado']);
        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertEquals('Credenciales incorrectas', $resultado['mensaje']);
    }

       public function testExisteWithEmptyCredentials(){
        $this->login->set_cedula('');
        $this->login->set_clave('');

        $resultado = $this->login->existe();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        // Puede ser 'noexiste' o 'error' dependiendo de cómo maneje la BD valores vacíos
        $this->assertContains($resultado['resultado'], ['noexiste', 'error']);
        $this->assertArrayHasKey('mensaje', $resultado);
    }

     protected function invokePrivateMethod($object, $methodName, array $parameters = []) {
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }

    public function testObtenerPermisosRolWithValidRolId(){
        // Usar un rol_id que exista en tu base de datos
        $rol_id = 3; // Cambia por un ID válido según tu BD
        
        $permisos = $this->invokePrivateMethod($this->login, 'obtener_permisos_rol', [$rol_id]);

        $this->assertIsArray($permisos);
        $this->assertArrayHasKey('modulos', $permisos);
        $this->assertArrayHasKey('acciones', $permisos);
        $this->assertIsArray($permisos['modulos']);
        $this->assertIsArray($permisos['acciones']);
    }

   

}