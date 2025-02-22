<?php
session_start();
require("config.php");
echo "<link rel='stylesheet' href='styles.css'>";
// Verificar si la sesión está iniciada
if (!isset($_SESSION['nombre'])) {
    header('Location: index.php');
    exit();
}
if ($_SESSION['adm'] == true) {
    $_SESSION["nombre"]="admin";
    $prods = obtenerProductos();
    foreach ($prods as $producto) {
        $_SESSION['idprod'][] = $producto['productoID'];
    }
    header('Location:admin.php');
} else {
    if ($_SESSION['productos']) {
        header('Location:procesaCompra.php');
    } else {
        header('Location:catalogo.php');
    }
}
?>
