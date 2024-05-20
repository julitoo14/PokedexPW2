<nav class="navbar navbar-expand-lg navcolor">
    <div class="container-fluid">
        <a class="navbar-brand" href="/Pokedex/index.php">
            <img src="/Pokedex/assets/logo.png" class="pokelogo" alt="Logotipo de Pokédex">
        </a>

        <h1 style="font-size: 60px; color: white">Pokédex</h1>

        <div class="admin ml-auto">
            <?php
            // Comprobar si el usuario está autenticado
            if (isset($_SESSION['usuario'])) {
                // Si está autenticado, mostrar su nombre y el botón de cerrar sesión
                $usuario = $_SESSION["usuario"];
                // Mostrar el nombre de usuario y el botón de cerrar sesión en línea
                echo "<div style='display: flex; align-items: center;'>"; // Contenedor flex
                echo "<p style='font-size: 25px; margin-right: 10px; margin-bottom: 0'>$usuario</p>"; // Nombre de usuario
                echo "<form method='post' action='/Pokedex/scripts/logOut.php' style='margin-bottom: 0'><button class='btn btn-danger' type='submit'>Cerrar Sesión</button></form>"; // Botón de cerrar sesión
                echo "</div>"; // Fin del contenedor flex
            }
             else {
                // Mostrar el formulario de inicio de sesión para todos los usuarios
                echo "<form action='/Pokedex/scripts/loginProcesar.php' method='POST' class='form-inline'>
                        <div class='login'> 
                        <input type='text' class='form-control mr-sm-2' name='usuario' placeholder='Usuario' required>
                        <input type='password' class='form-control mr-sm-2' name='contraseña' placeholder='Contraseña' required>
                        <button class='btn btn-primary my-2 my-sm-0' type='submit'>Iniciar Sesión</button>
                        </div>
                    </form>";
            }
            ?>
        </div>
    </div>
</nav>