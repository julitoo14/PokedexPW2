<?php
$TipoPokemonModel = Configuration::getTipoPokemonModel();

while ($row = $result->fetch_assoc()) {

    echo '<div class="col-md-3 mb-4">';
    echo '<div class="card text-center">';
    echo '<div class="card-body">';
    echo '<h5 class="card-title" style="font-weight: bolder">' . $row["nombre"] . '</h5>';
    echo "<a href=\"scripts/detalle.php?unique_id={$row['unique_id']}\"><img src=\"data:image/jpeg;base64," . base64_encode($row["imagen"]) . "\" class=\"imagen-pokemon mx-auto\" alt=\"" . $row["nombre"] . "\"></a>";
    echo '<p class="card-text">Nro: ' . $row["id"] . '</p>';


    if (!empty($row["tipo2_id"])) {
        $tipo1_imagen = 'assets/tipos/' . strtolower($row["tipo_id"]) . '.webp';
        $tipo2_imagen = 'assets/tipos/' . strtolower($row["tipo2_id"]) . '.webp';

        // Obtener el nombre del tipo 1
        $tipo1_nombre = $TipoPokemonModel->obtenerNombreTipo($row["tipo_id"]);

        // Obtener el nombre del tipo 2
        $tipo2_nombre = $TipoPokemonModel->obtenerNombreTipo($row["tipo2_id"]);

        echo '<img class="m-1 imagen-tipo" src="' . $tipo1_imagen . '" alt="' . $tipo1_nombre . '" title="' . $tipo1_nombre . '">';
        echo '<img class="m-1 imagen-tipo" src="' . $tipo2_imagen . '" alt="' . $tipo2_nombre . '" title="' . $tipo2_nombre . '">';
    } else {
        $tipo1_imagen = 'assets/tipos/' . strtolower($row["tipo_id"]) . '.webp';

        // Obtener el nombre del tipo 1
        $tipo1_nombre = $TipoPokemonModel->obtenerNombreTipo($row["tipo_id"]);

        echo '<img class="m-1 imagen-tipo" src="' . $tipo1_imagen . '" alt="' . $tipo1_nombre . '" title="' . $tipo1_nombre . '">';
    }
    // Función para obtener el nombre del tipo a partir de su ID



    if (isset($_SESSION['roleID']) && $_SESSION['roleID'] === 1) {
        echo '<div class="d-flex justify-content-center m-2">';
        echo '<form class="m-1" method="post" action="scripts/editarPokemon.php?unique_id=' . $row["unique_id"] . '">';
        echo '<button type="submit" class="btn btn-primary"><i class="bi bi-pencil"></i></button>';
        echo '</form>';

        echo '<form class="m-1" method="post" action="./scripts/borrarPokemon.php">';
        echo '<input type="hidden" name="unique_id" value="' . $row["unique_id"] . '">';
        echo '<button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>';
        echo '</form>';
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
    echo '</div>';
}