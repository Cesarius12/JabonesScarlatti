<?php
require ('config.php');
session_start();

echo "<link rel='stylesheet' href='styles.css'>";
$val = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['invitado'])) { 
        $_SESSION['nombre'] = 'invitado';
        $_SESSION['email']= null;
        header('Location: catalogo.php'); 
        exit();
    } else {
        
        $users = obtenerUsers();
        print_r($users);
       
        if ($users) {
            foreach ($users as $valor) {
                if ($_POST['user'] == $valor['username'] && $_POST['pass'] == $valor['pass']) {
                    $_SESSION['nombre'] = $valor['nombre'];
                    $_SESSION['email'] = $valor['email'];

                    if($valor['admin']=='1'){
                        $_SESSION['adm']=true;
                    }else{
                        $_SESSION['adm']=false;
                    }

                    if (isset($_COOKIE['productos'])) {                       
                        $_SESSION['productos'] = unserialize($_COOKIE['productos']);
                        setcookie('productos', '', time() - 3600, '/');
                    }

                    // Muestra la sesión para depuración
                    var_dump($_SESSION);
                    
                    header('Location: validaAdmin.php');
                    exit();
                }
            }
            $val = false; // Si no encuentra un usuario válido
        } else {
            $val = false; // Si no hay usuarios en la base de datos
        }
    }
}

// Si el login no es válido, muestra el formulario de nuevo
if (!$val) {
    echo "<p style='color: red;'>Nombre de usuario o contraseña incorrectos. Intenta nuevamente.</p>";
}
?>

<!-- Formulario de login -->
<form method="POST" action="index.php">
    <label for="user">Usuario:</label>
    <input type="text" name="user" id="user" required>
    <br>
    <label for="pass">Contraseña:</label>
    <input type="password" name="pass" id="pass" required>
    <br>
    <input type="submit" value="Iniciar sesión">
</form>

<!-- Botón para entrar como invitado -->
<form method="post" action="index.php">
    <input type="submit" value="Entrar como invitado" name="invitado">
</form>
