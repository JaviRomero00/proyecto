<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pagina principal</title>
    <link rel="stylesheet" href="diseño.css">
</head>
<body>
    <div class="formulario">
        <?php session_start();
        if (!isset($_SESSION['tiempo'])) {
            $_SESSION['tiempo'] = time();
        }
        else if (time() - $_SESSION['tiempo'] > 180) {
            session_destroy();
            header("Location: login.php");
            die();
        }
        $_SESSION['tiempo'] = time();
        if (!isset($_SESSION['user'])) {
            header('Location: login.php');
            exit;
        } else {
        ?>

            <h1>Guardar contacto</h1>
            <form action="index.php" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required>
                <br>
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" required>
                <br>
                <input type="submit" value="Guardar">
            </form>
        <?php } ?>
        <?php
            require 'conexion.php';
            $nombre   = $_POST['nombre'];
            $telefono = $_POST['telefono'];

            if (preg_match('/^[a-zA-Z\s]+$/', $_POST['nombre']) && preg_match('/[[:digit:]]/', $_POST['telefono'])) {
                $query = "INSERT INTO contactos (nombre, telefono)
                            VALUES ('$nombre', '$telefono') RETURNING id;";

                $consulta = "SELECT * FROM contactos
                            WHERE telefono = '$telefono';";

                $res = pg_query($con, $consulta);

                if (pg_num_rows($res) == 0) {
                    pg_query($con, $query);
                    echo "<div class='aceptar'>Contacto guardado correctamente</div>";
                } else {
                    echo "<div class='error'>Error al guardar el contacto, el teléfono ya esta registrado</div>";
                }
            } if (!preg_match('/^[a-zA-Z\s]+$/', $_POST['nombre']) || !preg_match('/[[:digit:]]/', $_POST['telefono'])){
                echo "<div class='error'>Error al guardar el contacto, algunos caracteres no son validos</div>";
            }
            pg_close($con);
        ?>
        <br>
        <a href="logout.php">
            <button> Cerrar sesión</button>
        </a>
    </div>
</body>
</html>
