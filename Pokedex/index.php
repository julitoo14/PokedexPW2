<?php
session_start();
// FORMULARIO ENVIADO BOTON DE FILTRAR.
include_once('Configuration.php');// Incluye el archivo de conexión a la base de datos
include_once ('models/PokemonModel.php');// Incluye el modelo de Pokémon
include_once ('models/TipoPokemonModel.php');// Incluye el modelo de Tipo de Pokémon
include_once ('controllers/PokemonController.php');// Incluye el controlador de Pokémon
$connection = Configuration::getDatabase(); // Obtiene la instancia de la base de datos
$pokemonController = Configuration::getPokemonController();

 // Instancia de la clase PokemonController





if (isset($_SESSION["usuario"])) {
    $usuario = $_SESSION["usuario"];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pokedex - Inicio</title>
    <link rel="stylesheet" type="text/css" href="/Pokedex/style/style.css">
    <link href="/Pokedex/assets/logo.png" rel="icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>

<?php
include_once("scripts/header.php");     //HEADER CON BARRA DE NAVEGACION




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
                        <label for="descripcion" class="form-label">Descripcion</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion">
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
    <?php
    if(isset($usuario)) {
        echo "<h2 style='font-size: 40px'>¡Bienvenido, $usuario!</h2>";
    } else {
        echo "<h2 style='font-size: 40px'>¡Bienvenido!</h2>";
    }
    ?>
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
    <div class="container">
    <div class="row">
<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $pokemonController->filter();
}else{
    $pokemonController->list();
}
?>
</div>
</div>
</div>


        <?php
        include_once("scripts/footer.php");        //FOOTER INCLUIDO
        ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>


</html>
