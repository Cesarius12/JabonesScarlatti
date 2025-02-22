<?php
session_start();
require("config.php");
echo "<link rel='stylesheet' href='styles.css'>";
// Depuración: Verificar si la sesión está activa
if (!isset($_SESSION["nombre"])) {
    echo "No hay sesión activa. Redirigiendo...";
    header("Location: index.php");
    exit();
}
$cat.="
Bienvenido $_SESSION[nombre]
<div style='position: absolute; top: 10px; right: 10px;'>";
    if (isset($_SESSION['nombre'])){
        $cat.="<a href='logout.php' style='padding: 10px; background-color: red; color: white; text-decoration: none;'>Cerrar sesión</a></div>";
    }
echo $cat;

$prod=obtenerProductos();
mostrarProd($prod);

?>
