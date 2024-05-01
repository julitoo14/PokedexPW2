<nav class="navbar navbar-expand-lg navcolor">
    <div class="container-fluid">
        <a href="home.php">
            <img src="./assets/logo.png"  class="pokelogo" alt="Logotipo de Pokédex">
        </a>


        <h1 style="font-size: 60px; color: white">Pokédex</h1>

        <div class="admin">
            <form method="post" action="scripts/logOut.php" style="display: flex">
                <?php
                $usuario = $_SESSION["usuario"];
                if (isset($_SESSION['roleID']) && $_SESSION['roleID'] === 1) {
                    // Si es un administrador, muestra su nombre y un botón para cerrar sesión
                    echo "<p style='font-size:25px;font-weight: bold; margin:0;margin-right: 15px'>$usuario</p><button class=\"btn btn-danger\" type=\"submit\">Cerrar Sesión</button>";
                }
                ?>
            </form>
        </div>

    </div>

</nav>