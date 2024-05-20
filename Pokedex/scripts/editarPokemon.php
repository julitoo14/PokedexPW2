<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("location: login.php");
}

$usuario = $_SESSION["usuario"];

// Incluir el archivo de conexión a la base de datos
include_once("./database.php");



// Verificar si se recibió un ID de Pokémon válido
if (!isset($_GET["unique_id"]) || empty($_GET["unique_id"])) {
    echo "ID de Pokémon no proporcionado.";
    exit();
}

// Obtener el ID del Pokémon a editar
$pokemon_id = $_GET["unique_id"];

// Consultar la información del Pokémon en la base de datos
$sql = "SELECT * FROM pokemon WHERE unique_id = $pokemon_id";
$result = $conexion->query($sql);

// Verificar si se encontró el Pokémon
if ($result->num_rows == 0) {
    echo "El Pokémon no existe.";
    exit();
}

// Obtener los datos del Pokémon
$pokemon = $result->fetch_assoc();

// Procesar el formulario de edición si se ha enviado
if (isset($_POST["nombre"])) {
    // Verificar si se recibieron los datos del formulario
    if ( isset($_POST["id"]) && isset($_POST["nombre"]) && isset($_POST["descripcion"]) &&  isset($_POST["altura"]) && isset($_POST["peso"]) && isset($_POST["tipo_id"])) {
        // Obtener los valores del formulario
        $id = $_POST["id"];
        $nombre = $_POST["nombre"];
        $altura = $_POST["altura"];
        $peso = $_POST["peso"];
        $tipo_id = $_POST["tipo_id"];
        $descripcion = $_POST["descripcion"];

        $stmt = $conexion->prepare("SELECT * FROM tipo_pokemon WHERE nombre=? ");
        $stmt->bind_param("s", $tipo_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();

        if ($resultado->num_rows == 1) {
            $row = $resultado->fetch_assoc();
            $tipo_id = $row["id"];
            echo $tipo_id;
        }

        // Verificar si se cargó una nueva imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            // Obtener la información de la imagen
            $imagen_temporal = $_FILES['imagen']['tmp_name'];
            $tipo = $_FILES['imagen']['type'];

            // Validar el tipo de archivo
            if ($tipo === 'image/jpeg' || $tipo === 'image/png') {
                // Obtener el contenido de la imagen
                $imagen_contenido = file_get_contents($imagen_temporal);

                // Actualizar la imagen en la base de datos
                $sql_update_imagen = "UPDATE pokemon SET imagen = ? WHERE unique_id = ?";
                $stmt = $conexion->prepare($sql_update_imagen);
                $stmt->bind_param("si", $imagen_contenido, $pokemon_id);
                $stmt->execute();
            }
        }

        // Preparar la consulta SQL para actualizar los datos del Pokémon
        $sql_update = "UPDATE pokemon SET id='$id', nombre = '$nombre', descripcion = '$descripcion', altura = '$altura', peso = '$peso', tipo_id = '$tipo_id' WHERE unique_id = $pokemon_id";

        // Ejecutar la consulta de actualización
        if ($conexion->query($sql_update) === TRUE) {
            // Redirigir al usuario a la página de detalle del Pokémon actualizado
            header("Location: ./detalle.php?unique_id=$pokemon_id");
            exit();
        } else {
            echo "Error al actualizar el Pokémon: " . $conexion->error;
        }
    } else {
        echo "Por favor, complete todos los campos. " ;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Pokémon</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link href="/Pokedex/assets/logo.png" rel="icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>

<?php include_once("./header.php");

$tipos = [
    1 => "Agua", 2 => "Fuego", 3 => "Planta", 4 => "Acero", 5 => "Volador",
    6 => "Hielo", 7 => "Bicho", 8 => "Electrico", 9 => "Normal", 10 => "Roca",
    11 => "Tierra", 12 => "Lucha", 13 => "Hada", 14 => "Psiquico", 15 => "Veneno",
    16 => "Dragon", 17 => "Fantasma", 18 => "Siniestro"
];
?>

<div class="container">
    <h1>Editar Pokémon</h1>

    <form method="post" action="" enctype="multipart/form-data"> <!-- Agregado enctype para permitir la carga de archivos -->
        <input type="hidden" name="unique_id" value="<?php echo $pokemon['unique_id']; ?>"> <!-- Campo oculto para enviar el ID del Pokémon -->
        <div class="mb-3">
            <label for="id" class="form-label">Id:</label>
            <input type="text" id="nombre" name="id" class="form-control" value="<?php echo $pokemon['id']; ?>">
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $pokemon['nombre']; ?>">
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripcion:</label>
            <input type="text" id="descripcion" name="descripcion" class="form-control" value="<?php echo $pokemon['descripcion']; ?>">
        </div>
        <div class="mb-3">
            <label for="altura" class="form-label">Altura:</label>
            <input type="text" id="altura" name="altura" class="form-control" value="<?php echo $pokemon['altura']; ?>">
        </div>
        <div class="mb-3">
            <label for="peso" class="form-label">Peso:</label>
            <input type="text" id="peso" name="peso" class="form-control" value="<?php echo $pokemon['peso']; ?>">
        </div>
        <div>
            <label for="tipo_id" class="form-label">Tipo:</label>
            <select class=" mb-3 form-select" id="tipo_id" name="tipo_id">
                <option value="" disabled selected>Selecciona un tipo</option>
                <?php
                foreach ($tipos as $tipo) {
                    echo '<option value="' . $tipo . '">' . ucfirst($tipo) . '</option>';
                }
                ?>
            </select>
        </div>
        <!-- Campo de carga de imagen -->
        <div class="mb-3">
            <label for="imagen" class="form-label">Nueva Imagen:</label>
            <input type="file" id="imagen" name="imagen" class="form-control" accept="image/jpeg, image/png">
        </div>
        <input type="submit" class="btn btn-primary" value="Guardar cambios">
    </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>


<?php
include_once("./footer.php");
?>

</body>
</html>

