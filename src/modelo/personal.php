<?php
namespace Shm\Shm\modelo;
use Shm\Shm\modelo\datos;
use Pdo;
use Exception;


class personal extends datos{
    
    
    private $cedula_personal; 
    private $nombre;
    private $apellido;
    private $correo;
    private $telefonos = array();
    private $cargo;
    
    
    
    function set_cedula_personal($valor){
        $this->cedula_personal = $valor; 
        
    }
    
    
    function set_apellido($valor){
        $this->apellido = $valor;
    }
    
    function set_nombre($valor){
        $this->nombre = $valor;
    }
    
    function set_correo($valor){
        $this->correo = $valor;
    }
    
    function set_telefonos($telefonos){
        $this->telefonos = $telefonos;
    }
    
    function set_cargo($valor){
        $this->cargo = $valor;
    }

    
    
    function get_cedula_personal(){
        return $this->cedula_personal;
    }
    
    function get_apellido(){
        return $this->apellido;
    }
    
    function get_nombre(){
        return $this->nombre;
    }
    
    function get_correo(){
        return $this->correo;
    }
    
    function get_telefonos(){
        return $this->telefonos;
    }
    
    function get_cargo(){
        return $this->cargo;
    }

    
    
    
    function incluir(){
        $r = array();
        if(!$this->existe($this->cedula_personal)){
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            try {
                // Iniciar transacción
                $co->beginTransaction();
                
                // Insertar datos básicos del personal
                $co->query("Insert into personal(
                    cedula_personal,
                    apellido,
                    nombre,
                    correo,
                    cargo
                    )
                    Values(
                    '$this->cedula_personal',
                    '$this->apellido',
                    '$this->nombre',
                    '$this->correo',
                    '$this->cargo'
                    )");
                
                foreach($this->telefonos as $telefono){
                    $co->query("Insert into telefonos_personal(
                        cedula_personal,
                        telefono
                        )
                        Values(
                        '$this->cedula_personal',
                        '$telefono'
                        )");
                }
                
                $co->commit();
                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Registro Incluido';
            } catch(Exception $e) {
                $co->rollBack();
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        }
        else{
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Ya existe la cedula';
        }
        return $r;
    }
    
    function modificar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if($this->existe($this->cedula_personal)){
            try {
                $co->beginTransaction();
                
                $co->query("Update personal set 
                    apellido = '$this->apellido',
                    nombre = '$this->nombre',
                    correo = '$this->correo',
                    cargo = '$this->cargo'
                    where
                    cedula_personal = '$this->cedula_personal'
                    ");
                
                $co->query("Delete from telefonos_personal where cedula_personal = '$this->cedula_personal'");
                
                foreach($this->telefonos as $telefono){
                    $co->query("Insert into telefonos_personal(
                        cedula_personal,
                        telefono
                        )
                        Values(
                        '$this->cedula_personal',
                        '$telefono'
                        )");
                }
                
                $co->commit();
                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Registro Modificado';
            } catch(Exception $e) {
                $co->rollBack();
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        }
        else{
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Cedula no registrada';
        }
        return $r;
    }
    
    function eliminar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if($this->existe($this->cedula_personal)){
            try {
                $co->beginTransaction();
                
                $co->query("Delete from telefonos_personal where cedula_personal = '$this->cedula_personal'");
                
                $co->query("Delete from personal where cedula_personal = '$this->cedula_personal'");
                
                $co->commit();
                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Registro Eliminado';
            } catch(Exception $e) {
                $co->rollBack();
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        }
        else{
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'No existe la cedula';
        }
        return $r;
    }
    
    
    public function consultar(){
        
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("SELECT p.*, 
                                   GROUP_CONCAT(tp.telefono SEPARATOR ', ') as telefonos 
                                   FROM personal p
                                   LEFT JOIN telefonos_personal tp ON p.cedula_personal = tp.cedula_personal
                                   GROUP BY p.cedula_personal");
            
            if ($resultado) {
                $r['resultado'] = 'consultar';
                $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $r['resultado'] = 'consultar';
                $r['datos'] = array();
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
        
    private function existe($cedula_personal){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try{
            
            $resultado = $co->query("Select * from personal where cedula_personal ='$cedula_personal'");
            
            
            $fila = $resultado->fetchAll(PDO::FETCH_BOTH);
            if($fila){

                return true;
                
            }
            else{
                
                return false;;
            }
            
        }catch(Exception $e){
            return false;
        }
    }
}
?>