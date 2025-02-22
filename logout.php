<?php
session_start();  // Iniciar la sesión

// Destruir la sesión
session_unset();  // Elimina todas las variables de la sesión
session_destroy();  // Destruye la sesión
echo "<link rel='stylesheet' href='styles.css'>";
//matar la cookie si existe
if (isset($_COOKIE['productos'])) {
    setcookie('productos', '', time() - 3600, '/'); // Establece el tiempo en el pasado para borrar la cookie
}

// Redirigir a la página de inicio (o login)
header("Location: index.php");
exit();
?>
