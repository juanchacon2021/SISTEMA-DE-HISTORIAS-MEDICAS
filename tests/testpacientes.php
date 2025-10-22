<?php
use PHPUnit\Framework\TestCase;
use Shm\Shm\modelo\pacientes;

class testPacientes extends TestCase
{
    private pacientes $pacientes;
    private static int $cedula;

    protected function setUp(): void
    {
        $this->pacientes = new pacientes();
        if (!isset(self::$cedula)) {
            self::$cedula = 30000000 + random_int(1, 999);
        }
    }

    public function testConsultarDevuelveEstructuraValida(): void
    {
        $r = $this->pacientes->consultar();

        $this->assertIsArray($r);
        $this->assertArrayHasKey('resultado', $r);
        $this->assertEquals('consultar', $r['resultado']);
        $this->assertArrayHasKey('datos', $r);
        $this->assertIsArray($r['datos']);
    }

    public function testListadoPatologiasDevuelveEstructuraValida(): void
    {
        $r = $this->pacientes->listado_patologias();

        $this->assertIsArray($r);
        $this->assertArrayHasKey('resultado', $r);
        $this->assertEquals('listado_patologias', $r['resultado']);
        $this->assertArrayHasKey('datos', $r);
        $this->assertIsArray($r['datos']);
    }

    public function testIncluirPacienteRegistraCorrectamente(): void
    {
        $datos = [
            'cedula_paciente' => self::$cedula,
            'nombre' => 'Nombre PHPUnit',
            'apellido' => 'Apellido PHPUnit',
            'fecha_nac' => '1990-01-01',
            'edad' => 35,
            'telefono' => '0412-0000000',
            'estadocivil' => 'Soltero',
            'direccion' => 'Calle Prueba',
            'ocupacion' => 'Tester',
            'hda' => '',
            'habtoxico' => '',
            'alergias' => '',
            'alergias_med' => '',
            'quirurgico' => '',
            'transsanguineo' => '',
            'psicosocial' => ''
        ];

        $r = $this->pacientes->incluir($datos);

        $this->assertIsArray($r);
        $this->assertArrayHasKey('resultado', $r);
        // El modelo usa 'success' para incluir; aceptamos también 'incluir' por compatibilidad
        $this->assertContains($r['resultado'], ['success', 'incluir']);
        $this->assertArrayHasKey('mensaje', $r);

        // Verificar en BD
        $co = $this->pacientes->conecta();
        $stmt = $co->prepare('SELECT COUNT(*) FROM paciente WHERE cedula_paciente = ?');
        $stmt->execute([self::$cedula]);
        $this->assertEquals(1, (int)$stmt->fetchColumn());
    }

    public function testModificarPacienteActualizaCorrectamente(): void
    {
        $datos = [
            'cedula_paciente' => self::$cedula,
            'nombre' => 'Nombre Mod PHP',
            'apellido' => 'Apellido Mod PHP',
            'fecha_nac' => '1990-01-01',
            'edad' => 36,
            'telefono' => '0412-1111111',
            'estadocivil' => 'Casado',
            'direccion' => 'Avenida Mod',
            'ocupacion' => 'QA',
            'hda' => 'N/A',
            'habtoxico' => 'No',
            'alergias' => 'No',
            'alergias_med' => 'No',
            'quirurgico' => 'No',
            'transsanguineo' => 'No',
            'psicosocial' => 'N/A'
        ];

        $r = $this->pacientes->modificar($datos);

        $this->assertIsArray($r);
        $this->assertArrayHasKey('resultado', $r);
        $this->assertEquals('modificar', $r['resultado']);
        $this->assertArrayHasKey('mensaje', $r);

        // Verificar cambio
        $co = $this->pacientes->conecta();
        $stmt = $co->prepare('SELECT nombre, apellido, telefono, estadocivil FROM paciente WHERE cedula_paciente = ?');
        $stmt->execute([self::$cedula]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals('Nombre Mod PHP', $row['nombre']);
        $this->assertEquals('Apellido Mod PHP', $row['apellido']);
        $this->assertEquals('0412-1111111', $row['telefono']);
        $this->assertEquals('Casado', $row['estadocivil']);
    }

    public function testObtenerPatologiasPacienteDevuelveArray(): void
    {
        $r = $this->pacientes->obtener_patologias_paciente(['cedula_paciente' => self::$cedula]);

        $this->assertIsArray($r);
        $this->assertArrayHasKey('resultado', $r);
        $this->assertEquals('obtener_patologias_paciente', $r['resultado']);
        $this->assertArrayHasKey('datos', $r);
        $this->assertIsArray($r['datos']);
    }

    public function testEliminarPacienteEliminaCorrectamente(): void
    {
        $r = $this->pacientes->eliminar(['cedula_paciente' => self::$cedula]);

        $this->assertIsArray($r);
        $this->assertArrayHasKey('resultado', $r);
        $this->assertEquals('eliminar', $r['resultado']);
        $this->assertArrayHasKey('mensaje', $r);

        // Verificar en BD
        $co = $this->pacientes->conecta();
        $stmt = $co->prepare('SELECT COUNT(*) FROM paciente WHERE cedula_paciente = ?');
        $stmt->execute([self::$cedula]);
        $this->assertEquals(0, (int)$stmt->fetchColumn());
    }
}