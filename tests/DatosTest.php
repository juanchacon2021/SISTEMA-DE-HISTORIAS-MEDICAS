<?php
use PHPUnit\Framework\TestCase;
use Shm\Shm\modelo\datos;

class DatosTest extends TestCase{
    
    public function testConexion(){
        $datos = new datos();
        
        // Probar primera conexión
        $conexion1 = $datos->conecta();
        $this->assertNotNull($conexion1);
        
        // Probar segunda conexión  
        $conexion2 = $datos->conecta2();
        $this->assertNotNull($conexion2);
        
        // Verificar que pueden hacer consultas
        $this->assertNotFalse($conexion1->query("SELECT 1"));
        $this->assertNotFalse($conexion2->query("SELECT 1"));
    }
}