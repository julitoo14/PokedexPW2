<?php
session_start();
include ("database.php");


// Verificar la conexión

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //AGREGADO POR JULIAN
    //si no se enviaron los datos redirecciono a home.php
    if (!isset($_POST["id"]) || !isset($_POST["nombre"]) || !isset($_FILES["imagen"]["tmp_name"]) || !isset($_POST["altura"]) || !isset($_POST["peso"]) || !isset($_POST["tipo_id"])) {
        $_SESSION['error'] = "Por favor, completa todos los campos.";
        echo $_SESSION['error'];
        header("Location: ../home.php");
        exit;
    }
    //AGREGADO POR JULIAN

    $idPokemon = $_POST["id"];
    $nombrePokemon = $_POST["nombre"];
    $imagenPokemon = $_FILES["imagen"]["tmp_name"];
    $contenidoImagen = file_get_contents($imagenPokemon);
    $alturaPokemon = $_POST["altura"];
    $pesoPokemon = $_POST["peso"];
    $tipo_idPokemon = $_POST["tipo_id"];
    $tipo2_idPokemon = $_POST["tipo2_id"];

    //AGREGADO POR JULIAN
    // Verifico que los datos sean correctos
    if (!verificardatos($idPokemon, $nombrePokemon,  $alturaPokemon, $pesoPokemon)) {
        //si los datos no son correctos redirecciono a home.php
        header("Location: ../home.php");
        exit;
    }
    //AGREGADO POR JULIAN

    $stmt = $conexion->prepare("SELECT * FROM tipo_pokemon WHERE nombre=? ");
    $stmt->bind_param("s", $tipo_idPokemon);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $stmt->close();

    if ($resultado->num_rows == 1) {
        $row = $resultado->fetch_assoc();
        $tipo_idPokemon = $row["id"];
    }

    $stmt = $conexion->prepare("SELECT * FROM tipo_pokemon WHERE nombre=? ");
    $stmt->bind_param("s", $tipo2_idPokemon);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $stmt->close();

    if ($resultado->num_rows == 1) {
        $row = $resultado->fetch_assoc();
        $tipo2_idPokemon = $row["id"];
    }

    $sql = "INSERT INTO pokemon (id, nombre, imagen, altura, peso, tipo_id, tipo2_id) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("issddii", $idPokemon,$nombrePokemon, $contenidoImagen, $alturaPokemon, $pesoPokemon, $tipo_idPokemon, $tipo2_idPokemon);

        if ($stmt->execute()) {
            header("Location: ../home.php");
            exit;
        } else {
            echo "Error al agregar el Pokemon: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }
} else {
    echo "Acceso no autorizado.";
}

$conexion->close();

// AGREGADO POR JULIAN
function verificardatos($idPokemon, $nombrePokemon, $alturaPokemon, $pesoPokemon){
    //
    if (!is_numeric($idPokemon)){
        $_SESSION['error'] = "El id del pokemon debe ser un número.";
        return false;
    }
    //si el nombre del pokemon es menor a 3 caracteres o mayor a 20 caracteres
    if (strlen($nombrePokemon) < 3 || strlen($nombrePokemon) > 20) {
        $_SESSION['error'] = "El nombre del pokemon debe tener entre 3 y 20 caracteres.";
        return false;
    }
    if(!is_numeric($alturaPokemon)){
        $_SESSION['error'] = "La altura del pokemon debe ser un número.";
        return false;
    }
    if(!is_numeric($pesoPokemon)){
        $_SESSION['error'] = "El peso del pokemon debe ser un número.";
        return false;
    }
    return true;
}

// AGREGADO POR JULIAN


?>

