<?php
use PHPUnit\Framework\TestCase;
use Shm\Shm\modelo\usuarios;

class testUsuarios extends TestCase{

    private $usuarios;
    private static $usuario_id;
    private static $rol_id;
    private static $modulo_id;

    public function setUp(): void {
        $this->usuarios = new usuarios();
    }

      public function testIncluirUsuarioAgregaUsuarioCorrectamente(){
        $datos = [
            'nombre' => 'Usuario Test PHPUnit',
            'cedula' => '20000001',
            'password' => 'password123',
            'rol_id' => 3,
            'foto_perfil' => null
        ];

        // Configurar los datos en el objeto usuarios
          $this->usuarios->set_nombre($datos['nombre']);
        $this->usuarios->set_cedula($datos['cedula']);
        $this->usuarios->set_password($datos['password']);
        $this->usuarios->set_rol_id($datos['rol_id']);
        $this->usuarios->set_foto_perfil($datos['foto_perfil']);

        $resultado = $this->usuarios->incluir_usuario();

        $this->assertIsArray($resultado);
        $this->assertEquals('incluir', $resultado['resultado']);
        $this->assertEquals('Usuario registrado exitosamente', $resultado['mensaje']);

        // Guardar el ID para otros tests (necesitaríamos una forma de obtenerlo)
        // self::$usuario_id = $resultado['id'];
    }

    public function testModificarUsuarioActualizaCorrectamente(){
        // Cedula usada en la prueba de inclusión
        
        $datos = [
            'nombre' => 'Usuario Test Modificado',
            'cedula' => '20000001', 
            'password' => 'nuevoPassword123',
            'rol_id' => 3,
            'foto_perfil' => null
        ];
        $this->usuarios->set_nombre($datos['nombre']);
        $this->usuarios->set_cedula($datos['cedula']);
        $this->usuarios->set_password($datos['password']);
        $this->usuarios->set_rol_id($datos['rol_id']);
        $this->usuarios->set_foto_perfil($datos['foto_perfil']);
      

        // Ejecutar modificación
        $resultado = $this->usuarios->modificar_usuario();

        $this->assertIsArray($resultado);
        $this->assertEquals('modificar', $resultado['resultado']);
        $this->assertEquals('Usuario actualizado exitosamente', $resultado['mensaje']);
    }


    public function testEliminarUsuarioEliminaCorrectamente(){
        // Cédula usada en test de inclusión/modificación
        $cedula_prueba = '20000001';

        // Buscar el id del usuario creado
        $co = $this->usuarios->conecta2();
        $stmt = $co->prepare("SELECT id FROM usuario WHERE cedula_personal = ?");
        $stmt->execute([$cedula_prueba]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($fila, 'Usuario de prueba no encontrado para eliminar');

        $id = $fila['id'];

        $this->usuarios->set_id($id);
      
        // Ejecutar eliminación
        $resultado = $this->usuarios->eliminar_usuario();

        $this->assertIsArray($resultado);
        $this->assertEquals('eliminar', $resultado['resultado']);
        $this->assertEquals('Usuario eliminado exitosamente', $resultado['mensaje']);

      
    }

    



}