<?php

class PokemonModel
{
    private $conecction;

    public function __construct($conexion)
    {
        $this->conecction = $conexion->getConnection();
    }

    // listo

    public function getAllPokemons()
    {
        $sql = "SELECT * FROM pokemon";
        $result = $this->conecction->query($sql);

        return $result;
    }

    //

    public function getFilteredPokemons($tipo, $nombre)
    {
        // Si se seleccionó un tipo y no es "todos"
        if ($tipo !== "" && $tipo !== "todos") {
            $sql = "SELECT * FROM pokemon WHERE tipo_id = ? OR tipo2_id = ? ORDER BY id ASC";
        } else {
            $sql = "SELECT * FROM pokemon ORDER BY id ASC"; // Mostrar todos los Pokémon
        }

        // Si se ingresó un nombre o un ID
        if (!empty($nombre)) {
            // Verifica si el valor ingresado es un número
            if (is_numeric($nombre)) {
                // Si es un número, busca por ID
                $sql = "SELECT * FROM pokemon WHERE id = ? ORDER BY id ASC";
            } else {
                // Si no es un número, busca por nombre
                $sql = "SELECT * FROM pokemon WHERE nombre LIKE ? ORDER BY id ASC";
                $nombre = '%' . $nombre . '%'; // Asegúrate de incluir los comodines para la búsqueda LIKE
            }
        }

        // Si se seleccionó un tipo y se ingresó un nombre
        if (!empty($tipo) && !empty($nombre)) {
            $sql = "SELECT * FROM pokemon WHERE tipo_id = ? AND nombre LIKE ? ORDER BY id ASC";
        }

        // Preparar la consulta
        $stmt = $this->conecction->prepare($sql);

        // Si se seleccionó un tipo y se ingresó un nombre
        if (!empty($tipo) && !empty($nombre)) {
            $stmt->bind_param("ss", $tipo, $nombre);
        } elseif (!empty($tipo)) { // Si solo se seleccionó un tipo
            if ($tipo !== "todos") {
                $stmt->bind_param("ss", $tipo, $tipo);
            } else {
                // No need to bind any parameters
            }
        } elseif (!empty($nombre)) { // Si solo se ingresó un nombre
            $stmt->bind_param("s", $nombre);
        }


        // Ejecutar la consulta
        $stmt->execute();

        // Obtener los resultados
        $result = $stmt->get_result();



        return $result;
    }

    public function getPokemon($pokemon_id)
    {
        $sql = "SELECT * FROM pokemon WHERE unique_id = $pokemon_id";
        $result = $this->conecction->query($sql);

        if ($result->num_rows == 0) {
            return null;
        }

        return $result->fetch_assoc();
    }

    public function getTipoId($tipo_id)
    {
        $stmt = $this->conecction->prepare("SELECT * FROM tipo_pokemon WHERE nombre=? ");
        $stmt->bind_param("s", $tipo_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();

        if ($resultado->num_rows == 1) {
            $row = $resultado->fetch_assoc();
            return $row["id"];
        }

        return null;
    }

    public function updatePokemon($id, $nombre, $descripcion, $altura, $peso, $tipo_id)
    {
        $tipo_id = $this->getTipoId($tipo_id);

        if ($tipo_id == null) {
            return false;
        }

        $stmt = $this->conecction->prepare("UPDATE pokemon SET nombre=?, descripcion=?, altura=?, peso=?, tipo_id=? WHERE unique_id=?");
        $stmt->bind_param("ssiiii", $nombre, $descripcion, $altura, $peso, $tipo_id, $id);
        $stmt->execute();
        $stmt->close();

        return true;
    }
}
