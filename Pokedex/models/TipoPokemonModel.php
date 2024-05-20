<?php

class TipoPokemonModel
{
    private $connection;

    public function __construct($conexion)
    {
        $this->connection = $conexion->getConnection();
    }

    public function obtenerNombreTipo($tipo_id) {
        // Consulta SQL para obtener el nombre del tipo
        $sql = "SELECT nombre FROM tipo_pokemon WHERE id = ?";

        // Preparar la consulta
        $stmt = $this->connection->prepare($sql);

        // Vincular los parámetros
        $stmt->bind_param("i", $tipo_id);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener los resultados
        $result = $stmt->get_result();

        // Verificar si se encontró el tipo
        if ($result->num_rows == 0) {
            return null;
        }else{
            $row = $result->fetch_assoc();
            return $row["nombre"];
        }
    }
}