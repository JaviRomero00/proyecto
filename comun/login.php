<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="diseño.css">
</head>
<body>
    <div class="formulario">
        <h1>Login</h1>
        <?php if (isset($error)) { ?>
            <p><?php echo $error; ?></p>
        <?php } ?>

        <form action="login.php" method="POST">
            <label for="user">Introduzca su nombre de usuario:</label>
            <input type="text" id="user" name="user">
            <br>

            <label for="password">Introduzca su Contraseña:</label>
            <input type="password" id="password" name="password">
            <br>

            <input type="submit" value="Iniciar Sesión">
        </form>

        <?php
            session_start();
            $intentos = 3;
            require('conexion.php');

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                if (isset($_POST['user']) && isset($_POST['password'])) {
                    $user    = md5($_POST['user']);
                    $password   = md5($_POST['password']);

                    $query = "SELECT * FROM usuarios
                            WHERE usuario='$user' AND
                            contrasena='$password'";
                    $resultado = pg_query($con, $query);

                        if(pg_num_rows($resultado) == 1) {
                            $_SESSION['intentos'] = 0;
                            $_SESSION['user'] = $user;
                            header("Location: index.php");
                        } else {
                            if (!isset($_SESSION['intentos'])) {
                                $_SESSION['intentos'] = 0;
                            }
                            $_SESSION['intentos']++;

                            if ($_SESSION['intentos'] >= $intentos) {
                                $_SESSION['intentos'] = 0;
                                header("Location: error.html");
                            } else {
                                echo "<div class='error'>Nombre de usuario o contraseña incorrectos. Intento Nº " . $_SESSION['intentos'] . "</div>";
                            }
                        }
                }
            }
            pg_close($con);
        ?>

        <p>¿No tienes cuenta? Registrese aqui: </p>
        <a href="registrar.php">
            <button> Registrarse </button>
        </a>
    </div>
</body>
</html>
