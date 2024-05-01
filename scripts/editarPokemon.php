<?php

include("database.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {

 /*
    $stmt = $conexion->prepare("UPDATE pokemons set  nombre=?, altura=?, peso=?, tipo_id=? WHERE id=?");
    $stmt->bind_param("sddii", $nombrePokemon, $alturaPokemon, $pesoPokemon, $tipoPokemon, $idPokemon);
    $stmt->execute();
    $stmt->close();
    */
}

$conexion->close();