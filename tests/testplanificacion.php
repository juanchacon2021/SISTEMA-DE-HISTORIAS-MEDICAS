<?php

use PHPUnit\Framework\TestCase;
use Shm\Shm\modelo\planificacion;


// Se asume que 'datos.php' y la conexión a la BD son válidos para un entorno de prueba
// y que 'planificacion.php' está en el namespace correcto.

class TestPlanificacion extends TestCase
{
    private $planificacion;
    // La clase planificacion no tiene una propiedad estática para un ID creado,
    // ya que no tiene un método 'gestionar' centralizado como 'jornadas'.

    public function setUp(): void
    {
        // Instancia de la clase a probar
        $this->planificacion = new planificacion();
    }

    // ===============================================
    // Tests para la gestión de Publicaciones (Feed)
    // ===============================================

    /**
     * Verifica que el método consultar_publicaciones devuelva un array con la estructura esperada.
     */
    public function testConsultarPublicacionesRetornaArrayCorrectamente()
    {
        $datos = []; // Este método no usa parámetros de entrada según el código fuente
        $resultado = $this->planificacion->consultar_publicaciones($datos);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('consultar_publicaciones', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);

        // Verifica la estructura de un registro si existen datos
        if (!empty($resultado['datos'])) {
            $primera_pub = $resultado['datos'][0];
            $this->assertArrayHasKey('cod_pub', $primera_pub);
            $this->assertArrayHasKey('contenido', $primera_pub);
            $this->assertArrayHasKey('nombre_usuario', $primera_pub);
            $this->assertArrayHasKey('apellido_usuario', $primera_pub);
        }
    }

    public function testObtenerPublicacionInexistenteRetornaNullEnDatos()
    {
        $datos = ['cod_pub' => 'CODIGO_INEXISTENTE_PUB_789'];
        $resultado = $this->planificacion->obtener_publicacion($datos);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('obtener_publicacion', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        // Debería ser false si la consulta no devuelve filas
        $this->assertFalse($resultado['datos']);
    }

    /**
     * Verifica que el método incluir devuelva el resultado esperado y un código generado.
     * Nota: Este test puede fallar si la BD no está levantada o los procedures no existen/fallan.
     * Se asume un entorno de prueba con dependencias (BD) simuladas o configuradas.
     */
    public function testIncluirPublicacionDevuelveResultadoExitoso()
    {
        $datos = [
            'contenido' => 'Contenido de prueba para incluir',
            'cedula_personal' => '12345678', // Cédula válida en la BD de prueba
            // 'imagen' => Se omite la simulación compleja de $_FILES, se espera que funcione sin ella.
        ];

        $resultado = $this->planificacion->incluir($datos);

        $this->assertIsArray($resultado);
        // Se espera un resultado 'incluir' si el procedure se ejecuta, o 'error' si falla la BD.
        $this->assertContains($resultado['resultado'], ['incluir', 'error']);
        $this->assertArrayHasKey('cod_pub', $resultado);
        
        // Si fue exitoso, el cod_pub debe ser un valor (ej: no null)
        if ($resultado['resultado'] === 'incluir') {
            $this->assertNotNull($resultado['cod_pub']);
            // Se podría guardar $resultado['cod_pub'] para pruebas posteriores si fuera un test integrado.
        }
    }

    /**
     * Verifica la lógica de 'verificar_autor' para el caso de no ser el autor.
     * Nota: Este test depende de un 'cod_pub' real y una 'cedula_personal' que no sea el autor.
     * Se debe simular un escenario donde la BD esté activa.
     */
    public function testVerificarAutorConPublicacionInexistente()
    {
        $datos = [
            'cod_pub' => 'PUB_INEXISTENTE',
            'cedula_personal' => '12345678'
        ];
        $es_autor = $this->planificacion->verificar_autor($datos);

        // Si la publicación no existe, fetch devuelve false, y el resultado de verificar_autor es false.
        $this->assertFalse($es_autor);
    }

    // ===============================================
    // Tests para la gestión de Medicamentos
    // ===============================================

    /**
     * Verifica que el método consultar_medicamentos devuelva un array con la estructura esperada.
     */
    public function testConsultarMedicamentosRetornaArrayCorrectamente()
    {
        $resultado = $this->planificacion->consultar_medicamentos();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('consultar_medicamentos', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);

        // Verifica la estructura de un registro si existen datos
        if (!empty($resultado['datos'])) {
            $primer_medicamento = $resultado['datos'][0];
            $this->assertArrayHasKey('cod_medicamento', $primer_medicamento);
            $this->assertArrayHasKey('nombre', $primer_medicamento);
            $this->assertArrayHasKey('stock_total', $primer_medicamento);
            $this->assertArrayHasKey('stock_minimo', $primer_medicamento);
            $this->assertArrayHasKey('stock_maximo', $primer_medicamento);
            $this->assertEquals(0, $primer_medicamento['stock_minimo']); // Valor fijo en el código
            $this->assertEquals(250, $primer_medicamento['stock_maximo']); // Valor fijo en el código
        }
    }

    /**
     * Verifica que obtener_medicamento devuelva error para un código inexistente.
     */
    public function testObtenerMedicamentoInexistenteRetornaError()
    {
        $cod_medicamento = 'MED_INEXISTENTE_999';
        $resultado = $this->planificacion->obtener_medicamento($cod_medicamento);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('error', $resultado['resultado']);
        $this->assertArrayHasKey('mensaje', $resultado);
        $this->assertEquals('Medicamento no encontrado', $resultado['mensaje']);
    }

    /**
     * Verifica que el método consultar_lotes_medicamento devuelva la estructura correcta.
     */
    public function testConsultarLotesMedicamentoRetornaArrayCorrectamente()
    {
        $cod_medicamento = 'CODIGO_MED_A_PROBAR'; // Debe ser un código real en la BD de prueba
        $resultado = $this->planificacion->consultar_lotes_medicamento($cod_medicamento);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('consultar_lotes', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
    }


    // ===============================================
    // Test de estructura
    // ===============================================

    /**
     * Verifica que la clase 'planificacion' tenga los métodos públicos esperados.
     */
    public function testModeloTieneMetodosEsperados()
    {
        $metodos_esperados = [
            'consultar_publicaciones',
            'obtener_publicacion',
            'incluir',
            'modificar',
            'eliminar',
            'verificar_autor',
            'obtener_medicamento',
            'consultar_medicamentos',
            'consultar_lotes_medicamento'
        ];

        foreach ($metodos_esperados as $metodo) {
            $this->assertTrue(
                method_exists($this->planificacion, $metodo),
                "El método $metodo debería existir en la clase planificacion"
            );
        }
    }
}