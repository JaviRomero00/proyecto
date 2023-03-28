<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pagina principal</title>
</head>
<body>
    <?php session_start();
    if (!isset($_SESSION['tiempo'])) {
        $_SESSION['tiempo'] = time();
    }
    else if (time() - $_SESSION['tiempo'] > 30) {
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

        if (preg_match('/[A-Za-z]/', $_POST['nombre']) && preg_match('/[[:digit:]]/', $_POST['telefono'])) {

            $query = "INSERT INTO contactos (nombre, telefono)
                        VALUES ('$nombre', '$telefono') RETURNING id;";

            $res = pg_query($con, $query);

            if (pg_num_rows($res) != 0) {
                $fila = pg_fetch_array($res, 0);
                echo "Contacto guardado correctamente";
            } else {
                echo "Error al guardar el contacto";
            }
        } else {
            echo "Error al guardar el contacto, algunos caracteres no son validos";
        }
        pg_close($con);
    ?>
</body>
</html>
