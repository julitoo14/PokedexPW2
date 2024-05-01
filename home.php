<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("location: login.php");
}

$usuario = $_SESSION["usuario"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pokedex - Inicio</title>
    <link rel="stylesheet" type="text/css" href="./style/style.css">
    <link href="/PW2_Pokedex/assets/logo.png" rel="icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>

<?php
include_once ("header.php")     //HEADER CON BARRA DE NAVEGACION
?>


<?php
$tipos = [
    1 => "Agua", 2 => "Fuego", 3 => "Planta", 4 => "Acero", 5 => "Volador",
    6 => "Hielo", 7 => "Bicho", 8 => "Electrico", 9 => "Normal", 10 => "Roca",
    11 => "Tierra", 12 => "Lucha", 13 => "Hada", 14 => "Psiquico", 15 => "Veneno",
    16 => "Dragon", 17 => "Fantasma", 18 => "Siniestro"
];
?>

<div class="modal fade" id="nuevoPokemonModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Pokemon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="scripts/agregarPokemon.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="id" class="form-label">ID</label>
                        <input type="text" class="form-control" id="id" name="id">
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre">
                    </div>
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen</label>
                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="altura" class="form-label">Altura</label>
                        <input type="text" class="form-control" id="altura" name="altura" pattern="[0-9]+(\.[0-9]+)?">
                    </div>
                    <div class="mb-3">
                        <label for="peso" class="form-label">Peso (kg)</label>
                        <input type="text" class="form-control" id="peso" name="peso" pattern="[0-9]+(\.[0-9]+)?">
                    </div>
                    <div class="mb-3">
                        <label for="tipo_id" class="form-label">Tipo</label>
                        <select class="form-select" id="tipo_id" name="tipo_id" onchange="actualizarTipo2()">
                            <option value="" disabled selected>Selecciona un tipo</option>
                            <?php
                            foreach ($tipos as $tipo) {
                                echo '<option value="' . $tipo . '">' . ucfirst($tipo) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tipo2_id" class="form-label">Tipo 2</label>
                        <select class="form-select" id="tipo2_id" name="tipo2_id">
                            <option value="" disabled selected>Selecciona un tipo</option>
                        </select>
                    </div>
                    <?php
                    //AGREGADO POR JULIAN
                    // Muestro errores de validación
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                        unset($_SESSION['error']);
                    }
                    //AGREGADO POR JULIAN
                    ?>
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </form>
            </div>
        </div>
    </div>
</div>




<script>
    function actualizarTipo2() {
        const tipo1 = document.getElementById('tipo_id').value;
        const tipo2Select = document.getElementById('tipo2_id');

        tipo2Select.innerHTML = '<option value="" disabled selected>Selecciona un tipo</option>';

        <?php
        foreach ($tipos as $tipo) {
            echo 'if ("' . $tipo . '" !== tipo1) {';
            echo 'tipo2Select.innerHTML += \'<option value="' . $tipo . '">' . ucfirst($tipo) . '</option>\';';
            echo '}';
        }
        ?>
    }

    actualizarTipo2();
</script>

<div class="container">
    <br>
    <h2>¡Bienvenido, <?php echo $usuario; ?>!</h2>
    <br>

    <form class="form-inline mb-3" method="post" action="">
        <h5 style="font-weight: bolder">Busqueda de Pokemones:</h5>
        <div class="d-flex align-items-center">
            <div class="form-group mb-2">
                <select class="form-control" name="tipo" id="tipo">
                    <option value="" selected>Todos</option>
                    <?php
                    foreach ($tipos as $numeroReferencia => $tipo) {
                        echo '<option value="' . $numeroReferencia . '">' . ucfirst($tipo) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group mx-sm-3 mb-2"> <!-- Cambiado a mx-sm-3 para más espacio horizontal -->
                <input type="text" class="busqueda" name="nombre" id="nombre" placeholder="Ingrese ID o nombre del Pokemon">
            </div>
            <div class="form-group mb-2">
                <button type="submit" class="btn btn-primary ml-2" style="margin-left: 10px">Filtrar</button>
            </div>
            <div class="form-group mb-2"> <!-- Añadido un contenedor form-group para el botón Nuevo Pokémon -->
                <?php
                if (isset($_SESSION['roleID']) && $_SESSION['roleID'] === 1) {
                    echo '<button class="btn btn-danger pokemon-nuevo-btn" type="button" data-bs-toggle="modal" data-bs-target="#nuevoPokemonModal">Nuevo Pokemon<i class="bi bi-plus" style="margin-left: 5px"></i></button>';
                }
                ?>
            </div>

        </div>

    </form>


    <?php

    // FORMULARIO ENVIADO BOTON DE FILTRAR.
    include("scripts/database.php"); // Incluye el archivo de conexión a la base de datos

    // Variable para almacenar la consulta SQL
    $sql = "SELECT * FROM pokemon ORDER BY id ASC";

    // Verifica si se ha enviado el formulario de filtrado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Procesa los datos del formulario de filtrado
        $tipo = $_POST["tipo"];
        $nombre = $_POST["nombre"];

        // Si se seleccionó un tipo
        if ($tipo !== "" && $tipo !== "todos") {
            $sql = "SELECT * FROM pokemon WHERE tipo_id = '$tipo' ORDER BY id ASC";
        } else {
            $sql = "SELECT * FROM pokemon ORDER BY id ASC"; // Mostrar todos los Pokémon
        }

        // Si se ingresó un nombre o un ID
        if (!empty($nombre)) {
            // Verifica si el valor ingresado es un número
            if (is_numeric($nombre)) {
                // Si es un número, busca por ID
                $sql = "SELECT * FROM pokemon WHERE id = '$nombre' ORDER BY id ASC"; // Asegúrate de incluir $nombre entre comillas simples
            } else {
                // Si no es un número, busca por nombre
                $sql = "SELECT * FROM pokemon WHERE nombre LIKE '%$nombre%' ORDER BY id ASC";
            }
        }

        // Si se seleccionó un tipo y se ingresó un nombre
        if (!empty($tipo) && !empty($nombre)) {
            $sql = "SELECT * FROM pokemon WHERE tipo_id = '$tipo' AND nombre LIKE '%$nombre%' ORDER BY id ASC";
        }
    }

    $result = $conexion->query($sql);
    ?>
    <div class="container">
    <div class="row">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col-md-3 mb-4">';
                echo '<div class="card text-center">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title" style="font-weight: bolder">' . $row["nombre"] . '</h5>';
                echo "<a href=\"/detalle.php?id={$row['id']}\"><img src=\"data:image/jpeg;base64," . base64_encode($row["imagen"]) . "\" class=\"imagen-pokemon mx-auto\" alt=\"" . $row["nombre"] . "\"></a>";
                echo '<p class="card-text">Nro: ' . $row["id"] . '</p>';

                if (!empty($row["tipo2_id"])) {
                    $tipo1_imagen = './assets/tipos/' . strtolower($row["tipo_id"]) . '.webp';
                    $tipo2_imagen = './assets/tipos/' . strtolower($row["tipo2_id"]) . '.webp';

                    echo '<img class="m-1 imagen-tipo" src="' . $tipo1_imagen . '" alt="' . $row["tipo_id"] . '">';
                    echo '<img class="m-1 imagen-tipo" src="' . $tipo2_imagen . '" alt="' . $row["tipo2_id"] . '">';
                } else {
                    $tipo1_imagen = './assets/tipos/' . strtolower($row["tipo_id"]) . '.webp';

                    echo '<img class="m-1 imagen-tipo" src="' . $tipo1_imagen . '" alt="' . $row["tipo_id"] . '">';
                }

                if (isset($_SESSION['roleID']) && $_SESSION['roleID'] === 1) {
                    echo '<div class="d-flex justify-content-center m-2">';
                    echo '<form class="m-1" method="post" action="./scripts/editarPokemon.php">';
                    echo '<input type="hidden" name="pokemon_id" value="' . $row["id"] . '">';
                    echo '<button type="submit" class="btn btn-primary"><i class="bi bi-pencil"></i></button>';
                    echo '</form>';

                    echo '<form class="m-1" method="post" action="./scripts/borrarPokemon.php">';
                    echo '<input type="hidden" name="id" value="' . $row["id"] . '">';
                    echo '<button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>';
                    echo '</form>';
                    echo '</div>';
                }

                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "No se encontraron Pokémon.";
        }
        ?>
</div>
</div>
</div>


        <?php
        include_once ("footer.php");        //FOOTER INCLUIDO
        ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>


</html>
