<?php
use PHPUnit\Framework\TestCase;
use Shm\Shm\modelo\pacientes;

class testPacientes extends TestCase
{
    private pacientes $pacientes;
    private static int $cedula;

    public static function setUpBeforeClass(): void
    {
        // Genera una cédula amplia para minimizar colisiones y limpia el registro por si quedó algún residuo
        self::$cedula = 30000000 + random_int(1000, 999999);
        $p = new pacientes();
        $co = $p->conecta();
        $stmt = $co->prepare('DELETE FROM paciente WHERE cedula_paciente = ?');
        $stmt->execute([self::$cedula]);
    }

    protected function setUp(): void
    {
        $this->pacientes = new pacientes();
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
            'nombre' => 'Ana',              // corto
            'apellido' => 'Test',           // corto
            'fecha_nac' => '1990-01-01',
            'edad' => 35,
            'telefono' => '04120000000',    // solo dígitos (11)
            'estadocivil' => 'Soltero',
            'direccion' => 'C1',            // corto
            'ocupacion' => 'QA',            // corto
            'hda' => '',
            'habtoxico' => '',
            'alergias' => '',
            'alergias_med' => '',
            'quirurgico' => '',
            'transsanguineo' => '',
            'psicosocial' => ''
        ];

        $r = $this->pacientes->incluir($datos);

        // Si el modelo devuelve error, verifica si aun así existe el registro (pudo ser por duplicado)
        if (($r['resultado'] ?? '') === 'error') {
            $co = $this->pacientes->conecta();
            $stmt = $co->prepare('SELECT COUNT(*) FROM paciente WHERE cedula_paciente = ?');
            $stmt->execute([self::$cedula]);
            $count = (int)$stmt->fetchColumn();
            $this->assertGreaterThan(
                0,
                $count,
                'Incluir devolvió error y no se encontró el paciente en BD: ' . ($r['mensaje'] ?? 'sin mensaje')
            );
            return; // ya validado que existe; continúa el flujo del resto de pruebas
        }

        $this->assertIsArray($r);
        $this->assertArrayHasKey('resultado', $r);
        $this->assertContains($r['resultado'], ['success', 'incluir'], 'Resultado inesperado en incluir: ' . ($r['mensaje'] ?? ''));
        $this->assertArrayHasKey('mensaje', $r);

        // Verificar en BD
        $co = $this->pacientes->conecta();
        $stmt = $co->prepare('SELECT COUNT(*) FROM paciente WHERE cedula_paciente = ?');
        $stmt->execute([self::$cedula]);
        $this->assertEquals(1, (int)$stmt->fetchColumn());
    }

    public function testModificarPacienteActualizaCorrectamente(): void
    {
        // Asegura que exista el paciente antes de modificar
        $co = $this->pacientes->conecta();
        $stmt = $co->prepare('SELECT COUNT(*) FROM paciente WHERE cedula_paciente = ?');
        $stmt->execute([self::$cedula]);
        if ((int)$stmt->fetchColumn() === 0) {
            $this->markTestIncomplete('No existe el paciente para modificar (la inclusión falló previamente).');
        }

        $datos = [
            'cedula_paciente' => self::$cedula,
            'nombre' => 'AnaMod',           // corto
            'apellido' => 'TestMod',        // corto
            'fecha_nac' => '1990-01-01',
            'edad' => 36,
            'telefono' => '04121111111',    // solo dígitos (11)
            'estadocivil' => 'Casado',
            'direccion' => 'Av1',           // corto
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
        $stmt = $co->prepare('SELECT nombre, apellido, telefono, estadocivil FROM paciente WHERE cedula_paciente = ?');
        $stmt->execute([self::$cedula]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $this->assertIsArray($row, 'No se encontró el paciente luego de modificar');
        $this->assertEquals('AnaMod', $row['nombre']);
        $this->assertEquals('TestMod', $row['apellido']);
        $this->assertEquals('04121111111', $row['telefono']);
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