<?php
$configFilePath = __DIR__ . '/../config.ini';
$config = parse_ini_file($configFilePath);

class Database {

    private $connection;

    public function __construct($servername, $user, $pass, $db) {

        $this->connection = mysqli_connect($servername, $user, $pass, $db);

        if ($this->connection->connect_error) {
            die("Conexión fallida: " . $this->connection->connect_error);
        }
    }

    public function query($sql)
    {
        $result = mysqli_query($this->connection, $sql);

        if ($result === FALSE) {
            throw new Exception('Error en la consulta SQL: ' . mysqli_error($this->connection));
        }

        return $result;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    /* cierro la conexion
    public function __destruct() {
        mysqli_close($this->connection);
    }
    */

}

$servername = $config['servername'];
$db = $config['db'];
$user = $config['user'];
$pass = $config['pass'];

