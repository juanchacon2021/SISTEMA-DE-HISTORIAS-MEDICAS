<?php

namespace Shm\Shm\modelo;

use Pdo;

class datos
{
    private $ip = "localhost";
    private $bd = "prueba";
    private $bd2 = "seguridad";
    private $usuario = "root";
    private $contrasena = "123456";



    function conecta()
    {
        $pdo = new PDO("mysql:host=" . $this->ip . ";dbname=" . $this->bd . "", $this->usuario, $this->contrasena);
        $pdo->exec("set names utf8");
        return $pdo;
    }
    function conecta2()
    {
        $pdo = new PDO("mysql:host=" . $this->ip . ";dbname=" . $this->bd2 . "", $this->usuario, $this->contrasena);
        $pdo->exec("set names utf8");
        return $pdo;
    }
}
