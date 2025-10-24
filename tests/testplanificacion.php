<?php

use PHPUnit\Framework\TestCase;
use Shm\Shm\modelo\planificacion;

class TestPlanificacion extends TestCase
{
    private $planificacion;

    public function setUp(): void
    {
        $this->planificacion = new planificacion();
    }

    public function testConsultarPublicacionesRetornaArrayCorrectamente()
    {
        $datos = [];
        $resultado = $this->planificacion->consultar_publicaciones($datos);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertEquals('consultar_publicaciones', $resultado['resultado']);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);

        
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
        $this->assertFalse($resultado['datos']);
    }

    
    public function testIncluirPublicacionDevuelveResultadoExitoso()
    {
        $datos = [
            'contenido' => 'Contenido de prueba para incluir',
            'cedula_personal' => '12345678',
        ];

        $resultado = $this->planificacion->incluir($datos);

        $this->assertIsArray($resultado);
        $this->assertContains($resultado['resultado'], ['incluir', 'error']);
        $this->assertArrayHasKey('cod_pub', $resultado);
        
        if ($resultado['resultado'] === 'incluir') {
            $this->assertNotNull($resultado['cod_pub']);
        }
    }

    
    public function testVerificarAutorConPublicacionInexistente()
    {
        $datos = [
            'cod_pub' => 'PUB_INEXISTENTE',
            'cedula_personal' => '12345678'
        ];
        $es_autor = $this->planificacion->verificar_autor($datos);

        $this->assertFalse($es_autor);
    }

   

    
    public function testModeloTieneMetodosEsperados()
    {
        $metodos_esperados = [
            'consultar_publicaciones',
            'obtener_publicacion',
            'incluir',
            'modificar',
            'eliminar',
            'verificar_autor'
        ];

        foreach ($metodos_esperados as $metodo) {
            $this->assertTrue(
                method_exists($this->planificacion, $metodo),
                "El método $metodo debería existir en la clase planificacion"
            );
        }
    }
}