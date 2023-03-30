<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar</title>
    <link rel="stylesheet" href="diseño.css">
</head>
<body>
    <div class="formulario">
        <h1>Registrese aquí:</h1>
        <?php
        require ('conexion.php');
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                if (isset($_POST['user']) && isset($_POST['password']) && isset($_POST['password_repeat'])) {
                    $user    = md5($_POST['user']);
                    $password   = md5($_POST['password']);
                    $password_repeat = md5($_POST['password_repeat']);

                    $clases_label = [];
                    $clases_input = [];
                    $error = ['user' => [], 'password' => [], 'password_repeat' => []];

                    foreach (['user', 'password', 'password_repeat'] as $e) {
                        $clases_label[$e] = '';
                        $clases_input[$e] = '';
                    }

                    $query = "SELECT * FROM usuarios
                            WHERE usuario='$user'";
                    $resultado = pg_query($con, $query);

                    if (isset($user, $password, $password_repeat)) {

                        if ($_POST['user'] == '') {
                            $error['user'][] = 'El usuario es obligatorio.';
                        } else if(pg_num_rows($resultado) == 1) {
                            $error['user'][] = 'El usuario ya existe.';
                        } else if (mb_strlen($_POST['user']) > 20) {
                            $error['user'][] = 'El nombre de usuario es demasiado largo.';
                        }

                        if($_POST['password'] != $_POST['password_repeat']) {
                            $error['password'][] = 'Las contraseñas no coinciden.';
                            $error['password_repeat'][] = 'Las contraseñas no coinciden.';
                        }

                        if ($_POST['password'] == '') {
                            $error['password'][] = 'La contraseña es obligatoria.';
                        }

                        if ($_POST['password_repeat'] == '') {
                            $error['password_repeat'][] = 'La contraseña es obligatoria.';
                        }

                        if (preg_match('/[a-z]/', $_POST['password']) !== 1) {
                            $error['password'][] = 'Debe contener al menos una minúscula.';
                        }

                        if (preg_match('/[A-Z]/', $_POST['password']) !== 1) {
                            $error['password'][] = 'Debe contener al menos una mayúscula.';
                        }

                        if (preg_match('/[[:digit:]]/', $_POST['password']) !== 1) {
                            $error['password'][] = 'Debe contener al menos un dígito.';
                        }

                        if (preg_match('/[[:punct:]]/', $_POST['password']) !== 1) {
                            $error['password'][] = 'Debe contener al menos un signo de puntuación.';
                        }

                        if (mb_strlen($_POST['password']) < 8) {
                            $error['password'][] = 'Debe tener al menos 8 caracteres.';
                        }

                        $vacio = true;

                        foreach ($error as $err) {
                            if (!empty($err)) {
                                $vacio = false;
                                break;
                            }
                        }

                        if ($vacio) {
                            $registrar = "INSERT INTO usuarios (usuario, contrasena)
                                        VALUES ('$user', '$password')";
                            pg_query($con, $registrar);
                            header('Location: login.php');
                        }

                    }

                }
            }
        ?>
        <form action="" method="POST">
                <label for="user" class="<?= $clases_label['user'] ?>">Nombre de usuario</label>
                <input type="text" name="user" id="user" class="<?= $clases_input['user'] ?>">
                <?php foreach ($error['user'] as $err): ?>
                    <p><span class="font-bold">Error!! <?= $err ?></p>
                <?php endforeach ?>
            <br>
                <label for="password" class="<?= $clases_label['password'] ?>">Contraseña</label>
                <input type="password" name="password" id="password" class="<?= $clases_input['password'] ?>">
                <?php foreach ($error['password'] as $err): ?>
                    <p><span class="font-bold">Error!! <?= $err ?></p>
                <?php endforeach ?>
            <br>
                <label for="password_repeat" class="<?= $clases_label['password_repeat'] ?>">Confirmar Contraseña</label>
                <input type="password" name="password_repeat" id="password_repeat" class="<?= $clases_input['password_repeat'] ?>">
                <?php foreach ($error['password_repeat'] as $err): ?>
                    <p><span class="font-bold">Error!! <?= $err ?></p>
                <?php endforeach ?>
            <button type="submit"> Registrar </button>
        </form>
        <p>¿Ya tienes cuenta? Inicia sesión aquí:</p>
        <a href="login.php"> <button> Login </button> </a>
    </div>
</body>
</html>
