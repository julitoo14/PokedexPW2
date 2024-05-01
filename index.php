<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link href="./assets/favicon.ico" rel="icon" type="image/x-icon">
</head>
<body class="bodyLogin">

    <form action="scripts/loginProcesar.php" method="POST">
        <div class="containerLogin">
            <h2>Iniciar Sesión</h2>
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" required><br><br>

            <label for="contraseña">Contraseña:</label>
            <input type="password" name="contraseña" required><br><br>

            <input type="submit" value="Iniciar Sesión">
        </div>
    </form>
</body>
</html>
