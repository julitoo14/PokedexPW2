<?php
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["unique_id"])) {
    $idPokemon = $_POST["unique_id"];

    $stmt = $conexion->prepare("DELETE FROM pokemon WHERE unique_id = ?");
    if ($stmt) {
        $stmt->bind_param('i', $idPokemon);

        if ($stmt->execute()) {
            header("Location: ../index.php");
            exit;
        } else {
            echo "Error al eliminar el Pokémon: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }
} else {
    echo "Acceso no autorizado o falta el parámetro 'id'.";
}

$conexion->close();
?>
