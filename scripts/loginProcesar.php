<?php
session_start();
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["usuario"];
    $contraseña = $_POST["contraseña"];

    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE correo=? AND contraseña=?");
        $stmt->bind_param("ss", $correo,$contraseña);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();

    if ($res->num_rows == 1) {
        $_SESSION["usuario"] = $correo;
        $_SESSION["roleID"] = $res->fetch_assoc()["roleID"];
        header("location: ../home.php");
    } else {
        echo "Usuario o contraseña incorrectos. <a href='../index.php'>Volver a intentar</a>";
    }
}

$conexion->close();
?>
