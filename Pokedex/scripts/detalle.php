<?php
session_start();
include_once("database.php");

if (!isset($_GET["unique_id"]) || !is_numeric($_GET["unique_id"])) {
    header("Location: index.php");
    exit();
} else {
    $pokemonSolicitado = $_GET["unique_id"];

    $stmt = $conexion->prepare("SELECT * FROM pokemon WHERE unique_id=?");
    $stmt->bind_param("s", $pokemonSolicitado);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();
}

?>

<head>
    <meta charset="UTF-8">
    <title>Pokedex</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link href="/Pokedex/assets/logo.png" rel="icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>

<body>

<?php
include_once("header.php")     //HEADER CON BARRA DE NAVEGACION
?>

<a href="/Pokedex/index.php"><img class="back-btn" src="../assets/left-arrow.png"></a>
<div class="pokemon-detalle" >
    <div class="container-nombreyfoto" >
        <?php
        echo '<h1 class="pokemon-nombre">' . $row["nombre"] . '</h1>';
        echo   '<img style="height:300px; width:300px; " class="pokemon-imagen" src="data:image/jpeg;base64,' . base64_encode($row["imagen"]) . '" >';
        ?>
    </div>
    <div class="container-info">
        <?php
        if (!empty($row["tipo2_id"])) {
            $tipo1_imagen = '/Pokedex/assets/tipos/' . strtolower($row["tipo_id"]) . '.webp';
            $tipo2_imagen = '/Pokedex/assets/tipos/' . strtolower($row["tipo2_id"]) . '.webp';

            echo '<img class="m-1 imagen-tipo" src="' . $tipo1_imagen . '" alt="' . $row["tipo_id"] . '">';
            echo '<img class="m-1 imagen-tipo" src="' . $tipo2_imagen . '" alt="' . $row["tipo2_id"] . '">';
        } else {
            $tipo1_imagen = '/Pokedex/assets/tipos/' . strtolower($row["tipo_id"]) . '.webp';

            echo '<img  class="m-1 imagen-tipo" src="' . $tipo1_imagen . '" alt="' . $row["tipo_id"] . '">';
        }
        ?>

        <?php
        echo '<p class="pokemon-descripcion">' .  $row["descripcion"]. '</p>';
        echo '<p class="pokemon-altpeso">' . '# Pokémon: '  . $row["id"]. '</p>';
        echo '<p class="pokemon-altpeso">' . 'Altura: '  . $row["altura"]. ' mts' . '</p>';
        echo '<p class="pokemon-altpeso">' . 'Peso: '  . $row["peso"]. ' kg' . '</p>';
        ?>
    </div>
</div>

<?php
include_once("footer.php")     //FOOTER
?>

</body>


