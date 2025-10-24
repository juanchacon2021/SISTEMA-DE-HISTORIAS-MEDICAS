<?php
class datos
{
    private $ip = "localhost";
<<<<<<< HEAD
    private $bd = "sgm2";
=======
    private $bd = "prueva";
>>>>>>> 62c3445557c6751c97f314a93624eb42e6ebc22d
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
